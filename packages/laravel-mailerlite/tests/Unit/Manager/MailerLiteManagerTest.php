<?php

declare(strict_types=1);

use Ihasan\LaravelMailerlite\Exceptions\MailerLiteAuthenticationException;
use Ihasan\LaravelMailerlite\Manager\MailerLiteManager;
use MailerLite\MailerLite;

describe('MailerLiteManager', function () {
    it('throws exception when API key is missing', function () {
        expect(fn () => new MailerLiteManager(null))
            ->toThrow(MailerLiteAuthenticationException::class);
    });

    it('throws exception when API key is empty string', function () {
        expect(fn () => new MailerLiteManager(''))
            ->toThrow(MailerLiteAuthenticationException::class);
    });

    it('can be instantiated with valid API key', function () {
        $manager = new MailerLiteManager('test-api-key');
        
        expect($manager)->toBeInstanceOf(MailerLiteManager::class);
        expect($manager->getApiKey())->toBe('test-api-key');
        expect($manager->getOptions())->toBe([]);
    });

    it('can be instantiated with API key and options', function () {
        $options = ['timeout' => 60, 'base_url' => 'https://custom.api.com'];
        $manager = new MailerLiteManager('test-api-key', $options);
        
        expect($manager->getApiKey())->toBe('test-api-key');
        expect($manager->getOptions())->toBe($options);
    });

    it('can be created from config array', function () {
        $config = [
            'key' => 'config-api-key',
            'timeout' => 30,
            'url' => 'https://connect.mailerlite.com/api/'
        ];
        
        $manager = MailerLiteManager::fromConfig($config);
        
        expect($manager->getApiKey())->toBe('config-api-key');
        expect($manager->getOptions())->toBe([
            'timeout' => 30,
            'base_url' => 'https://connect.mailerlite.com/api/'
        ]);
    });

    it('throws exception when creating from config without API key', function () {
        $config = ['timeout' => 30];
        
        expect(fn () => MailerLiteManager::fromConfig($config))
            ->toThrow(MailerLiteAuthenticationException::class);
    });

    it('throws exception when creating from config with empty API key', function () {
        $config = ['key' => ''];
        
        expect(fn () => MailerLiteManager::fromConfig($config))
            ->toThrow(MailerLiteAuthenticationException::class);
    });

    it('returns same client instance on multiple calls', function () {
        // Skip this test if we don't have a real API key for testing
        if (empty(env('MAILERLITE_TEST_API_KEY'))) {
            $this->markTestSkipped('MAILERLITE_TEST_API_KEY environment variable not set');
        }

        $manager = new MailerLiteManager(env('MAILERLITE_TEST_API_KEY'));
        
        $client1 = $manager->getClient();
        $client2 = $manager->getClient();
        
        expect($client1)->toBe($client2);
        expect($client1)->toBeInstanceOf(MailerLite::class);
    })->skip(); // Skip by default to avoid API calls in regular tests
});