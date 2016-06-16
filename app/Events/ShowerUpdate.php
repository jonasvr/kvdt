<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Showers;

class ShowerUpdate extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * @var Showers
     */
    public $showers;

    /**
     * ShowerUpdate constructor.
     * @param Showers $showers
     */
    public function __construct(Showers $showers)
    {
        $this->showers = $showers;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['shower-channel'];
    }
}
