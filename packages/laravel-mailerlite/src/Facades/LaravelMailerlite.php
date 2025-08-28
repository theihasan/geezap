<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Facades;

use Illuminate\Support\Facades\Facade;
use Ihasan\LaravelMailerlite\Resources\Subscribers\SubscriberBuilder;
use Ihasan\LaravelMailerlite\Resources\Campaigns\CampaignBuilder;
use Ihasan\LaravelMailerlite\Resources\Groups\GroupBuilder;

/**
 * MailerLite Facade
 *
 * Provides a fluent, plain-English API for interacting with MailerLite services.
 * This facade enables method chaining for readable and expressive code.
 *
 * @method static SubscriberBuilder subscribers()
 * @method static CampaignBuilder campaigns()
 * @method static GroupBuilder groups()
 *
 * @see \Ihasan\LaravelMailerlite\LaravelMailerlite
 */
class MailerLite extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Ihasan\LaravelMailerlite\LaravelMailerlite::class;
    }
}
