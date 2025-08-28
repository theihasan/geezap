<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite;

use Ihasan\LaravelMailerlite\Contracts\MailerLiteInterface;
use Ihasan\LaravelMailerlite\Manager\MailerLiteManager;
use Ihasan\LaravelMailerlite\Resources\Subscribers\SubscriberBuilder;

/**
 * Laravel MailerLite Main Service Class
 *
 * This is the main entry point for the MailerLite package. It provides
 * factory methods for creating builders for different MailerLite resources.
 */
class LaravelMailerlite implements MailerLiteInterface
{
    /**
     * Create a new LaravelMailerlite instance.
     *
     * @param MailerLiteManager $manager
     */
    public function __construct(
        protected MailerLiteManager $manager
    ) {}

    /**
     * Get a subscriber builder instance.
     *
     * @return SubscriberBuilder
     */
    public function subscribers(): SubscriberBuilder
    {
        return app(SubscriberBuilder::class);
    }
}
