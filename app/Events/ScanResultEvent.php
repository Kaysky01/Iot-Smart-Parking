<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScanResultEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $result;

    public function __construct(array $result)
    {
        $this->result = $result;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('gate-screen'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'scan.result';
    }

    public function broadcastWith(): array
    {
        return $this->result;
    }
}
