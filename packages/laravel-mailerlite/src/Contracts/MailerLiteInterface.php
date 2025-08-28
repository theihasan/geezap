<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Contracts;

use Ihasan\LaravelMailerlite\Resources\Subscribers\SubscriberBuilder;

/**
 * Main MailerLite package interface.
 *
 * This interface defines the high-level contract for the MailerLite package,
 * providing access to all resource builders for fluent API usage.
 */
interface MailerLiteInterface
{
    /**
     * Get the subscriber builder instance for fluent API operations.
     *
     * @return SubscriberBuilder
     */
    public function subscribers(): SubscriberBuilder;

    /**
     * Get the campaign builder instance for fluent API operations.
     * 
     * @return mixed (to be implemented in future steps)
     */
    // public function campaigns(): CampaignBuilder;

    /**
     * Get the group builder instance for fluent API operations.
     * 
     * @return mixed (to be implemented in future steps) 
     */
    // public function groups(): GroupBuilder;

    /**
     * Get the field builder instance for fluent API operations.
     * 
     * @return mixed (to be implemented in future steps)
     */
    // public function fields(): FieldBuilder;

    /**
     * Get the segment builder instance for fluent API operations.
     * 
     * @return mixed (to be implemented in future steps)
     */
    // public function segments(): SegmentBuilder;

    /**
     * Get the webhook builder instance for fluent API operations.
     * 
     * @return mixed (to be implemented in future steps)
     */
    // public function webhooks(): WebhookBuilder;

    /**
     * Get the automation builder instance for fluent API operations.
     * 
     * @return mixed (to be implemented in future steps)
     */
    // public function automations(): AutomationBuilder;
}