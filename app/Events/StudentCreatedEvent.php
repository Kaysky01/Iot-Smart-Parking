<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $student;

    public function __construct(User $student)
    {
        $this->student = $student;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('student-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'student.created';
    }

    public function broadcastWith(): array
    {
        return [
            'student' => [
                'id' => $this->student->id,
                'name' => $this->student->name,
                'npm' => $this->student->npm,
                'rfid_uid' => $this->student->rfid_uid,
                'plate_number' => $this->student->plate_number,
                'vehicle_type' => $this->student->vehicle_type,
                'balance' => $this->student->balance,
                'created_at' => $this->student->created_at->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
