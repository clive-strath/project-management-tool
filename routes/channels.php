<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('project.{projectId}', function ($user, $projectId) {
    $project = \App\Models\Project::find($projectId);
    
    if (!$project) {
        return false;
    }

    return $user->role?->slug === 'admin' 
        || $project->owner_id === $user->id 
        || $project->members()->where('user_id', $user->id)->exists();
});
