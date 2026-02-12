<?php

namespace App\Policies;

use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TimeEntryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TimeEntry $timeEntry): bool
    {
        return $user->role?->slug === 'admin' 
            || $timeEntry->user_id === $user->id
            || $timeEntry->task->taskList->board->project->owner_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, TimeEntry $timeEntry): bool
    {
        return $user->role?->slug === 'admin' || $timeEntry->user_id === $user->id;
    }

    public function delete(User $user, TimeEntry $timeEntry): bool
    {
        return $user->role?->slug === 'admin' || $timeEntry->user_id === $user->id;
    }

    public function restore(User $user, TimeEntry $timeEntry): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function forceDelete(User $user, TimeEntry $timeEntry): bool
    {
        return $user->role?->slug === 'admin';
    }
}
