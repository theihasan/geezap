<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetUserPreferences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:set-preferences 
                            {--force : Force update existing preferences}
                            {--user= : Set preferences for specific user ID}
                            {--dry-run : Show what would be done without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set default preferences for existing users who don\'t have preferences';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        $userId = $this->option('user');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('DRY RUN MODE - No changes will be made');
        }

        $query = User::query();
        
        if ($userId) {
            $query->where('id', $userId);
        }

        if (!$force) {
            $query->whereDoesntHave('preferences');
        }

        $users = $query->get();
        
        if ($users->isEmpty()) {
            $this->info('No users found that need preferences setup.');
            return 0;
        }

        $this->info("Found {$users->count()} users to process.");
        
        $progressBar = $this->output->createProgressBar($users->count());
        $progressBar->start();

        $created = 0;
        $updated = 0;
        $errors = 0;

        foreach ($users as $user) {
            try {
                $defaultPreferences = [
                    'user_id' => $user->id,
                    'email_frequency' => 'weekly',
                    'preferred_job_categories_id' => [],
                    'preferred_regions_id' => [],
                    'preferred_job_types' => [],
                    'preferred_experience_levels' => [],
                    'min_salary' => null,
                    'max_salary' => null,
                    'remote_only' => false,
                    'email_notifications_enabled' => true,
                    'show_recommendations' => true,
                    'last_recommendation_update' => now(),
                ];

                if (!$dryRun) {
                    if ($force && $user->preferences) {
                        $user->preferences->update($defaultPreferences);
                        $updated++;
                    } else {
                        UserPreference::create($defaultPreferences);
                        $created++;
                    }
                } else {
                    if ($force && $user->preferences) {
                        $this->line("\nWould update preferences for user: {$user->email}");
                        $updated++;
                    } else {
                        $this->line("\nWould create preferences for user: {$user->email}");
                        $created++;
                    }
                }
            } catch (\Exception $e) {
                $this->error("\nError processing user {$user->email}: " . $e->getMessage());
                $errors++;
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info('Summary:');
        $this->line("Created: {$created}");
        $this->line("Updated: {$updated}");
        
        if ($errors > 0) {
            $this->error("Errors: {$errors}");
        }

        if ($dryRun) {
            $this->info('\nThis was a dry run. Use --force to actually make changes.');
        } else {
            $this->info('\nPreferences setup completed successfully!');
        }

        return 0;
    }
}