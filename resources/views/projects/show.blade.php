<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('projects.index') }}" class="text-gray-600 hover:text-gray-800">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                            </a>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $project['name'] }}</h1>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800">
                                {{ ucfirst($project->status ?? 'Active') }}
                            </span>
                            @if($project->owner_id == Auth::id())
                                    <a href="{{ route('projects.members', $project->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Manage Members
                                </a>
                            @endif
                            <a href="{{ route('board') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                                View Board
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Project Details -->
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Project Details</h3>
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                                        <p class="text-gray-600">{{ $project->description ?? 'No description provided' }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 mb-2">Created</h4>
                                            <p class="text-gray-600">{{ $project->created_at->format('M j, Y') }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 mb-2">Last Updated</h4>
                                            <p class="text-gray-600">{{ $project->updated_at->format('M j, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Project Progress -->
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Project Progress</h3>
                                <div class="space-y-4">
                                    @php
                                        $completionPercentage = ($totalTasks ?? 0) > 0 ? round((($completedTasks ?? 0) / ($totalTasks ?? 1)) * 100) : 0;
                                    @endphp
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-gray-700">Overall Progress</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $completionPercentage }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-3">
                                            <div class="bg-indigo-600 h-3 rounded-full transition-all duration-300" style="width: {{ $completionPercentage }}%"></div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 pt-2">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-gray-900">{{ $totalTasks ?? 0 }}</div>
                                            <div class="text-xs text-gray-500">Total Tasks</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-blue-600">{{ $inProgressTasks ?? 0 }}</div>
                                            <div class="text-xs text-gray-500">In Progress</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-green-600">{{ $completedTasks ?? 0 }}</div>
                                            <div class="text-xs text-gray-500">Completed</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Activity -->
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                        <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs">
                                            {{ strtoupper(substr($project->owner->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-900">Project created by {{ $project->owner->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $project->created_at->format('M j, Y g:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Project Stats -->
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Total Tasks</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $totalTasks ?? 0 }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Completed</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $completedTasks ?? 0 }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">In Progress</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $inProgressTasks ?? 0 }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Team Members</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $teamMembers ?? 1 }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Team Members -->
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Team Members</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs">
                                            {{ strtoupper(substr($project->owner->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $project->owner->name }}</p>
                                            <p class="text-xs text-gray-500">Owner</p>
                                        </div>
                                    </div>
                                    @foreach($project->members as $member)
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center text-white text-xs">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($member->pivot->role) }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @if($project->owner_id == Auth::id())
                                    <a href="{{ route('projects.members', $project->id) }}" class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center inline-block">
                                        <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Invite Member
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
