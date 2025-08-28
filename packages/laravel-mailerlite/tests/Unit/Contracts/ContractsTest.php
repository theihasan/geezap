<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\Contracts\AutomationsInterface;
use Ihasan\LaravelMailerlite\Contracts\CampaignsInterface;
use Ihasan\LaravelMailerlite\Contracts\FieldsInterface;
use Ihasan\LaravelMailerlite\Contracts\GroupsInterface;
use Ihasan\LaravelMailerlite\Contracts\MailerLiteInterface;
use Ihasan\LaravelMailerlite\Contracts\SegmentsInterface;
use Ihasan\LaravelMailerlite\Contracts\SubscribersInterface;
use Ihasan\LaravelMailerlite\Contracts\WebhooksInterface;

describe('Contracts', function () {
    it('has all required contracts defined', function () {
        expect(interface_exists(MailerLiteInterface::class))->toBeTrue();
        expect(interface_exists(SubscribersInterface::class))->toBeTrue();
        expect(interface_exists(CampaignsInterface::class))->toBeTrue();
        expect(interface_exists(GroupsInterface::class))->toBeTrue();
        expect(interface_exists(FieldsInterface::class))->toBeTrue();
        expect(interface_exists(SegmentsInterface::class))->toBeTrue();
        expect(interface_exists(WebhooksInterface::class))->toBeTrue();
        expect(interface_exists(AutomationsInterface::class))->toBeTrue();
    });

    it('main interface defines all resource methods', function () {
        $reflection = new ReflectionClass(MailerLiteInterface::class);
        $methods = array_map(fn ($method) => $method->getName(), $reflection->getMethods());

        expect($methods)->toContain('subscribers');
        expect($methods)->toContain('campaigns');
        expect($methods)->toContain('groups');
        expect($methods)->toContain('fields');
        expect($methods)->toContain('segments');
        expect($methods)->toContain('webhooks');
        expect($methods)->toContain('automations');
    });

    it('subscribers interface has all required methods', function () {
        $reflection = new ReflectionClass(SubscribersInterface::class);
        $methods = array_map(fn ($method) => $method->getName(), $reflection->getMethods());

        expect($methods)->toContain('create');
        expect($methods)->toContain('getByEmail');
        expect($methods)->toContain('getById');
        expect($methods)->toContain('update');
        expect($methods)->toContain('delete');
        expect($methods)->toContain('list');
        expect($methods)->toContain('addToGroup');
        expect($methods)->toContain('removeFromGroup');
        expect($methods)->toContain('unsubscribe');
        expect($methods)->toContain('resubscribe');
    });

    it('campaigns interface has all required methods', function () {
        $reflection = new ReflectionClass(CampaignsInterface::class);
        $methods = array_map(fn ($method) => $method->getName(), $reflection->getMethods());

        expect($methods)->toContain('create');
        expect($methods)->toContain('getById');
        expect($methods)->toContain('update');
        expect($methods)->toContain('delete');
        expect($methods)->toContain('list');
        expect($methods)->toContain('schedule');
        expect($methods)->toContain('send');
        expect($methods)->toContain('cancel');
        expect($methods)->toContain('getStats');
        expect($methods)->toContain('getSubscribers');
    });

    it('groups interface has all required methods', function () {
        $reflection = new ReflectionClass(GroupsInterface::class);
        $methods = array_map(fn ($method) => $method->getName(), $reflection->getMethods());

        expect($methods)->toContain('create');
        expect($methods)->toContain('getById');
        expect($methods)->toContain('getByName');
        expect($methods)->toContain('update');
        expect($methods)->toContain('delete');
        expect($methods)->toContain('list');
        expect($methods)->toContain('getSubscribers');
        expect($methods)->toContain('assignSubscribers');
        expect($methods)->toContain('unassignSubscribers');
    });
});