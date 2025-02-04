<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Log;

class MonitorProcessed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $monitorId;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($monitorId, $message)
    {
        $this->monitorId = $monitorId;
        $this->message = $message;
    }


    public function broadcastOn()
    {
        Log::info("$this->message");
        return new Channel('monitors');
    }

    public function broadcastAs(){
        return 'MonitorProcessed';
    }

}
