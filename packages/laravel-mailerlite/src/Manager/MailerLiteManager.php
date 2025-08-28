<?php

declare(strict_types=1);

namespace Ihasan\LaravelMailerlite\Manager;

use Ihasan\LaravelMailerlite\Exceptions\MailerLiteAuthenticationException;
use MailerLite\MailerLite;

/**
 * MailerLite SDK Manager
 *
 * This class is responsible for initializing and managing the MailerLite SDK instance.
 * It handles API key validation and SDK instantiation.
 */
class MailerLiteManager
{
    /**
     * The MailerLite SDK instance.
     */
    protected ?MailerLite $client = null;

    /**
     * Create a new MailerLite manager instance.
     *
     * @param string|null $apiKey The API key for authentication
     * @param array $options Additional configuration options
     *
     * @throws MailerLiteAuthenticationException
     */
    public function __construct(
        protected ?string $apiKey = null,
        protected array $options = []
    ) {
        if (empty($this->apiKey)) {
            throw MailerLiteAuthenticationException::missingApiKey();
        }
    }

    /**
     * Get or create the MailerLite SDK client instance.
     *
     * @return MailerLite
     * @throws MailerLiteAuthenticationException
     */
    public function getClient(): MailerLite
    {
        if ($this->client === null) {
            $this->client = $this->createClient();
        }

        return $this->client;
    }

    /**
     * Create a new MailerLite SDK client instance.
     *
     * @return MailerLite
     * @throws MailerLiteAuthenticationException
     */
    protected function createClient(): MailerLite
    {
        try {
            $client = new MailerLite([
                'api_key' => $this->apiKey,
                ...$this->options
            ]);

            // Test the connection by making a simple API call
            $this->validateConnection($client);

            return $client;
        } catch (\Exception $e) {
            if ($e instanceof MailerLiteAuthenticationException) {
                throw $e;
            }

            // If it's an authentication-related error from the SDK
            if (str_contains($e->getMessage(), '401') || 
                str_contains($e->getMessage(), 'Unauthorized') ||
                str_contains($e->getMessage(), 'Invalid API key')) {
                throw MailerLiteAuthenticationException::invalidApiKey();
            }

            // Re-throw other exceptions as-is for now
            throw $e;
        }
    }

    /**
     * Validate the connection by making a test API call.
     *
     * @param MailerLite $client
     * @throws MailerLiteAuthenticationException
     */
    protected function validateConnection(MailerLite $client): void
    {
        try {
            // Make a simple API call to validate the connection
            // We'll use the timezones endpoint as it's lightweight
            $client->timezones->get();
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), '401') || 
                str_contains($e->getMessage(), 'Unauthorized') ||
                str_contains($e->getMessage(), 'Invalid API key')) {
                throw MailerLiteAuthenticationException::invalidApiKey();
            }

            // For other errors during validation, we'll let them pass
            // as they might be network issues or temporary problems
        }
    }

    /**
     * Get the configured API key.
     *
     * @return string|null
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * Get the configuration options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Create a manager instance from configuration.
     *
     * @param array $config
     * @return static
     * @throws MailerLiteAuthenticationException
     */
    public static function fromConfig(array $config): static
    {
        $apiKey = $config['key'] ?? null;
        
        if (empty($apiKey)) {
            throw MailerLiteAuthenticationException::missingApiKey();
        }

        $options = [];
        
        // Add timeout if configured
        if (isset($config['timeout'])) {
            $options['timeout'] = $config['timeout'];
        }

        // Add base URL if configured
        if (isset($config['url'])) {
            $options['base_url'] = $config['url'];
        }

        return new static($apiKey, $options);
    }
}