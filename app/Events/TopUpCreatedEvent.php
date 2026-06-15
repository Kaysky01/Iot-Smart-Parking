<?php

namespace App\Events;

use App\Models\TopUp;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TopUpCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TopUp $topup;

    public function __construct(TopUp $topup)
    {
        $this->topup = $topup->load(['user', 'admin']);
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('topup-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'topup.created';
    }

    public function broadcastWith(): array
    {
        return [
            'topup' => [
                'id' => $this->topup->id,
                'user_id' => $this->topup->user_id,
                'admin_id' => $this->topup->admin_id,
                'amount' => $this->topup->amount,
                'balance_before' => $this->topup->balance_before,
                'balance_after' => $this->topup->balance_after,
                'method' => $this->topup->method,
                'notes' => $this->topup->notes,
                'created_at' => $this->topup->created_at->format('Y-m-d H:i:s'),
                'user' => [
                    'id' => $this->topup->user->id,
                    'name' => $this->topup->user->name,
                    'rfid_uid' => $this->topup->user->rfid_uid,
                ],
                'admin' => $this->topup->admin ? [
                    'id' => $this->topup->admin->id,
                    'name' => $this->topup->admin->name,
                ] : null,
            ],
        ];
    }
}
