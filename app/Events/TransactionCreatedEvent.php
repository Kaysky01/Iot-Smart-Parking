<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction->load(['user', 'parking']);
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('transaction-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'transaction.created';
    }

    public function broadcastWith(): array
    {
        return [
            'transaction' => [
                'id' => $this->transaction->id,
                'user_id' => $this->transaction->user_id,
                'parking_id' => $this->transaction->parking_id,
                'amount' => $this->transaction->amount,
                'remaining_balance' => $this->transaction->remaining_balance,
                'created_at' => $this->transaction->created_at->format('Y-m-d H:i:s'),
                'user' => [
                    'id' => $this->transaction->user->id,
                    'name' => $this->transaction->user->name,
                    'rfid_uid' => $this->transaction->user->rfid_uid,
                ],
                'parking' => [
                    'id' => $this->transaction->parking->id,
                    'entry_time' => $this->transaction->parking->entry_time->format('Y-m-d H:i:s'),
                    'exit_time' => $this->transaction->parking->exit_time?->format('Y-m-d H:i:s'),
                ],
            ],
        ];
    }
}
