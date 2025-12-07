<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    private function createIncompleteUser(): User
    {
        return User::factory()->create([
            'onboarding_completed_at' => null,
            'profile_completion_score' => 0,
            'occupation' => null,
            'country' => null,
            'bio' => null,
            'phone' => null,
            'website' => null,
            'skills' => null,
        ]);
    }

    private function createCompleteUser(): User
    {
        return User::factory()->create([
            'onboarding_completed_at' => now(),
            'profile_completion_score' => 100,
            'occupation' => 'Software Engineer',
            'bio' => 'Experienced developer',
        ]);
    }

    public function test_welcome_page_displays_correctly(): void
    {
        $user = $this->createIncompleteUser();

        $response = $this->actingAs($user)->get('/onboarding/welcome');

        $response->assertSuccessful()
            ->assertSee('Welcome to Geezap')
            ->assertSee('Ready to find your next opportunity?')
            ->assertSee('Step 1 of 3');
    }

    public function test_essential_info_page_displays_correctly(): void
    {
        $user = $this->createIncompleteUser();

        $response = $this->actingAs($user)->get('/onboarding/essential-info');

        $response->assertSuccessful()
            ->assertSee('Essential Information')
            ->assertSee('Step 2 of 3')
            ->assertSee('What\'s your occupation?', false)
            ->assertSee('Tell us about your professional background');
    }

    public function test_preferences_page_displays_correctly(): void
    {
        $user = $this->createIncompleteUser();

        $response = $this->actingAs($user)->get('/onboarding/preferences');

        $response->assertSuccessful()
            ->assertSee('Your Preferences')
            ->assertSee('Step 3 of 3')
            ->assertSee('Email alerts')
            ->assertSee('Job alerts');
    }

    public function test_can_store_essential_info(): void
    {
        $user = $this->createIncompleteUser();
        
        // Create a country for the test
        $country = \App\Models\Country::factory()->create(['is_active' => true]);

        $response = $this->actingAs($user)->post('/onboarding/essential-info', [
            'occupation' => 'Software Engineer',
            'country_id' => $country->id,
            'bio' => 'I am a passionate developer with 5 years of experience.',
        ]);

        $response->assertRedirect('/onboarding/preferences');

        $user->refresh();
        $this->assertEquals('Software Engineer', $user->occupation);
        $this->assertEquals($country->name, $user->country);  // User stores country name, not ID
        $this->assertEquals('I am a passionate developer with 5 years of experience.', $user->bio);
        $this->assertTrue($user->profile_completion_score > 0);
    }

    public function test_can_store_preferences(): void
    {
        $user = $this->createIncompleteUser();

        $response = $this->actingAs($user)->post('/onboarding/preferences', [
            'email_notifications' => '1',
            'job_alerts' => '1',
            'newsletter' => '0',
            'marketing_emails' => '0',
        ]);

        $response->assertRedirect('/dashboard');

        $user->refresh();
        $this->assertNotNull($user->onboarding_completed_at);
        $this->assertEquals(100, $user->profile_completion_score);

        // Check user preferences were created
        $preferences = $user->userPreference;
        $this->assertTrue($preferences->email_notifications);
        $this->assertTrue($preferences->job_alerts);
        $this->assertFalse($preferences->newsletter);
        $this->assertFalse($preferences->marketing_emails);
    }

    public function test_can_skip_onboarding(): void
    {
        $user = $this->createIncompleteUser();

        $response = $this->actingAs($user)->post('/onboarding/skip');

        $response->assertRedirect('/dashboard');

        $user->refresh();
        $this->assertNotNull($user->onboarding_completed_at);
    }

    public function test_completed_user_redirected_from_onboarding_pages(): void
    {
        $user = $this->createCompleteUser();

        $pages = [
            '/onboarding/welcome',
            '/onboarding/essential-info',
            '/onboarding/preferences',
        ];

        foreach ($pages as $page) {
            $response = $this->actingAs($user)->get($page);
            $response->assertRedirect('/dashboard');
        }
    }

    public function test_essential_info_validation_errors(): void
    {
        $user = $this->createIncompleteUser();

        $response = $this->actingAs($user)->post('/onboarding/essential-info', [
            'occupation' => '', // Required
            'country_id' => 'invalid', // Must be numeric
            'bio' => str_repeat('a', 501), // Max 500 characters
        ]);

        $response->assertSessionHasErrors(['occupation', 'country_id', 'bio']);
    }

    public function test_preferences_creates_default_preferences_if_none_exist(): void
    {
        $user = $this->createIncompleteUser();

        // Ensure no preferences exist
        $this->assertNull($user->userPreference);

        $response = $this->actingAs($user)->post('/onboarding/preferences', [
            'email_notifications' => '1',
            'job_alerts' => '0',
        ]);

        $response->assertRedirect('/dashboard');

        $user->refresh();
        $preferences = $user->userPreference;
        $this->assertNotNull($preferences);
        $this->assertTrue($preferences->email_notifications);
        $this->assertFalse($preferences->job_alerts);
    }

    public function test_profile_completion_score_calculation(): void
    {
        $user = $this->createIncompleteUser();
        
        // Create a country for the test
        $country = \App\Models\Country::factory()->create(['is_active' => true]);

        // Test partial completion
        $this->actingAs($user)->post('/onboarding/essential-info', [
            'occupation' => 'Developer',
            'country_id' => $country->id,
            // No bio provided
        ]);

        $user->refresh();
        $this->assertTrue($user->profile_completion_score > 0);
        $this->assertTrue($user->profile_completion_score < 100);

        // Complete onboarding
        $this->actingAs($user)->post('/onboarding/preferences', [
            'email_notifications' => '1',
        ]);

        $user->refresh();
        $this->assertEquals(100, $user->profile_completion_score);
    }

    public function test_guest_cannot_access_onboarding_pages(): void
    {
        $pages = [
            '/onboarding/welcome',
            '/onboarding/essential-info',
            '/onboarding/preferences',
        ];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertRedirect('/login');
        }
    }

    public function test_guest_cannot_post_to_onboarding_endpoints(): void
    {
        $endpoints = [
            '/onboarding/essential-info',
            '/onboarding/preferences',
            '/onboarding/skip',
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->post($endpoint);
            $response->assertRedirect('/login');
        }
    }
}
