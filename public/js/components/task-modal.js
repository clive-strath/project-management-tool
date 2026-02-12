// Task Modal Component
const { createApp, ref, reactive } = Vue;

const TaskModal = {
    template: `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-slate-900 rounded-xl border border-slate-800 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-slate-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-white">{{ task?.title || 'Task Details' }}</h3>
                        <button @click="$emit('close')" class="text-slate-400 hover:text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Task Description -->
                    <div>
                        <h4 class="text-sm font-medium text-slate-300 mb-2">Description</h4>
                        <p class="text-slate-400">{{ task?.description || 'No description provided' }}</p>
                    </div>

                    <!-- Task Details -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-slate-300 mb-2">Status</h4>
                            <span class="inline-flex items-center rounded-full bg-blue-500/10 px-2 py-1 text-xs text-blue-400">
                                {{ task?.status || 'Unknown' }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-slate-300 mb-2">Priority</h4>
                            <span class="inline-flex items-center rounded-full bg-green-500/10 px-2 py-1 text-xs text-green-400">
                                {{ task?.priority || 'Medium' }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-slate-300 mb-2">Assignee</h4>
                            <p class="text-slate-400">{{ task?.assignee?.name || 'Unassigned' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-slate-300 mb-2">Due Date</h4>
                            <p class="text-slate-400">{{ formatDate(task?.due_date) || 'No due date' }}</p>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div>
                        <h4 class="text-sm font-medium text-slate-300 mb-4">Comments</h4>
                        
                        <!-- Add Comment -->
                        <div class="mb-4">
                            <div class="flex gap-2">
                                <input 
                                    v-model="newComment" 
                                    type="text" 
                                    placeholder="Add a comment..."
                                    class="flex-1 rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                    @keyup.enter="addComment"
                                >
                                <button 
                                    @click="addComment"
                                    :disabled="!newComment.trim()"
                                    class="bg-indigo-500 hover:bg-indigo-600 disabled:opacity-50 text-white px-4 py-2 rounded-lg text-sm font-medium"
                                >
                                    Post
                                </button>
                            </div>
                        </div>

                        <!-- Comments List -->
                        <div class="space-y-3">
                            <div v-if="comments.length === 0" class="text-slate-500 text-sm">
                                No comments yet
                            </div>
                            <div 
                                v-for="comment in comments" 
                                :key="comment.id"
                                class="bg-slate-800/50 rounded-lg p-3"
                            >
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-white">{{ comment.user?.name || 'Unknown' }}</span>
                                    <span class="text-xs text-slate-400">{{ formatDate(comment.created_at) }}</span>
                                </div>
                                <p class="text-sm text-slate-300">{{ comment.content }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments Section -->
                    <div>
                        <h4 class="text-sm font-medium text-slate-300 mb-4">Attachments</h4>
                        
                        <!-- Upload Attachment -->
                        <div class="mb-4">
                            <input 
                                type="file" 
                                @change="handleFileUpload"
                                class="hidden"
                                ref="fileInput"
                            >
                            <button 
                                @click="$refs.fileInput.click()"
                                class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg text-sm font-medium"
                            >
                                Upload File
                            </button>
                        </div>

                        <!-- Attachments List -->
                        <div class="space-y-2">
                            <div v-if="attachments.length === 0" class="text-slate-500 text-sm">
                                No attachments
                            </div>
                            <div 
                                v-for="attachment in attachments" 
                                :key="attachment.id"
                                class="flex items-center justify-between bg-slate-800/50 rounded-lg p-3"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded bg-indigo-500/20 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-white">{{ attachment.filename }}</p>
                                        <p class="text-xs text-slate-400">{{ formatFileSize(attachment.size) }}</p>
                                    </div>
                                </div>
                                <button class="text-indigo-400 hover:text-indigo-300 text-sm">
                                    Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `,
    props: {
        task: {
            type: Object,
            required: true
        },
        comments: {
            type: Array,
            default: () => []
        },
        attachments: {
            type: Array,
            default: () => []
        }
    },
    setup(props, { emit }) {
        const newComment = ref('');
        const fileInput = ref(null);

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

        const addComment = async () => {
            if (!newComment.value.trim() || !props.task) return;
            
            try {
                const response = await api.post(`/tasks/${props.task.id}/comments`, {
                    content: newComment.value
                });
                emit('comment-added', response.data);
                newComment.value = '';
            } catch (err) {
                console.error('Failed to add comment:', err);
                alert('Failed to add comment. Please try again.');
            }
        };

        const handleFileUpload = async (event) => {
            const file = event.target.files[0];
            if (!file || !props.task) return;
            
            try {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('task_id', props.task.id);
                
                const response = await api.post(`/tasks/${props.task.id}/attachments`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                emit('attachment-uploaded', response.data);
                
                // Reset file input
                event.target.value = '';
            } catch (err) {
                console.error('Failed to upload attachment:', err);
                alert('Failed to upload file. Please try again.');
            }
        };

        const formatDate = (dateString) => {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString();
        };

        const formatFileSize = (bytes) => {
            if (!bytes) return 'Unknown size';
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(1024));
            return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
        };

        return {
            newComment,
            fileInput,
            addComment,
            handleFileUpload,
            formatDate,
            formatFileSize
        };
    }
};

// Register the component
window.TaskModal = TaskModal;
