<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Project Members - {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Manage Members</h1>
                            <p class="text-gray-600">Add or remove members from {{ $project->name }}</p>
                        </div>
                        <a href="{{ route('projects.show', $project->id) }}" class="text-gray-600 hover:text-gray-800">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Project
                        </a>
                    </div>

                    <!-- Add Member Form -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Member</h3>
                        @if(session('success'))
                            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('projects.add-member', $project->id) }}" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                                    <select name="user_id" id="user_id" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        <option value="">Choose a user...</option>
                                        @foreach($availableUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                    <select name="role" id="role" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        <option value="member">Member</option>
                                        <option value="lead">Lead</option>
                                    </select>
                                    @error('role')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Member
                                </button>
                                @if($availableUsers->count() === 0)
                                    <p class="text-sm text-gray-500">All users are already members of this project.</p>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Current Members -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Members ({{ $members->count() }})</h3>
                        
                        @if($members->count() > 0)
                            <div class="space-y-3">
                                @foreach($members as $member)
                                    @php
                                        $isOwner = $project->owner_id == $member->id;
                                        $role = $isOwner ? 'owner' : ($member->pivot->role ?? 'member');
                                        $joinedAt = $isOwner ? $project->created_at : ($member->pivot->joined_at ?? now());
                                    @endphp
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                        <div class="flex items-center gap-4">
                                            <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $member->name }}</h4>
                                                <p class="text-sm text-gray-600">{{ $member->email }}</p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    @if($isOwner)
                                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800">
                                                            Owner
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $role === 'lead' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                            {{ ucfirst($role) }}
                                                        </span>
                                                    @endif
                                                    <span class="text-xs text-gray-500">
                                                        Joined {{ \Carbon\Carbon::parse($joinedAt)->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-2">
                                            @if(!$isOwner)
                                                <form method="POST" action="{{ route('projects.remove-member', [$project->id, $member->id]) }}" onsubmit="return confirm('Are you sure you want to remove {{ $member->name }} from this project?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium px-3 py-1 rounded hover:bg-red-50 transition-colors">
                                                        Remove
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-sm italic">Cannot remove owner</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No members yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Add team members to start collaborating.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
