@extends('layouts.app')

@section('content')
<div class="flex-1 px-4 sm:px-6 py-5 sm:py-6 space-y-6 overflow-y-auto">
    <h2 class="text-xl font-semibold text-white">Reports & Analytics</h2>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Project Progress</h3>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-slate-300">Mobile App</span>
                        <span class="text-slate-400">75%</span>
                    </div>
                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full w-3/4 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Team Performance</h3>
            <div class="text-center text-slate-400">
                <p>Analytics dashboard coming soon...</p>
            </div>
        </div>
    </div>
</div>
@endsection
