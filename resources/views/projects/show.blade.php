@extends('layouts.app')

@section('content')
<div class="flex-1 px-4 sm:px-6 py-5 sm:py-6 space-y-6 overflow-y-auto">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('projects.index') }}" class="text-slate-400 hover:text-white">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="text-xl font-semibold text-white">{{ $project['name'] }}</h2>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-2 py-1 text-xs font-medium text-emerald-400">
                {{ ucfirst($project['status'] ?? 'Active') }}
            </span>
            <a href="{{ route('board') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                View Board
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Project Details -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Project Details</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-slate-300 mb-2">Description</h4>
                        <p class="text-slate-400">{{ $project['description'] ?? 'No description provided' }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-slate-300 mb-2">Created</h4>
                            <p class="text-slate-400">{{ \Carbon\Carbon::parse($project['created_at'])->format('M j, Y') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-slate-300 mb-2">Last Updated</h4>
                            <p class="text-slate-400">{{ \Carbon\Carbon::parse($project['updated_at'])->format('M j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Recent Activity</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-800/50">
                        <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs">
                            U
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-white">Project created</p>
                            <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($project['created_at'])->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Project Stats -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Statistics</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-400">Total Tasks</span>
                        <span class="text-sm font-medium text-white">0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-400">Completed</span>
                        <span class="text-sm font-medium text-white">0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-400">In Progress</span>
                        <span class="text-sm font-medium text-white">0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-400">Team Members</span>
                        <span class="text-sm font-medium text-white">1</span>
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Team Members</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400">Owner</p>
                        </div>
                    </div>
                </div>
                <button class="mt-4 w-full bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Invite Member
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
