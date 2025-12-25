<?php

namespace Tests\Feature;

use Tests\TestCase;

class TypesenseConfigApiTest extends TestCase
{
    /** @test */
    public function it_returns_typesense_configuration(): void
    {
        $response = $this->getJson('/api/typesense/config');

        $response->assertSuccessful()
            ->assertJsonStructure([
                'nodes' => [
                    '*' => [
                        'host',
                        'port',
                        'protocol',
                    ],
                ],
                'api_key',
                'connectionTimeoutSeconds',
            ]);
    }

    /** @test */
    public function it_includes_correct_configuration_values(): void
    {
        $response = $this->getJson('/api/typesense/config');

        $data = $response->json();

        expect($data['nodes'])->toBeArray();
        expect($data['nodes'][0]['host'])->toBe(config('scout.typesense.client-settings.nodes.0.host'));
        expect($data['nodes'][0]['port'])->toBe(config('scout.typesense.client-settings.nodes.0.port'));
        expect($data['nodes'][0]['protocol'])->toBe(config('scout.typesense.client-settings.nodes.0.protocol'));
        expect($data['api_key'])->toBe(config('scout.typesense.client-settings.api_key'));
    }

    /** @test */
    public function it_does_not_expose_sensitive_configuration(): void
    {
        $response = $this->getJson('/api/typesense/config');

        $data = $response->json();

        // Ensure we don't expose sensitive config
        expect($data)->not->toHaveKey('nearest_node');
        expect($data)->not->toHaveKey('healthcheck_interval_seconds');
        expect($data)->not->toHaveKey('num_retries');
    }
}
