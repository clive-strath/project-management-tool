<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;
    protected $fillable = ['project_id', 'name', 'type', 'position'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function taskLists()
    {
        return $this->hasMany(TaskList::class)->orderBy('position');
    }
}
