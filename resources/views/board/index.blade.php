<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kanban Board
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">Kanban Board</h1>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 border border-green-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-400 animate-pulse"></span>
                                Live updates
                            </span>
                            <button onclick="showCreateTaskModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                New Task
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Backlog Column -->
                        <div class="bg-gray-50 rounded-lg p-4" ondrop="handleDrop(event, 'backlog')" ondragover="handleDragOver(event)">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-gray-500"></span>
                                    <h3 class="font-medium text-gray-900">Backlog</h3>
                                </div>
                                <span class="text-xs text-gray-500">{{ $tasks->where('status', 'backlog')->count() }}</span>
                            </div>
                            <div class="space-y-2" id="backlog-tasks">
                                @forelse ($tasks->where('status', 'backlog') as $task)
                                    <div draggable="true" ondragstart="handleDragStart(event, {{ $task->id }})" onclick="showTaskModal({{ $task->id }})" class="bg-white border border-gray-200 rounded-lg p-3 cursor-pointer hover:shadow-sm">
                                        <h4 class="font-medium text-gray-900 text-sm mb-2">{{ $task->title }}</h4>
                                        <div class="flex items-center justify-between">
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-800">{{ ucfirst($task->priority ?? 'Medium') }}</span>
                                            <div class="h-6 w-6 rounded-full bg-indigo-500 text-white text-xs flex items-center justify-center">
                                                {{ $task->assignee ? strtoupper(substr($task->assignee->name, 0, 1)) : 'U' }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-xs">No tasks in backlog</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- In Progress Column -->
                        <div class="bg-blue-50 rounded-lg p-4" ondrop="handleDrop(event, 'in_progress')" ondragover="handleDragOver(event)">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-blue-400"></span>
                                    <h3 class="font-medium text-gray-900">In Progress</h3>
                                </div>
                                <span class="text-xs text-gray-500">{{ $tasks->where('status', 'in_progress')->count() }}</span>
                            </div>
                            <div class="space-y-2" id="in-progress-tasks">
                                @forelse ($tasks->where('status', 'in_progress') as $task)
                                    <div draggable="true" ondragstart="handleDragStart(event, {{ $task->id }})" onclick="showTaskModal({{ $task->id }})" class="bg-white border border-gray-200 rounded-lg p-3 cursor-pointer hover:shadow-sm">
                                        <h4 class="font-medium text-gray-900 text-sm mb-2">{{ $task->title }}</h4>
                                        <div class="flex items-center justify-between">
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs text-green-800">{{ ucfirst($task->priority ?? 'Medium') }}</span>
                                            <div class="h-6 w-6 rounded-full bg-purple-500 text-white text-xs flex items-center justify-center">
                                                {{ $task->assignee ? strtoupper(substr($task->assignee->name, 0, 1)) : 'U' }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-xs">No tasks in progress</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Done Column -->
                        <div class="bg-green-50 rounded-lg p-4" ondrop="handleDrop(event, 'completed')" ondragover="handleDragOver(event)">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-green-400"></span>
                                    <h3 class="font-medium text-gray-900">Done</h3>
                                </div>
                                <span class="text-xs text-gray-500">{{ $tasks->where('status', 'completed')->count() }}</span>
                            </div>
                            <div class="space-y-2" id="completed-tasks">
                                @forelse ($tasks->where('status', 'completed') as $task)
                                    <div onclick="showTaskModal({{ $task->id }})" class="bg-white border border-gray-200 rounded-lg p-3 cursor-pointer hover:shadow-sm">
                                        <h4 class="font-medium text-gray-900 text-sm mb-2">{{ $task->title }}</h4>
                                        <div class="flex items-center justify-between">
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-800">Completed</span>
                                            <span class="text-xs text-gray-500">{{ $task->updated_at ? \Carbon\Carbon::parse($task->updated_at)->format('M j') : 'Recently' }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-xs">No completed tasks</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Modal -->
    <div id="taskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 id="taskModalTitle" class="text-xl font-semibold text-gray-900">Task Details</h3>
                    <button onclick="hideTaskModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div id="taskModalContent" class="p-6">
                Loading...
            </div>
        </div>
    </div>

    <!-- Create Task Modal -->
    <div id="createTaskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl max-w-md w-full">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">Create New Task</h3>
                    <button onclick="hideCreateTaskModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <form onsubmit="createTask(event)" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" id="taskTitle" required class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="Enter task title">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select id="taskPriority" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="taskDescription" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="Enter task description"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Create Task
                    </button>
                    <button type="button" onclick="hideCreateTaskModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    let draggedTaskId = null;

    function handleDragStart(event, taskId) {
        draggedTaskId = taskId;
        event.dataTransfer.effectAllowed = 'move';
    }

    function handleDragOver(event) {
        event.preventDefault();
        event.dataTransfer.dropEffect = 'move';
    }

    function handleDrop(event, newStatus) {
        event.preventDefault();
        if (!draggedTaskId) return;

        fetch(`/board/tasks/${draggedTaskId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error updating task:', error);
        })
        .finally(() => {
            draggedTaskId = null;
        });
    }

    function showTaskModal(taskId) {
        document.getElementById('taskModal').style.display = 'flex';
        document.getElementById('taskModalContent').innerHTML = 'Loading...';
        
        fetch(`/board/tasks/${taskId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const task = data.task;
                    document.getElementById('taskModalTitle').textContent = task.title;
                    document.getElementById('taskModalContent').innerHTML = `
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                                <p class="text-gray-600">${task.description || 'No description provided'}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Status</h4>
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-800">
                                        ${task.status}
                                    </span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Priority</h4>
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs text-green-800">
                                        ${task.priority}
                                    </span>
                                </div>
                            </div>
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('taskModalContent').innerHTML = '<p class="text-red-600">Failed to load task details</p>';
            });
    }

    function hideTaskModal() {
        document.getElementById('taskModal').style.display = 'none';
    }

    function showCreateTaskModal() {
        document.getElementById('createTaskModal').style.display = 'flex';
    }

    function hideCreateTaskModal() {
        document.getElementById('createTaskModal').style.display = 'none';
        document.getElementById('taskTitle').value = '';
        document.getElementById('taskDescription').value = '';
        document.getElementById('taskPriority').value = 'medium';
    }

    function createTask(event) {
        event.preventDefault();
        
        const title = document.getElementById('taskTitle').value;
        const description = document.getElementById('taskDescription').value;
        const priority = document.getElementById('taskPriority').value;
        
        fetch('/board/tasks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                title: title,
                description: description,
                priority: priority
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                hideCreateTaskModal();
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error creating task:', error);
        });
    }
    </script>

    @csrf
    <meta name="csrf-token" content="{{ csrf_token() }}">
</x-app-layout>
