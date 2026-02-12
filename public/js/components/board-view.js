// Board View Component
const { createApp, ref, onMounted, onUnmounted } = Vue;

const BoardView = {
    template: `
        <div class="flex-1 px-4 sm:px-6 py-5 sm:py-6 overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-white">Kanban Board</h2>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-2 py-1 text-xs font-medium text-emerald-400 border border-emerald-500/30">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Live updates
                    </span>
                    <button @click="createNewTask" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        New Task
                    </button>
                </div>
            </div>

            <div v-if="loading" class="text-center py-8">
                <p class="text-slate-400">Loading board...</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Backlog Column -->
                <div 
                    class="rounded-xl border border-slate-800 bg-slate-950/60 p-4" 
                    @drop="handleDrop($event, 'backlog')" 
                    @dragover="handleDragOver"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-slate-500"></span>
                            <h3 class="font-medium text-white">Backlog</h3>
                        </div>
                        <span class="text-xs text-slate-400">{{ getTasksByStatus('backlog').length }}</span>
                    </div>
                    <div class="space-y-2">
                        <div v-if="getTasksByStatus('backlog').length === 0" class="text-slate-500 text-xs">
                            No tasks in backlog
                        </div>
                        <div 
                            v-for="task in getTasksByStatus('backlog')" 
                            :key="task.id"
                            draggable="true" 
                            @dragstart="handleDragStart($event, task.id)" 
                            @click="openTaskModal(task.id)"
                            class="rounded-lg border border-slate-700 bg-slate-800/50 p-3 cursor-move hover:border-slate-600"
                        >
                            <h4 class="font-medium text-white text-sm mb-2">{{ task.title }}</h4>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center rounded-full bg-blue-500/10 px-2 py-1 text-xs text-blue-400">{{ task.priority || 'Medium' }}</span>
                                <div class="h-6 w-6 rounded-full bg-indigo-500 text-white text-xs flex items-center justify-center">
                                    {{ getAssigneeInitial(task) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- In Progress Column -->
                <div 
                    class="rounded-xl border border-slate-800 bg-slate-950/60 p-4" 
                    @drop="handleDrop($event, 'in_progress')" 
                    @dragover="handleDragOver"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-blue-400"></span>
                            <h3 class="font-medium text-white">In Progress</h3>
                        </div>
                        <span class="text-xs text-slate-400">{{ getTasksByStatus('in_progress').length }}</span>
                    </div>
                    <div class="space-y-2">
                        <div v-if="getTasksByStatus('in_progress').length === 0" class="text-slate-500 text-xs">
                            No tasks in progress
                        </div>
                        <div 
                            v-for="task in getTasksByStatus('in_progress')" 
                            :key="task.id"
                            draggable="true" 
                            @dragstart="handleDragStart($event, task.id)" 
                            @click="openTaskModal(task.id)"
                            class="rounded-lg border border-slate-700 bg-slate-800/50 p-3 cursor-move hover:border-slate-600"
                        >
                            <h4 class="font-medium text-white text-sm mb-2">{{ task.title }}</h4>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center rounded-full bg-green-500/10 px-2 py-1 text-xs text-green-400">{{ task.priority || 'Medium' }}</span>
                                <div class="h-6 w-6 rounded-full bg-purple-500 text-white text-xs flex items-center justify-center">
                                    {{ getAssigneeInitial(task) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Done Column -->
                <div 
                    class="rounded-xl border border-slate-800 bg-slate-950/60 p-4" 
                    @drop="handleDrop($event, 'completed')" 
                    @dragover="handleDragOver"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            <h3 class="font-medium text-white">Done</h3>
                        </div>
                        <span class="text-xs text-slate-400">{{ getTasksByStatus('completed').length }}</span>
                    </div>
                    <div class="space-y-2">
                        <div v-if="getTasksByStatus('completed').length === 0" class="text-slate-500 text-xs">
                            No completed tasks
                        </div>
                        <div 
                            v-for="task in getTasksByStatus('completed')" 
                            :key="task.id"
                            @click="openTaskModal(task.id)"
                            class="rounded-lg border border-slate-700 bg-slate-800/50 p-3 cursor-pointer hover:border-slate-600"
                        >
                            <h4 class="font-medium text-white text-sm mb-2">{{ task.title }}</h4>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center rounded-full bg-gray-500/10 px-2 py-1 text-xs text-gray-400">Completed</span>
                                <span class="text-xs text-slate-400">{{ formatDate(task.updated_at) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Detail Modal -->
            <task-modal 
                v-if="showTaskModal"
                :task="selectedTask"
                :comments="taskComments"
                :attachments="taskAttachments"
                @close="closeTaskModal"
                @comment-added="handleCommentAdded"
                @attachment-uploaded="handleAttachmentUploaded"
            ></task-modal>
        </div>
    `,
    setup(props, { emit }) {
        const tasks = ref([]);
        const loading = ref(false);
        const draggedTaskId = ref(null);
        const selectedTaskId = ref(null);
        const selectedTask = ref(null);
        const showTaskModal = ref(false);
        const taskComments = ref([]);
        const taskAttachments = ref([]);

        const api = axios.create({
            baseURL: '/api/v1'
        });

        // Add auth token to requests
        api.interceptors.request.use(config => {
            const token = localStorage.getItem('auth_token');
            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }
            return config;
        });

        const loadTasks = async () => {
            loading.value = true;
            try {
                const response = await api.get('/tasks');
                tasks.value = response.data.data;
            } catch (err) {
                console.error('Failed to load tasks:', err);
            } finally {
                loading.value = false;
            }
        };

        const getTasksByStatus = (status) => {
            return tasks.value.filter(task => task.status === status);
        };

        const getAssigneeInitial = (task) => {
            return task.assignee?.name?.charAt(0).toUpperCase() || 'U';
        };

        const formatDate = (dateString) => {
            if (!dateString) return 'Recently';
            return new Date(dateString).toLocaleDateString();
        };

        // Drag and Drop Functions
        const handleDragStart = (event, taskId) => {
            draggedTaskId.value = taskId;
            event.dataTransfer.effectAllowed = 'move';
        };

        const handleDragOver = (event) => {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
        };

        const handleDrop = async (event, newStatus) => {
            event.preventDefault();
            if (!draggedTaskId.value) return;

            try {
                const task = tasks.value.find(t => t.id === draggedTaskId.value);
                if (task) {
                    task.status = newStatus;
                    
                    await api.put(`/tasks/${draggedTaskId.value}`, {
                        status: newStatus
                    });

                    // Real-time update will be handled by Laravel Echo
                }
            } catch (err) {
                console.error('Failed to update task status:', err);
                // Revert the status on error
                const task = tasks.value.find(t => t.id === draggedTaskId.value);
                if (task) {
                    // You might want to reload the task from server
                    loadTasks();
                }
            } finally {
                draggedTaskId.value = null;
            }
        };

        const createNewTask = async () => {
            const taskTitle = prompt('Enter task title:');
            if (taskTitle) {
                try {
                    const response = await api.post('/tasks', {
                        title: taskTitle,
                        description: 'New task description',
                        status: 'backlog',
                        priority: 'medium'
                    });
                    tasks.value.push(response.data);
                } catch (err) {
                    console.error('Failed to create task:', err);
                    alert('Failed to create task. Please try again.');
                }
            }
        };

        // Task Modal Functions
        const openTaskModal = async (taskId) => {
            try {
                const [taskRes, commentsRes, attachmentsRes] = await Promise.all([
                    api.get(`/tasks/${taskId}`),
                    api.get(`/tasks/${taskId}/comments`),
                    api.get(`/tasks/${taskId}/attachments`)
                ]);
                selectedTask.value = taskRes.data;
                taskComments.value = commentsRes.data;
                taskAttachments.value = attachmentsRes.data;
                showTaskModal.value = true;
            } catch (err) {
                console.error('Failed to load task details:', err);
            }
        };

        const closeTaskModal = () => {
            showTaskModal.value = false;
            selectedTask.value = null;
            taskComments.value = [];
            taskAttachments.value = [];
        };

        const handleCommentAdded = (comment) => {
            taskComments.value.push(comment);
        };

        const handleAttachmentUploaded = (attachment) => {
            taskAttachments.value.push(attachment);
        };

        // Real-time Updates
        const initializeRealtimeUpdates = () => {
            if (typeof window.Echo !== 'undefined') {
                // Listen for task updates
                window.Echo.private('project.1') // This should be dynamic based on selected project
                    .listen('TaskUpdated', (e) => {
                        const taskIndex = tasks.value.findIndex(t => t.id === e.task.id);
                        if (taskIndex !== -1) {
                            tasks.value[taskIndex] = e.task;
                        }
                    })
                    .listen('TaskMoved', (e) => {
                        const taskIndex = tasks.value.findIndex(t => t.id === e.task.id);
                        if (taskIndex !== -1) {
                            tasks.value[taskIndex] = e.task;
                        }
                    });
            }
        };

        const leaveProjectChannel = () => {
            if (typeof window.Echo !== 'undefined') {
                window.Echo.leave('project.1'); // This should be dynamic
            }
        };

        onMounted(() => {
            loadTasks();
            initializeRealtimeUpdates();
        });

        onUnmounted(() => {
            leaveProjectChannel();
        });

        return {
            tasks,
            loading,
            draggedTaskId,
            selectedTask,
            showTaskModal,
            taskComments,
            taskAttachments,
            getTasksByStatus,
            getAssigneeInitial,
            formatDate,
            handleDragStart,
            handleDragOver,
            handleDrop,
            createNewTask,
            openTaskModal,
            closeTaskModal,
            handleCommentAdded,
            handleAttachmentUploaded
        };
    }
};

// Register the component
window.BoardView = BoardView;
