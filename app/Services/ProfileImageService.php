<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileImageService
{
    /**
     * Download and save profile image from social provider
     *
     * @param string $imageUrl The URL of the profile image
     * @param string $provider The social provider (google, github, facebook)
     * @param string|int $userId The user ID for unique filename
     * @return string|null The saved image path or null if failed
     */
    public function downloadAndSaveProfileImage(string $imageUrl, string $provider, string|int $userId): ?string
    {
        try {
            if (empty($imageUrl) || $imageUrl === 'null') {
                return null;
            }

            if ($provider === 'github') {
                $imageUrl = $this->getHighResGithubImage($imageUrl);
            }

            $response = Http::timeout(30)->get($imageUrl);

            if (!$response->successful()) {
                \Log::warning("Failed to download profile image from {$provider}", [
                    'url' => $imageUrl,
                    'status' => $response->status()
                ]);
                return null;
            }

            $imageContent = $response->body();
            
            if (empty($imageContent) || strlen($imageContent) < 100) {
                \Log::warning("Invalid image content from {$provider}", ['url' => $imageUrl]);
                return null;
            }

            $extension = $this->getImageExtension($response->header('Content-Type'), $imageUrl);
            
            $filename = $this->generateFilename($userId, $provider, $extension);
            
            $filePath = "profile-images/{$filename}";
            
            if (Storage::disk('public')->put($filePath, $imageContent)) {
                \Log::info("Successfully saved profile image", [
                    'provider' => $provider,
                    'user_id' => $userId,
                    'path' => $filePath
                ]);
                
                return $filePath;
            }

        } catch (\Exception $e) {
            \Log::error("Error downloading profile image from {$provider}", [
                'url' => $imageUrl,
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * Get higher resolution GitHub profile image
     *
     * @param string $imageUrl
     * @return string
     */
    private function getHighResGithubImage(string $imageUrl): string
    {
        $urlParts = parse_url($imageUrl);
        if (isset($urlParts['query'])) {
            parse_str($urlParts['query'], $queryParams);
            unset($queryParams['s']); // Remove size parameter
            $newQuery = http_build_query($queryParams);
            $imageUrl = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'] . 
                       ($newQuery ? '?' . $newQuery : '');
        }
        
        return $imageUrl;
    }

    /**
     * Determine file extension from content type or URL
     *
     * @param string|null $contentType
     * @param string $imageUrl
     * @return string
     */
    private function getImageExtension(?string $contentType, string $imageUrl): string
    {
        // Try to get extension from content type first
        if ($contentType) {
            $contentTypeMap = [
                'image/jpeg' => 'jpg',
                'image/jpg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
            ];
            
            if (isset($contentTypeMap[$contentType])) {
                return $contentTypeMap[$contentType];
            }
        }

        // Fallback to URL extension
        $pathInfo = pathinfo(parse_url($imageUrl, PHP_URL_PATH));
        $extension = $pathInfo['extension'] ?? 'jpg';

        // Ensure it's a valid image extension
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        return in_array(strtolower($extension), $validExtensions) ? strtolower($extension) : 'jpg';
    }

    /**
     * Generate unique filename for the profile image
     *
     * @param string|int $userId
     * @param string $provider
     * @param string $extension
     * @return string
     */
    private function generateFilename(string|int $userId, string $provider, string $extension): string
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);
        
        return "user_{$userId}_{$provider}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Delete old profile image if it exists
     *
     * @param string|null $oldImagePath
     * @return void
     */
    public function deleteOldProfileImage(?string $oldImagePath): void
    {
        if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
            Storage::disk('public')->delete($oldImagePath);
            \Log::info("Deleted old profile image", ['path' => $oldImagePath]);
        }
    }

    /**
     * Get full URL for profile image
     *
     * @param string|null $imagePath
     * @return string|null
     */
    public function getProfileImageUrl(?string $imagePath): ?string
    {
        if (!$imagePath) {
            return null;
        }

        return Storage::disk('public')->url($imagePath);
    }

    /**
     * Validate image URL before downloading
     *
     * @param string $imageUrl
     * @return bool
     */
    public function isValidImageUrl(string $imageUrl): bool
    {
        if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return false;
        }
        $trustedDomains = [
            'avatars.githubusercontent.com',
            'lh3.googleusercontent.com',
            'graph.facebook.com',
            'platform-lookaside.fbsbx.com'
        ];

        $host = parse_url($imageUrl, PHP_URL_HOST);
        return in_array($host, $trustedDomains);
    }
}