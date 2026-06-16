<?php

namespace App\Notifications;

use App\Models\TopUp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TopUpCreatedNotification extends Notification
{
    use Queueable;

    public TopUp $topup;

    /**
     * Create a new notification instance.
     */
    public function __construct(TopUp $topup)
    {
        $this->topup = $topup;
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
            'type' => 'topup_created',
            'topup_id' => $this->topup->id,
            'amount' => $this->topup->amount,
            'message' => "Top-up saldo berhasil sebesar Rp " . number_format($this->topup->amount, 0, ',', '.') . " melalui " . ucfirst($this->topup->method) . ".",
        ];
    }
}
