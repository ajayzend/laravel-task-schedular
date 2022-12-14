<?php

namespace App\Events;

use App\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskExecutedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;
    public $elapsed_time;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Task $task, $elapsed_time)
    {
        $this->task = $task;
        $this->elapsed_time = $elapsed_time;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
