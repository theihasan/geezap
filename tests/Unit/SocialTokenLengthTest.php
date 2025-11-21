<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialTokenLengthTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_long_google_token(): void
    {
        // Create a fake Google token that's longer than 255 characters to test the column length fix
        $longToken = 'ya29.FAKE_'.str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', 5).'_TEST_TOKEN_FOR_COLUMN_LENGTH_VERIFICATION';

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'google_token' => $longToken,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'google_token' => $longToken,
        ]);

        // Verify the token was stored correctly
        $retrievedUser = User::where('email', 'test@example.com')->first();
        $this->assertEquals($longToken, $retrievedUser->google_token);
    }

    public function test_can_store_long_github_token(): void
    {
        $longToken = 'ghp_'.str_repeat('abcdefghijklmnopqrstuvwxyz1234567890', 10); // Long GitHub token

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'github_token' => $longToken,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'github_token' => $longToken,
        ]);
    }

    public function test_can_store_long_facebook_token(): void
    {
        $longToken = 'EAA'.str_repeat('abcdefghijklmnopqrstuvwxyz1234567890', 15); // Long Facebook token

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'facebook_token' => $longToken,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'facebook_token' => $longToken,
        ]);
    }
}
