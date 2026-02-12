<?php

namespace App\Events;

use App\Models\Board;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BoardUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $board;

    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('project.' . $this->board->project_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'board' => $this->board->load('taskLists.tasks'),
        ];
    }
}
