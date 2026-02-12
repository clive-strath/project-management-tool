<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Task $task): bool
    {
        $project = $task->taskList->board->project;
        return $user->role?->slug === 'admin' 
            || $project->owner_id === $user->id 
            || $project->members()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return true; // Verified at project/board level
    }

    public function update(User $user, Task $task): bool
    {
        $project = $task->taskList->board->project;
        return $user->role?->slug === 'admin' 
            || $project->owner_id === $user->id 
            || $task->assignee_id === $user->id
            || $project->members()->where('user_id', $user->id)->where('role', 'manager')->exists();
    }

    public function delete(User $user, Task $task): bool
    {
        $project = $task->taskList->board->project;
        return $user->role?->slug === 'admin' 
            || $project->owner_id === $user->id 
            || $project->members()->where('user_id', $user->id)->where('role', 'manager')->exists();
    }

    public function restore(User $user, Task $task): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return $user->role?->slug === 'admin';
    }
}
