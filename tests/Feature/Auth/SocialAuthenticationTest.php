<?php

namespace Tests\Feature\Auth;

use App\Enums\SocialProvider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class SocialAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_social_redirect_works(): void
    {
        $response = $this->get(route('social.redirect', ['provider' => SocialProvider::GOOGLE->value]));

        // Should redirect to the social provider
        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->getTargetUrl());
    }

    public function test_social_callback_creates_new_user(): void
    {
        $mockUser = Mockery::mock(SocialiteUser::class);
        $mockUser->shouldReceive('getId')
            ->andReturn('123456789');
        $mockUser->shouldReceive('getEmail')
            ->andReturn('test@example.com');
        $mockUser->shouldReceive('getName')
            ->andReturn('Test User');
        $mockUser->shouldReceive('getNickname')
            ->andReturn('testuser');
        $mockUser->shouldReceive('getAvatar')
            ->andReturn('https://example.com/avatar.jpg');
        $mockUser->token = 'mock-token';
        $mockUser->user = [];

        $socialiteManager = Mockery::mock();
        $socialiteManager->shouldReceive('driver')
            ->with('google')
            ->andReturnSelf();
        $socialiteManager->shouldReceive('user')
            ->andReturn($mockUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($socialiteManager);

        $response = $this->get(route('social.callback', ['provider' => SocialProvider::GOOGLE->value]));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'google_id' => '123456789',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertAuthenticated();
        $response->assertRedirect(route('onboarding.welcome'));
    }

    public function test_social_callback_logs_in_existing_user(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'test@example.com',
            'google_id' => '123456789',
        ]);

        $mockUser = Mockery::mock(SocialiteUser::class);
        $mockUser->shouldReceive('getId')
            ->andReturn('123456789');
        $mockUser->shouldReceive('getEmail')
            ->andReturn('test@example.com');
        $mockUser->shouldReceive('getName')
            ->andReturn('Test User');
        $mockUser->shouldReceive('getNickname')
            ->andReturn('testuser');
        $mockUser->shouldReceive('getAvatar')
            ->andReturn('https://example.com/avatar.jpg');
        $mockUser->token = 'mock-token';
        $mockUser->user = [];

        $socialiteManager = Mockery::mock();
        $socialiteManager->shouldReceive('driver')
            ->with('google')
            ->andReturnSelf();
        $socialiteManager->shouldReceive('user')
            ->andReturn($mockUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($socialiteManager);

        $response = $this->get(route('social.callback', ['provider' => SocialProvider::GOOGLE->value]));

        $this->assertAuthenticated();
        $this->assertEquals($existingUser->id, auth()->id());
        $response->assertRedirect(route('dashboard'));
    }

    public function test_login_page_shows_social_login_buttons(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Continue with Google');
        $response->assertSee('Continue with GitHub');
    }

    public function test_register_page_shows_social_login_buttons(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Continue with Google');
        $response->assertSee('Continue with GitHub');
    }
}
