<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Console\Command;

class TestWelcomeNotification extends Command
{
    protected $signature = 'notification:test-welcome {email?}';
    protected $description = 'Test the welcome notification';

    public function handle()
    {
        $email = $this->argument('email');

        if ($email) {
            $user = User::where('email', $email)->first();
        } else {
            $user = User::first();
        }

        if (!$user) {
            $this->error('User not found!');
            return Command::FAILURE;
        }

        $this->info("Sending welcome notification to: {$user->email}");

        try {
            $user->notify(new WelcomeNotification());
            $this->info("Welcome notification sent successfully!");
        } catch (\Exception $e) {
            $this->error("Failed: " . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
