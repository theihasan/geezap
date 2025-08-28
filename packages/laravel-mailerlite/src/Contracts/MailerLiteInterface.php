<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Contracts;

/**
 * Main MailerLite package interface.
 *
 * This interface defines the high-level contract for the MailerLite package,
 * providing access to all resource-specific services.
 */
interface MailerLiteInterface
{
    /**
     * Get the subscriber service instance.
     *
     * @return SubscribersInterface
     */
    public function subscribers(): SubscribersInterface;

    /**
     * Get the campaign service instance.
     *
     * @return CampaignsInterface
     */
    public function campaigns(): CampaignsInterface;

    /**
     * Get the group service instance.
     *
     * @return GroupsInterface
     */
    public function groups(): GroupsInterface;

    /**
     * Get the field service instance.
     *
     * @return FieldsInterface
     */
    public function fields(): FieldsInterface;

    /**
     * Get the segment service instance.
     *
     * @return SegmentsInterface
     */
    public function segments(): SegmentsInterface;

    /**
     * Get the webhook service instance.
     *
     * @return WebhooksInterface
     */
    public function webhooks(): WebhooksInterface;

    /**
     * Get the automation service instance.
     *
     * @return AutomationsInterface
     */
    public function automations(): AutomationsInterface;
}