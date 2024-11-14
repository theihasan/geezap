<?php

namespace App\Exceptions;

use App\Models\ApiKey;
use Exception;

class ApiKeyNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('No available API key found with remaining requests');
    }

    /**
     * @throws ApiKeyNotFoundException
     */
    public static function validateApiKey(ApiKey|null $apiKey): void
    {
        if (!$apiKey || $apiKey->request_remaining <= 0) {
            throw new self();
        }
    }
}
