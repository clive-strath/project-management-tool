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

    public function create(User $user, ?Task $task = null): bool
    {
        // If task context is provided, check project permissions
        if ($task) {
            $project = $task->taskList->board->project;
            return $user->role?->slug === 'admin' 
                || $project->owner_id === $user->id 
                || $project->isProjectLead($user->id);
        }
        
        // Otherwise, allow creation (will be verified at controller level)
        return true;
    }

    public function update(User $user, Task $task): bool
    {
        $project = $task->taskList->board->project;
        
        // Admins can always update
        if ($user->role?->slug === 'admin') {
            return true;
        }
        
        // Project owner/lead can update any task
        if ($project->owner_id === $user->id || $project->isProjectLead($user->id)) {
            return true;
        }
        
        // Regular members can only update their own assigned tasks (to mark as done)
        if ($task->assignee_id === $user->id) {
            return true;
        }
        
        return false;
    }

    public function delete(User $user, Task $task): bool
    {
        $project = $task->taskList->board->project;
        
        // Only admins, project owners, and project leads can delete tasks
        return $user->role?->slug === 'admin' 
            || $project->owner_id === $user->id 
            || $project->isProjectLead($user->id);
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
