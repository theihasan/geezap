<?php

namespace App\Jobs\Store;

use Ihasan\LaravelMailerlite\Exceptions\MailerLiteAuthenticationException;
use Ihasan\LaravelMailerlite\Exceptions\SubscriberCreateException;
use Ihasan\LaravelMailerlite\Facades\MailerLite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AddSubscriberToMailerLite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 4;

    public int $maxExceptions = 3;

    public int $timeout = 300;

    public array $backoff = [30, 45, 60];

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $email,
        public ?string $name = null,
        public ?array $fields = null,
        public ?array $groupIds = null,
        public bool $resubscribeIfExists = true
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Processing MailerLite subscriber sync', [
                'email' => $this->email,
                'name' => $this->name,
                'fields' => $this->fields,
                'groups' => $this->groupIds,
            ]);

            // Check if subscriber already exists
            $existingSubscriber = MailerLite::subscribers()
                ->email($this->email)
                ->find();

            if ($existingSubscriber) {
                $this->handleExistingSubscriber($existingSubscriber);
            } else {
                $this->createNewSubscriber();
            }

            Log::info('MailerLite subscriber sync completed successfully', [
                'email' => $this->email,
            ]);

        } catch (MailerLiteAuthenticationException $e) {
            Log::error('MailerLite authentication failed', [
                'email' => $this->email,
                'error' => $e->getMessage(),
            ]);

            $this->fail($e);

        } catch (SubscriberCreateException $e) {
            Log::error('Failed to create MailerLite subscriber', [
                'email' => $this->email,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            if ($e->getCode() === 422) {
                $this->fail($e);
            } else {
                throw $e; 
            }

        } catch (\Exception $e) {
            Log::error('Unexpected error in MailerLite subscriber sync', [
                'email' => $this->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle existing subscriber update
     */
    private function handleExistingSubscriber(array $existingSubscriber): void
    {
        $subscriberId = $existingSubscriber['id'];
        $currentStatus = $existingSubscriber['status'] ?? 'unsubscribed';

        Log::debug('Found existing MailerLite subscriber', [
            'email' => $this->email,
            'subscriber_id' => $subscriberId,
            'current_status' => $currentStatus,
        ]);

        // Update subscriber if needed
        $updateBuilder = MailerLite::subscribers();

        if ($this->name && $this->name !== ($existingSubscriber['name'] ?? '')) {
            $updateBuilder = $updateBuilder->named($this->name);
        }

        if ($this->fields) {
            foreach ($this->fields as $field => $value) {
                $updateBuilder = $updateBuilder->withField($field, $value);
            }
        }

        // Resubscribe if they're not active and we're configured to resubscribe
        if ($this->resubscribeIfExists && $currentStatus !== 'active') {
            $updateBuilder = $updateBuilder->active();
            Log::info('Reactivating existing MailerLite subscriber', [
                'email' => $this->email,
                'previous_status' => $currentStatus,
            ]);
        }

        // Update the subscriber
        $updated = $updateBuilder->updateById($subscriberId);

        // Add to groups if specified
        if ($this->groupIds) {
            $this->addSubscriberToGroups($this->email, $this->groupIds);
        } else {
            $this->addToDefaultGroups();
        }

        Log::info('Updated existing MailerLite subscriber', [
            'email' => $this->email,
            'subscriber_id' => $subscriberId,
            'updated' => (bool) $updated,
        ]);
    }

    /**
     * Create new subscriber
     */
    private function createNewSubscriber(): void
    {
        $builder = MailerLite::subscribers()
            ->email($this->email)
            ->active();

        if ($this->name) {
            $builder = $builder->named($this->name);
        }

        if ($this->fields) {
            foreach ($this->fields as $field => $value) {
                $builder = $builder->withField($field, $value);
            }
        }

        // Add source tracking
        $builder = $builder->withField('source', 'geezap_newsletter_signup')
            ->withField('signup_date', now()->toDateString())
            ->withField('signup_ip', request()->ip() ?? 'unknown');

        // Add to groups if specified
        if ($this->groupIds) {
            $builder = $builder->toGroups($this->groupIds);
        } else {
            // Add to default newsletter group if configured
            $defaultGroupId = config('mailerlite.newsletter_group_id');
            if ($defaultGroupId) {
                $builder = $builder->toGroup($defaultGroupId);
            }
        }

        $subscriber = $builder->subscribe();

        if ($subscriber) {
            Log::info('Created new MailerLite subscriber', [
                'email' => $this->email,
                'subscriber_id' => $subscriber['id'] ?? null,
                'name' => $this->name,
                'groups' => $this->groupIds ?: [$defaultGroupId ?? 'default'],
            ]);
        } else {
            throw new \RuntimeException('Failed to create MailerLite subscriber - no response received');
        }
    }

    /**
     * Add subscriber to specific groups
     */
    private function addSubscriberToGroups(string $email, array $groupIds): void
    {
        foreach ($groupIds as $groupId) {
            try {
                $result = MailerLite::subscribers()
                    ->email($email)
                    ->addToGroup($groupId);

                Log::debug('Added subscriber to MailerLite group', [
                    'email' => $email,
                    'group_id' => $groupId,
                    'success' => (bool) $result,
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to add subscriber to MailerLite group', [
                    'email' => $email,
                    'group_id' => $groupId,
                    'error' => $e->getMessage(),
                ]);
                // Continue with other groups even if one fails
            }
        }
    }

    /**
     * Add subscriber to default groups
     */
    private function addToDefaultGroups(): void
    {
        $defaultGroupId = config('mailerlite.newsletter_group_id') ?: config('mailerlite.default_group_id');

        if ($defaultGroupId) {
            $this->addSubscriberToGroups($this->email, [$defaultGroupId]);
        }
    }

    /**
     * Create job instance for simple email subscription
     */
    public static function forEmail(string $email): self
    {
        return new self($email);
    }

    /**
     * Create job instance with full subscriber data
     */
    public static function forSubscriber(string $email, ?string $name = null, ?array $fields = null): self
    {
        return new self($email, $name, $fields);
    }

    /**
     * Create job instance with group assignment
     */
    public static function forGroup(string $email, string|array $groupIds, ?string $name = null): self
    {
        $groups = is_array($groupIds) ? $groupIds : [$groupIds];

        return new self($email, $name, null, $groups);
    }

    /**
     * Create job instance that won't resubscribe existing subscribers
     */
    public static function withoutResubscribe(string $email, ?string $name = null): self
    {
        return new self($email, $name, null, null, false);
    }
}
