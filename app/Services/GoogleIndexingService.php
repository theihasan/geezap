<?php

namespace App\Services;

use Google\Client;
use Google\Service\Indexing;
use Google\Service\Indexing\UrlNotification;
use Illuminate\Support\Facades\Log;

class GoogleIndexingService
{
    private ?Client $client = null;
    private ?Indexing $indexingService = null;

    public function __construct()
    {
        $this->initializeClient();
    }

    private function initializeClient(): void
    {
        if (!config('services.google_indexing.enabled')) {
            return;
        }

        $keyPath = config('services.google_indexing.service_account_key_path');
        
        // Convert relative path to absolute path
        if ($keyPath && !str_starts_with($keyPath, '/')) {
            $keyPath = base_path($keyPath);
        }
        
        if (!$keyPath || !file_exists($keyPath)) {
            Log::warning('Google Indexing API: Service account key file not found', [
                'path' => $keyPath
            ]);
            return;
        }

        try {
            $this->client = new Client();
            $this->client->setAuthConfig($keyPath);
            $this->client->addScope('https://www.googleapis.com/auth/indexing');
            
            $this->indexingService = new Indexing($this->client);
        } catch (\Exception $e) {
            Log::error('Google Indexing API initialization failed', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function submitUrl(string $url, string $type = 'URL_UPDATED'): bool
    {
        if (!$this->isConfigured()) {
            Log::info('Google Indexing API not configured, skipping URL submission', [
                'url' => $url,
                'type' => $type
            ]);
            return false;
        }

        try {
            $notification = new UrlNotification();
            $notification->setUrl($url);
            $notification->setType($type);

            $response = $this->indexingService->urlNotifications->publish($notification);
            
            Log::info('URL submitted to Google Indexing API', [
                'url' => $url,
                'type' => $type,
                'response' => $response->toArray()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to submit URL to Google Indexing API', [
                'url' => $url,
                'type' => $type,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    public function updateUrl(string $url): bool
    {
        return $this->submitUrl($url, 'URL_UPDATED');
    }

    public function deleteUrl(string $url): bool
    {
        return $this->submitUrl($url, 'URL_DELETED');
    }

    public function getUrlStatus(string $url): ?array
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $response = $this->indexingService->urlNotifications->getMetadata([
                'url' => $url
            ]);

            return $response->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to get URL status from Google Indexing API', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    public function isConfigured(): bool
    {
        return $this->client !== null && $this->indexingService !== null;
    }

    public function batchSubmitUrls(array $urls, string $type = 'URL_UPDATED'): array
    {
        $results = [];
        
        foreach ($urls as $url) {
            $results[$url] = $this->submitUrl($url, $type);
        }

        return $results;
    }
}
