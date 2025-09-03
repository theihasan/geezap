<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SeoMetaService;
use Illuminate\Http\Request;

class BasicSeoTest extends TestCase
{
    /** @test */
    public function seo_service_can_be_instantiated()
    {
        // Arrange
        $request = new Request();
        
        // Act
        $service = new SeoMetaService($request);
        
        // Assert
        $this->assertInstanceOf(SeoMetaService::class, $service);
    }
    
    /** @test */
    public function basic_meta_generation_works()
    {
        // Arrange
        config([
            'seo.defaults.title' => 'Test App',
            'seo.defaults.description' => 'Test Description',
            'seo.defaults.keywords' => 'test, keywords',
        ]);
        
        $request = new Request();
        $service = new SeoMetaService($request);
        
        // Act
        $meta = $service->generateMeta('Test Title');
        
        // Assert
        $this->assertStringContainsString('Test Title', $meta->title);
    }
}