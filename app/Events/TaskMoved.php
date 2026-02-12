<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskMoved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;
    public $oldListId;

    public function __construct(Task $task, int $oldListId)
    {
        $this->task = $task;
        $this->oldListId = $oldListId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('project.' . $this->task->taskList->board->project_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'task' => $this->task->load(['assignee']),
            'newListId' => $this->task->task_list_id,
            'oldListId' => $this->oldListId,
        ];
    }
}
