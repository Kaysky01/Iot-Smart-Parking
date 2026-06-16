<?php

namespace App\Notifications;

use App\Models\TopUpRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TopUpApprovedNotification extends Notification
{
    use Queueable;

    public TopUpRequest $topupRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(TopUpRequest $topupRequest)
    {
        $this->topupRequest = $topupRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'topup_approved',
            'topup_request_id' => $this->topupRequest->id,
            'amount' => $this->topupRequest->amount,
            'message' => "Permintaan top-up sebesar Rp " . number_format($this->topupRequest->amount, 0, ',', '.') . " telah disetujui. Saldo Anda telah bertambah.",
        ];
    }
}
