<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    use HasFactory;
    
    protected $fillable = ['project_id', 'user_id', 'role', 'joined_at'];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isLead()
    {
        return $this->role === 'lead';
    }

    public function isMember()
    {
        return $this->role === 'member';
    }
}
