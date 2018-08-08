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

class CronJobError
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $name;
    public $return;
    public $runtime;
    public $rundate;
    public $tasks_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($name, $return, $runtime, $rundate)
    {
        $this->name = $name;
        $this->return = $return;
        $this->runtime = $runtime;
        $this->rundate = $rundate;
        $this->tasks_id = Task::where('name', '=', $name)->first()->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
