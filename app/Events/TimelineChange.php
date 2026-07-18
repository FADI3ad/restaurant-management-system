<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TimelineChange implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    

    public function __construct()
    {

    }

    public function broadcastOn(): array
    {
        return [
            new Channel('timeline-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'timeline.change';
    }
}
