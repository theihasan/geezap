<?php

namespace Tests\Unit;

use App\Services\GoogleIndexingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;
use Tests\TestCase as BaseTestCase;

class GoogleIndexingServiceTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        config([
            'services.google_indexing.enabled' => true,
            'services.google_indexing.service_account_key_path' => storage_path('app/google/service-account.json')
        ]);
    }

    public function test_service_is_not_configured_when_disabled(): void
    {
        config(['services.google_indexing.enabled' => false]);
        
        $service = new GoogleIndexingService();
        
        $this->assertFalse($service->isConfigured());
    }

    public function test_service_is_not_configured_when_key_file_does_not_exist(): void
    {
        config(['services.google_indexing.service_account_key_path' => '/non/existent/path.json']);
        
        $service = new GoogleIndexingService();
        
        $this->assertFalse($service->isConfigured());
    }

    public function test_submitUrl_returns_false_when_not_configured(): void
    {
        config(['services.google_indexing.enabled' => false]);
        
        $service = new GoogleIndexingService();
        
        Log::shouldReceive('info')
            ->once()
            ->with('Google Indexing API not configured, skipping URL submission', [
                'url' => 'https://example.com/test',
                'type' => 'URL_UPDATED'
            ]);
        
        $result = $service->submitUrl('https://example.com/test');
        
        $this->assertFalse($result);
    }

    public function test_updateUrl_calls_submitUrl_with_URL_UPDATED_type(): void
    {
        $service = $this->createPartialMock(GoogleIndexingService::class, ['submitUrl']);
        
        $service->expects($this->once())
            ->method('submitUrl')
            ->with('https://example.com/test', 'URL_UPDATED')
            ->willReturn(true);
        
        $result = $service->updateUrl('https://example.com/test');
        
        $this->assertTrue($result);
    }

    public function test_deleteUrl_calls_submitUrl_with_URL_DELETED_type(): void
    {
        $service = $this->createPartialMock(GoogleIndexingService::class, ['submitUrl']);
        
        $service->expects($this->once())
            ->method('submitUrl')
            ->with('https://example.com/test', 'URL_DELETED')
            ->willReturn(true);
        
        $result = $service->deleteUrl('https://example.com/test');
        
        $this->assertTrue($result);
    }

    public function test_batchSubmitUrls_processes_multiple_URLs(): void
    {
        $service = $this->createPartialMock(GoogleIndexingService::class, ['submitUrl']);
        
        $urls = [
            'https://example.com/test1',
            'https://example.com/test2',
            'https://example.com/test3'
        ];
        
        $service->expects($this->exactly(3))
            ->method('submitUrl')
            ->willReturnOnConsecutiveCalls(true, false, true);
        
        $results = $service->batchSubmitUrls($urls);
        
        $expected = [
            'https://example.com/test1' => true,
            'https://example.com/test2' => false,
            'https://example.com/test3' => true
        ];
        
        $this->assertEquals($expected, $results);
    }

    public function test_getUrlStatus_returns_null_when_not_configured(): void
    {
        config(['services.google_indexing.enabled' => false]);
        
        $service = new GoogleIndexingService();
        
        $result = $service->getUrlStatus('https://example.com/test');
        
        $this->assertNull($result);
    }
}
