<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Exceptions\AIServiceAPIKeyNotFound;
use App\Exceptions\IncompleteProfileException;
use App\Exceptions\NonAuthenticatedUser;
use App\Livewire\GenerateCoverLetter;
use App\Models\JobListing;
use App\Models\User;
use App\Services\AIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\CreatesJobListings;

class GenerateCoverLetterTest extends TestCase
{
    use RefreshDatabase, CreatesJobListings;
    
    protected JobListing $jobListing;
    protected User $user;
    protected User $incompleteUser;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->jobListing = $this->createJobListing();
        
        // Create a user with complete profile
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'skills' => json_encode(['skill' => ['PHP', 'Laravel'], 'skill_level' => ['Proficient', 'Proficient']]),
            'experience' => json_encode([[
                'title' => 'Developer',
                'company' => 'Test Company',
                'description' => 'Test description'
            ]])
        ]);
        
        // Create a user with incomplete profile
        $this->incompleteUser = User::factory()->create([
            'name' => null,
            'skills' => null,
            'experience' => null
        ]);
        
        // Set AI API key in config
        config(['ai.chat_gpt_api_key' => 'test-api-key']);
    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    #[Test]
    public function renders_successfully(): void
    {
        Livewire::test(GenerateCoverLetter::class, ['job' => $this->jobListing])
            ->assertStatus(200);
    }
    
    #[Test]
    public function unauthenticated_user_cannot_generate_cover_letter()
    {
        Livewire::test(GenerateCoverLetter::class, ['job' => $this->jobListing])
            ->call('startGeneration')
            ->assertDispatched('notify', function ($name, $data) {
                return $data[0]['type'] === 'error' && 
                       str_contains($data[0]['message'], 'login');
            });
    }
    
    #[Test]
    public function authenticated_user_with_complete_profile_can_generate_cover_letter()
    {
        // Mock AIService
        $aiServiceMock = Mockery::mock(AIService::class);
        $aiServiceMock->shouldReceive('getChatResponse')
            ->once()
            ->andReturnUsing(function ($user, $jobData, $callback, $previousAnswer = null, $feedbackText = null) {
                $callback('Generated cover letter content');
                return 'Complete generated cover letter';
            });
        
        $this->app->instance(AIService::class, $aiServiceMock);
        
        Livewire::actingAs($this->user)
            ->test(GenerateCoverLetter::class, ['job' => $this->jobListing])
            ->call('startGeneration')
            ->assertSet('isGenerating', false)
            ->assertSet('answer', 'Complete generated cover letter');
    }
    
    #[Test]
    public function regenerate_with_feedback_works_correctly()
    {
        // Mock AIService
        $aiServiceMock = Mockery::mock(AIService::class);
        $aiServiceMock->shouldReceive('getChatResponse')
            ->once()
            ->with(
                Mockery::any(), // user
                Mockery::any(), // jobData
                Mockery::any(), // callback
                Mockery::any(), // feedbackText
                Mockery::any()  // previousAnswer
            )
            ->andReturnUsing(function ($user, $jobData, $callback, $feedbackText = null, $previousAnswer = null) {
                $callback('Regenerated cover letter with feedback');
                return 'Complete regenerated cover letter';
            });
        
        $this->app->instance(AIService::class, $aiServiceMock);
        
        Livewire::actingAs($this->user)
            ->test(GenerateCoverLetter::class, ['job' => $this->jobListing])
            ->set('answer', 'Original answer')
            ->set('feedback', 'Make it more professional')
            ->call('regenerateWithFeedback')
            ->assertSet('isGenerating', false)
            ->assertSet('answer', 'Complete regenerated cover letter')
            ->assertSet('feedback', '');
    }
    
    #[Test]
    public function regenerate_with_empty_feedback_shows_error(): void
    {
        Livewire::actingAs($this->user)
            ->test(GenerateCoverLetter::class, ['job' => $this->jobListing])
            ->set('feedback', '')
            ->call('regenerateWithFeedback')
            ->assertDispatched('notify', function ($name, $data) {
                return $data[0]['type'] === 'error' && 
                       str_contains($data[0]['message'], 'provide feedback');
            });
    }
}
