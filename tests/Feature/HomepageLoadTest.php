<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomepageLoadTest extends TestCase
{
    /** @test */
    public function homepage_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('FIND Your');
        $response->assertSee('Dream Job');
    }

    /** @test */
    public function typesense_config_api_returns_valid_configuration(): void
    {
        $response = $this->getJson('/api/typesense/config');

        $response->assertStatus(200);

        $data = $response->json();
        $this->assertArrayHasKey('nodes', $data);
        $this->assertArrayHasKey('api_key', $data);
        $this->assertArrayHasKey('connectionTimeoutSeconds', $data);
        $this->assertIsArray($data['nodes']);
        $this->assertArrayHasKey('host', $data['nodes'][0]);
        $this->assertArrayHasKey('port', $data['nodes'][0]);
        $this->assertArrayHasKey('protocol', $data['nodes'][0]);
    }

    /** @test */
    public function homepage_includes_search_functionality(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('job-keyword');
        $response->assertSee('homepageSearch()');
        $response->assertSee('x-model="query"');
    }

    /** @test */
    public function api_suggestions_route_works_as_fallback(): void
    {
        $response = $this->getJson('/api/search/suggestions?q=test');

        $response->assertStatus(200);

        $data = $response->json();
        $this->assertArrayHasKey('success', $data);
        $this->assertArrayHasKey('data', $data);
    }
}
