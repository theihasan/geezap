<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CoverLetterGenerated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user, public array $coverLetter, public int $jobId)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('cover-letter.'.$this->user->id)
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'response' => $this->coverLetter['response']
        ];
    }
}
