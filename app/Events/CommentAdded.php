<?php

namespace App\Events;

use App\Models\TaskComment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(TaskComment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('project.' . $this->comment->task->taskList->board->project_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'comment' => $this->comment->load('user'),
        ];
    }
}
