<?php

namespace App\Events;

use App\Models\TopUpRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TopUpRejectedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TopUpRequest $topupRequest;

    public function __construct(TopUpRequest $topupRequest)
    {
        $this->topupRequest = $topupRequest->load(['user', 'approver']);
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('topup-requests-channel'),
            new PrivateChannel('App.Models.User.' . $this->topupRequest->user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'topup-request.rejected';
    }

    public function broadcastWith(): array
    {
        return [
            'topup_request' => [
                'id' => $this->topupRequest->id,
                'user_id' => $this->topupRequest->user_id,
                'amount' => $this->topupRequest->amount,
                'status' => 'rejected',
                'approved_by' => $this->topupRequest->approved_by,
                'approved_at' => $this->topupRequest->approved_at?->format('Y-m-d H:i:s'),
                'user' => [
                    'id' => $this->topupRequest->user->id,
                    'name' => $this->topupRequest->user->name,
                    'npm' => $this->topupRequest->user->npm,
                ],
            ],
        ];
    }
}
