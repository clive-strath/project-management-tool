<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'owner_id', 'status', 'due_date'];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function boards()
    {
        return $this->hasMany(Board::class);
    }

    public function projectMembers()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function isProjectLead($userId)
    {
        return $this->members()
            ->where('user_id', $userId)
            ->where('role', 'lead')
            ->exists();
    }

    public function isProjectMember($userId)
    {
        return $this->members()
            ->where('user_id', $userId)
            ->exists();
    }

    public function getUserRole($userId)
    {
        $member = $this->members()
            ->where('user_id', $userId)
            ->first();
        
        return $member ? $member->pivot->role : null;
    }

    public function addMember($userId, $role = 'member')
    {
        if (!$this->isProjectMember($userId)) {
            $this->members()->attach($userId, ['role' => $role]);
        }
    }

    public function removeMember($userId)
    {
        $this->members()->detach($userId);
    }
}
