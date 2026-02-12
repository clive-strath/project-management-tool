<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Projects
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">All Projects</h1>
                        <a href="{{ route('projects.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            New Project
                        </a>
                    </div>

                    @if($projects->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($projects as $project)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $project->name }}</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    </div>
                                    <p class="text-gray-600 mb-4">{{ $project->description ?? 'No description available' }}</p>
                                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                        <span>Created {{ $project->created_at->diffForHumans() }}</span>
                                        <span>{{ $project->tasks_count ?? 0 }} tasks</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex -space-x-2">
                                            <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs font-medium border-2 border-white">
                                                {{ Auth::user()->name[0]|upper }}
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('board') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                Board
                                            </a>
                                            <a href="{{ route('projects.show', $project->id) }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No projects</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new project.</p>
                            <div class="mt-6">
                                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    New Project
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
