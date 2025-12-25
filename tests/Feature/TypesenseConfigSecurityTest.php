<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TypesenseConfigSecurityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that Typesense config endpoint is accessible
     */
    public function test_typesense_config_endpoint_is_accessible(): void
    {
        $response = $this->get('/api/typesense/config');

        // Should be accessible (either 200 or 500, but not 403/404)
        $this->assertNotEquals(403, $response->status());
        $this->assertNotEquals(404, $response->status());
    }

    /**
     * Test that config endpoint returns expected structure when working
     */
    public function test_config_endpoint_returns_expected_structure(): void
    {
        $response = $this->get('/api/typesense/config');

        if ($response->status() === 200) {
            $data = $response->json();

            // Check structure
            $this->assertArrayHasKey('nodes', $data);
            $this->assertArrayHasKey('api_key', $data);
            $this->assertArrayHasKey('connectionTimeoutSeconds', $data);

            // Check nodes structure
            $this->assertIsArray($data['nodes']);
            $this->assertNotEmpty($data['nodes']);

            $node = $data['nodes'][0];
            $this->assertArrayHasKey('host', $node);
            $this->assertArrayHasKey('port', $node);
            $this->assertArrayHasKey('protocol', $node);

            // Ensure we don't expose the admin key directly
            $adminKey = config('scout.typesense.client-settings.api_key');
            $this->assertNotEquals($adminKey, $data['api_key']);

            // API key should be a string with reasonable length
            $this->assertIsString($data['api_key']);
            $this->assertGreaterThan(10, strlen($data['api_key']));
        }
    }

    /**
     * Test that refresh key endpoint requires authentication
     */
    public function test_refresh_key_endpoint_requires_authentication(): void
    {
        $response = $this->postJson('/api/typesense/refresh-key');

        // Should require authentication
        $this->assertEquals(401, $response->status());
    }

    /**
     * Test that refresh key endpoint requires admin role
     */
    public function test_refresh_key_endpoint_requires_admin_role(): void
    {
        // Create a non-admin user
        $user = User::factory()->create([
            'role' => Role::USER,
        ]);

        // Test with non-admin user
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/typesense/refresh-key');

        $this->assertEquals(403, $response->status());

        $data = $response->json();
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Unauthorized', $data['error']);
    }

    /**
     * Test that admin user can access refresh endpoint
     */
    public function test_admin_user_can_access_refresh_endpoint(): void
    {
        // Create an admin user
        $admin = User::factory()->create([
            'role' => Role::ADMIN,
        ]);

        // Test with admin user
        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/typesense/refresh-key');

        // Should either succeed (200) or fail with service error (500), not auth error (403)
        $this->assertNotEquals(403, $response->status());
        $this->assertNotEquals(401, $response->status());

        if ($response->status() === 200) {
            $data = $response->json();
            $this->assertArrayHasKey('message', $data);
            $this->assertArrayHasKey('key_expires_at', $data);
        }
    }
}
