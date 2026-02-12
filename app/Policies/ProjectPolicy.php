<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): bool
    {
        return $user->role?->slug === 'admin' 
            || $project->owner_id === $user->id 
            || $project->members()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return in_array($user->role?->slug, ['admin', 'manager']);
    }

    public function update(User $user, Project $project): bool
    {
        return $user->role?->slug === 'admin' || $project->owner_id === $user->id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->role?->slug === 'admin' || $project->owner_id === $user->id;
    }

    public function restore(User $user, Project $project): bool
    {
        return $user->role?->slug === 'admin';
    }

    public function forceDelete(User $user, Project $project): bool
    {
        return $user->role?->slug === 'admin';
    }
}
