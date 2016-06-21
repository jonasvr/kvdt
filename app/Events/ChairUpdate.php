<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChairUpdate extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * ChairUpdate constructor.
     */
    public function __construct()
    {
        
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['chair-channel'];
    }
}
