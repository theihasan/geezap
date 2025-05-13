<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateUserCountryFromCloudflare implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $userId,
        private readonly ?string $cfCountry
    ) {}

    public function handle(): void
    {
        if (empty($this->cfCountry)) {
            return;
        }

        $user = User::find($this->userId);
        
        if (!$user) {
            return;
        }

        if ($user->country === $this->cfCountry) {
            return;
        }

        $user->update([
            'country' => $this->cfCountry
        ]);
    }
}