<?php

namespace App\Events;

use App\Models\Parking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ParkingEntryEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Parking $parking;

    public function __construct(Parking $parking)
    {
        $this->parking = $parking->load('user');
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('parking-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'parking.entry';
    }

    public function broadcastWith(): array
    {
        return [
            'parking' => [
                'id' => $this->parking->id,
                'user_id' => $this->parking->user_id,
                'entry_time' => $this->parking->entry_time->format('Y-m-d H:i:s'),
                'exit_time' => null,
                'duration' => null,
                'cost' => null,
                'status' => 'IN',
                'user' => [
                    'id' => $this->parking->user->id,
                    'name' => $this->parking->user->name,
                    'rfid_uid' => $this->parking->user->rfid_uid,
                    'balance' => $this->parking->user->balance,
                ],
            ],
        ];
    }
}