<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Jobs;

use App\Enums\JobSavedStatus;
use App\Livewire\Jobs\SaveForLetter;
use App\Models\JobListing;
use App\Models\JobUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\CreatesJobListings;

class SaveForLetterTest extends TestCase
{
    use RefreshDatabase, CreatesJobListings;
    
    protected JobListing $jobListing;
    protected User $user;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->jobListing = $this->createJobListing();
        $this->user = User::factory()->create();
    }
    
    #[Test]
    public function renders_successfully(): void
    {
        Livewire::test(SaveForLetter::class, ['job' => $this->jobListing])
            ->assertStatus(200);
    }
    
    #[Test]
    public function unauthenticated_user_cannot_save_job(): void
    {
        Livewire::test(SaveForLetter::class, ['job' => $this->jobListing])
            ->call('saveForLetter')
            ->assertDispatched('notify', function ($name, $data) {
                return $data[0]['type'] === 'error' && 
                       str_contains($data[0]['message'], 'login');
            });
        
        // Verify no job was saved
        $this->assertDatabaseCount('job_user', 0);
    }
    
    #[Test]
    public function authenticated_user_can_save_job(): void
    {
        Livewire::actingAs($this->user)
            ->test(SaveForLetter::class, ['job' => $this->jobListing])
            ->call('saveForLetter')
            ->assertDispatched('notify', function ($name, $data) {
                return $data[0]['type'] === 'success' && 
                       str_contains($data[0]['message'], 'Saved');
            });
        
        // Verify the job was saved
        $this->assertTrue(
            JobUser::where('job_id', $this->jobListing->id)
                ->where('user_id', $this->user->id)
                ->where('status', JobSavedStatus::SAVED->value)
                ->exists()
        );
    }
    
    #[Test]
    public function saving_already_saved_job_updates_existing_record()
    {
        // Create a user and job listing
        $this->actingAs($this->user);
        
        // First, create an initial record
        $this->user->jobs()->attach($this->jobListing->id, ['status' => JobSavedStatus::APPLIED->value]);
        
        // Then test the component
        Livewire::test(SaveForLetter::class, ['job' => $this->jobListing])
            ->call('saveForLetter')
            ->assertDispatched('notify', function ($name, $data) {
                return $data[0]['type'] === 'success';
            });
        
        $this->assertDatabaseHas('job_user', [
            'job_id' => $this->jobListing->id,
            'user_id' => $this->user->id,
            'status' => JobSavedStatus::SAVED->value
        ]);
    }
    
    #[Test]
    public function component_mounts_job_correctly(): void
    {
        $component = Livewire::test(SaveForLetter::class, ['job' => $this->jobListing])
            ->instance();
        
        $this->assertEquals($this->jobListing->id, $component->job->id);
    }
}
