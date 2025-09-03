<?php

namespace Tests\Unit\Config;

use Tests\TestCase;


class SeoConfigTest extends TestCase
{
    #[PHPUnit\Framework\Attributes\Test]
    public function seo_config_file_exists_and_is_loadable()
    {
        // Act
        $config = config('seo');

        // Assert
        $this->assertIsArray($config);
        $this->assertNotEmpty($config);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function seo_config_has_required_default_keys()
    {
        // Act
        $config = config('seo');

        // Assert
        $this->assertArrayHasKey('defaults', $config);
        $this->assertArrayHasKey('images', $config);
        $this->assertArrayHasKey('open_graph', $config);
        $this->assertArrayHasKey('twitter', $config);
        $this->assertArrayHasKey('structured_data', $config);
        $this->assertArrayHasKey('routes', $config);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function defaults_section_has_required_keys()
    {
        // Act
        $defaults = config('seo.defaults');

        // Assert
        $this->assertArrayHasKey('title', $defaults);
        $this->assertArrayHasKey('title_separator', $defaults);
        $this->assertArrayHasKey('description', $defaults);
        $this->assertArrayHasKey('keywords', $defaults);
        $this->assertArrayHasKey('author', $defaults);
        $this->assertArrayHasKey('robots', $defaults);
        $this->assertArrayHasKey('canonical_url', $defaults);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function defaults_have_reasonable_values()
    {
        // Act
        $defaults = config('seo.defaults');

        // Assert
        $this->assertNotEmpty($defaults['title']);
        $this->assertNotEmpty($defaults['description']);
        $this->assertNotEmpty($defaults['keywords']);
        $this->assertEquals(' | ', $defaults['title_separator']);
        $this->assertEquals('index,follow', $defaults['robots']);
        $this->assertNull($defaults['canonical_url']); // Should be null for dynamic generation
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function images_section_has_required_configuration()
    {
        // Act
        $images = config('seo.images');

        // Assert
        $this->assertArrayHasKey('default', $images);
        $this->assertArrayHasKey('fallback', $images);
        $this->assertArrayHasKey('width', $images);
        $this->assertArrayHasKey('height', $images);
        
        $this->assertIsInt($images['width']);
        $this->assertIsInt($images['height']);
        $this->assertGreaterThan(0, $images['width']);
        $this->assertGreaterThan(0, $images['height']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function open_graph_section_is_properly_configured()
    {
        // Act
        $og = config('seo.open_graph');

        // Assert
        $this->assertArrayHasKey('site_name', $og);
        $this->assertArrayHasKey('locale', $og);
        $this->assertArrayHasKey('type', $og);
        
        $this->assertNotEmpty($og['site_name']);
        $this->assertEquals('en_US', $og['locale']);
        $this->assertEquals('website', $og['type']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function twitter_section_is_properly_configured()
    {
        // Act
        $twitter = config('seo.twitter');

        // Assert
        $this->assertArrayHasKey('site', $twitter);
        $this->assertArrayHasKey('creator', $twitter);
        $this->assertArrayHasKey('card', $twitter);
        
        $this->assertEquals('summary_large_image', $twitter['card']);
        $this->assertStringStartsWith('@', $twitter['site']);
        $this->assertStringStartsWith('@', $twitter['creator']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function structured_data_section_has_organization_and_website()
    {
        // Act
        $structuredData = config('seo.structured_data');

        // Assert
        $this->assertArrayHasKey('organization', $structuredData);
        $this->assertArrayHasKey('website', $structuredData);
        
        // Test organization structured data
        $org = $structuredData['organization'];
        $this->assertEquals('https://schema.org', $org['@context']);
        $this->assertEquals('Organization', $org['@type']);
        $this->assertArrayHasKey('name', $org);
        $this->assertArrayHasKey('url', $org);
        $this->assertArrayHasKey('logo', $org);
        
        // Test website structured data
        $website = $structuredData['website'];
        $this->assertEquals('https://schema.org', $website['@context']);
        $this->assertEquals('WebSite', $website['@type']);
        $this->assertArrayHasKey('potentialAction', $website);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function routes_section_contains_expected_routes()
    {
        // Act
        $routes = config('seo.routes');

        // Assert
        $this->assertIsArray($routes);
        
        $expectedRoutes = [
            'dashboard',
            'about',
            'contact',
            'privacy-policy',
            'terms',
            'cover-letter.update',
            'applications',
            'profile.update',
            'profile.preferences'
        ];

        foreach ($expectedRoutes as $route) {
            $this->assertArrayHasKey($route, $routes, "Route '{$route}' should be configured");
        }
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function private_routes_have_noindex_robots()
    {
        // Act
        $routes = config('seo.routes');

        // Assert - Private routes should have noindex,nofollow
        $privateRoutes = ['dashboard', 'applications', 'profile.update', 'profile.preferences'];
        
        foreach ($privateRoutes as $route) {
            $this->assertEquals('noindex,nofollow', $routes[$route]['robots'], 
                "Route '{$route}' should have noindex,nofollow robots directive");
        }
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function public_routes_have_proper_seo_data()
    {
        // Act
        $routes = config('seo.routes');

        // Assert
        $publicRoutes = ['about', 'contact', 'privacy-policy', 'terms'];
        
        foreach ($publicRoutes as $route) {
            $this->assertArrayHasKey('title', $routes[$route], "Route '{$route}' should have title");
            $this->assertArrayHasKey('description', $routes[$route], "Route '{$route}' should have description");
            $this->assertArrayHasKey('keywords', $routes[$route], "Route '{$route}' should have keywords");
            
            $this->assertNotEmpty($routes[$route]['title']);
            $this->assertNotEmpty($routes[$route]['description']);
            $this->assertNotEmpty($routes[$route]['keywords']);
        }
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function config_values_are_reasonable_lengths()
    {
        // Act
        $config = config('seo');

        // Assert - Check description lengths are SEO-friendly
        $this->assertLessThanOrEqual(160, strlen($config['defaults']['description']), 
            'Default description should be under 160 characters');

        foreach ($config['routes'] as $routeName => $routeConfig) {
            if (isset($routeConfig['description'])) {
                $this->assertLessThanOrEqual(160, strlen($routeConfig['description']), 
                    "Description for route '{$routeName}' should be under 160 characters");
            }
        }
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function config_handles_environment_variables()
    {
        // Arrange - Set test environment variables
        config(['app.name' => 'Test App Name']);
        config(['app.url' => 'https://test.example.com']);

        // Act
        $config = config('seo');

        // Assert - Config should use environment values
        $this->assertEquals('Test App Name', $config['defaults']['title']);
        $this->assertEquals('Test App Name', $config['open_graph']['site_name']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function structured_data_has_valid_schema_format()
    {
        // Act
        $structuredData = config('seo.structured_data');

        // Assert - Organization schema
        $org = $structuredData['organization'];
        $this->assertStringStartsWith('https://schema.org', $org['@context']);
        $this->assertContains($org['@type'], ['Organization']);
        
        // Assert - Website schema
        $website = $structuredData['website'];
        $this->assertStringStartsWith('https://schema.org', $website['@context']);
        $this->assertContains($website['@type'], ['WebSite']);
        $this->assertArrayHasKey('potentialAction', $website);
        $this->assertEquals('SearchAction', $website['potentialAction']['@type']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function image_paths_are_valid_format()
    {
        // Act
        $images = config('seo.images');

        // Assert
        $this->assertStringStartsWith('/', $images['default']);
        $this->assertStringStartsWith('/', $images['fallback']);
        
        // Should be reasonable image dimensions for social media
        $this->assertGreaterThanOrEqual(600, $images['width']);
        $this->assertGreaterThanOrEqual(300, $images['height']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function twitter_handles_are_properly_formatted()
    {
        // Act
        $twitter = config('seo.twitter');

        // Assert
        if ($twitter['site']) {
            $this->assertStringStartsWith('@', $twitter['site']);
            $this->assertGreaterThan(1, strlen($twitter['site'])); // More than just @
        }
        
        if ($twitter['creator']) {
            $this->assertStringStartsWith('@', $twitter['creator']);
            $this->assertGreaterThan(1, strlen($twitter['creator'])); // More than just @
        }
        
        $validCardTypes = ['summary', 'summary_large_image', 'app', 'player'];
        $this->assertContains($twitter['card'], $validCardTypes);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function config_can_be_cached()
    {
        // Act - Attempt to cache config
        $this->artisan('config:cache');

        // Assert - Config should still be accessible
        $config = config('seo');
        $this->assertIsArray($config);
        $this->assertNotEmpty($config);

        // Cleanup
        $this->artisan('config:clear');
    }
}