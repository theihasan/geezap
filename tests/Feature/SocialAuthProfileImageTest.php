<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\ProfileImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class SocialAuthProfileImageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_github_social_login_downloads_and_saves_profile_image(): void
    {
        // Mock a valid image response
        Http::fake([
            'https://avatars.githubusercontent.com/*' => Http::response(
                file_get_contents(__DIR__ . '/../../public/assets/images/profile.jpg'),
                200,
                ['Content-Type' => 'image/jpeg']
            ),
        ]);

        // Mock Socialite response
        $socialiteUser = $this->createMock(SocialiteUser::class);
        $socialiteUser->method('getId')->willReturn('12345');
        $socialiteUser->method('getEmail')->willReturn('test@example.com');
        $socialiteUser->method('getName')->willReturn('Test User');
        $socialiteUser->method('getNickname')->willReturn('testuser');
        $socialiteUser->method('getAvatar')->willReturn('https://avatars.githubusercontent.com/u/12345?v=4&s=40');
        $socialiteUser->token = 'mock_token';
        $socialiteUser->user = ['bio' => 'Test bio'];

        Socialite::shouldReceive('driver')->with('github')->andReturnSelf();
        Socialite::shouldReceive('user')->andReturn($socialiteUser);

        // Make the callback request
        $response = $this->get('/auth/github/callback');

        // Assert user was created and authenticated
        $response->assertRedirect('/dashboard');
        
        // Check that user was created with profile image
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->profile_image);
        $this->assertStringContainsString('profile-images/', $user->profile_image);
        
        // Verify the image file was saved
        Storage::disk('public')->assertExists($user->profile_image);
    }

    public function test_google_social_login_downloads_and_saves_profile_image(): void
    {
        // Mock a valid image response
        Http::fake([
            'https://lh3.googleusercontent.com/*' => Http::response(
                file_get_contents(__DIR__ . '/../../public/assets/images/profile.jpg'),
                200,
                ['Content-Type' => 'image/jpeg']
            ),
        ]);

        // Mock Socialite response
        $socialiteUser = $this->createMock(SocialiteUser::class);
        $socialiteUser->method('getId')->willReturn('67890');
        $socialiteUser->method('getEmail')->willReturn('google@example.com');
        $socialiteUser->method('getName')->willReturn('Google User');
        $socialiteUser->method('getNickname')->willReturn('googleuser');
        $socialiteUser->method('getAvatar')->willReturn('https://lh3.googleusercontent.com/a/photo?s=96-c');
        $socialiteUser->token = 'mock_google_token';
        $socialiteUser->user = ['bio' => 'Google bio'];

        Socialite::shouldReceive('driver')->with('google')->andReturnSelf();
        Socialite::shouldReceive('user')->andReturn($socialiteUser);

        // Make the callback request
        $response = $this->get('/auth/google/callback');

        // Assert user was created and authenticated
        $response->assertRedirect('/dashboard');
        
        // Check that user was created with profile image
        $user = User::where('email', 'google@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->profile_image);
        $this->assertStringContainsString('profile-images/', $user->profile_image);
        
        // Verify the image file was saved
        Storage::disk('public')->assertExists($user->profile_image);
    }

    public function test_existing_user_gets_profile_image_if_none_exists(): void
    {
        // Create existing user without profile image
        $user = User::factory()->create([
            'email' => 'existing@example.com',
            'profile_image' => null,
        ]);

        // Mock a valid image response
        Http::fake([
            'https://avatars.githubusercontent.com/*' => Http::response(
                file_get_contents(__DIR__ . '/../../public/assets/images/profile.jpg'),
                200,
                ['Content-Type' => 'image/jpeg']
            ),
        ]);

        // Mock Socialite response
        $socialiteUser = $this->createMock(SocialiteUser::class);
        $socialiteUser->method('getId')->willReturn('existing123');
        $socialiteUser->method('getEmail')->willReturn('existing@example.com');
        $socialiteUser->method('getName')->willReturn('Existing User');
        $socialiteUser->method('getNickname')->willReturn('existing');
        $socialiteUser->method('getAvatar')->willReturn('https://avatars.githubusercontent.com/u/existing123?v=4');
        $socialiteUser->token = 'mock_token';
        $socialiteUser->user = ['bio' => 'Existing bio'];

        Socialite::shouldReceive('driver')->with('github')->andReturnSelf();
        Socialite::shouldReceive('user')->andReturn($socialiteUser);

        // Make the callback request
        $response = $this->get('/auth/github/callback');

        // Assert user was authenticated and profile image was added
        $response->assertRedirect('/dashboard');
        
        $user->refresh();
        $this->assertNotNull($user->profile_image);
        $this->assertStringContainsString('profile-images/', $user->profile_image);
        
        // Verify the image file was saved
        Storage::disk('public')->assertExists($user->profile_image);
    }

    public function test_existing_user_keeps_existing_profile_image(): void
    {
        // Create existing user with profile image
        $user = User::factory()->create([
            'email' => 'hasimage@example.com',
            'profile_image' => 'profile-images/existing_image.jpg',
        ]);

        // Create the existing image file
        Storage::disk('public')->put('profile-images/existing_image.jpg', 'existing image content');

        // Mock Socialite response
        $socialiteUser = $this->createMock(SocialiteUser::class);
        $socialiteUser->method('getId')->willReturn('hasimage123');
        $socialiteUser->method('getEmail')->willReturn('hasimage@example.com');
        $socialiteUser->method('getName')->willReturn('Has Image User');
        $socialiteUser->method('getNickname')->willReturn('hasimage');
        $socialiteUser->method('getAvatar')->willReturn('https://avatars.githubusercontent.com/u/hasimage123?v=4');
        $socialiteUser->token = 'mock_token';
        $socialiteUser->user = ['bio' => 'Has image bio'];

        Socialite::shouldReceive('driver')->with('github')->andReturnSelf();
        Socialite::shouldReceive('user')->andReturn($socialiteUser);

        // Make the callback request
        $response = $this->get('/auth/github/callback');

        // Assert user was authenticated and kept existing profile image
        $response->assertRedirect('/dashboard');
        
        $user->refresh();
        $this->assertEquals('profile-images/existing_image.jpg', $user->profile_image);
        
        // Verify the existing image file still exists
        Storage::disk('public')->assertExists('profile-images/existing_image.jpg');
    }

    public function test_profile_image_service_methods(): void
    {
        $service = new ProfileImageService();
        
        // Test invalid image URL validation
        $this->assertFalse($service->isValidImageUrl('not-a-url'));
        $this->assertFalse($service->isValidImageUrl('https://untrusted.com/image.jpg'));
        $this->assertTrue($service->isValidImageUrl('https://avatars.githubusercontent.com/u/123?v=4'));
        $this->assertTrue($service->isValidImageUrl('https://lh3.googleusercontent.com/photo'));
        
        // Test profile image URL generation
        $user = User::factory()->make(['profile_image' => 'profile-images/test.jpg']);
        $this->assertStringContainsString('/storage/profile-images/test.jpg', $user->profile_image_url);
        
        $userWithoutImage = User::factory()->make(['profile_image' => null]);
        $this->assertNull($userWithoutImage->profile_image_url);
        
        // Test default profile image
        $this->assertStringContainsString('assets/images/profile.jpg', $userWithoutImage->profile_image_or_default);
    }
}