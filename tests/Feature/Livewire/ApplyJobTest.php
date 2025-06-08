<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Enums\JobSavedStatus;
use App\Livewire\ApplyJob;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\CreatesJobListings;

class ApplyJobTest extends TestCase
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
        Livewire::test(ApplyJob::class, ['job' => $this->jobListing])
            ->assertStatus(200);
    }
    
    #[Test]
    public function unauthenticated_user_cannot_see_apply_status(): void
    {
        Livewire::test(ApplyJob::class, ['job' => $this->jobListing])
            ->assertSet('hasApplied', false);
    }
    
    #[Test]
    public function authenticated_user_can_apply_for_job(): void
    {
        Livewire::actingAs($this->user)
            ->test(ApplyJob::class, ['job' => $this->jobListing])
            ->call('apply')
            ->assertSet('hasApplied', false);
        
        // Verify the job was attached to the user
        $this->assertTrue(
            $this->user->jobs()
                ->where('job_user.job_id', $this->jobListing->id)
                ->where('job_user.status', JobSavedStatus::APPLIED->value)
                ->exists()
        );
    }
    
    #[Test]
    public function already_applied_status_is_detected_correctly(): void
    {
        // Attach the job to the user first
        $this->user->jobs()->attach($this->jobListing->id, ['status' => JobSavedStatus::APPLIED->value]);
        
        // Test that the component detects the already applied status
        Livewire::actingAs($this->user)
            ->test(ApplyJob::class, ['job' => $this->jobListing])
            ->assertSet('hasApplied', true);
    }
    
    #[Test]
    public function calling_already_applied_updates_status(): void
    {
        // Attach the job to the user first
        $this->user->jobs()->attach($this->jobListing->id, ['status' => JobSavedStatus::APPLIED->value]);
        
        // Test that calling alreadyApplied updates the status
        Livewire::actingAs($this->user)
            ->test(ApplyJob::class, ['job' => $this->jobListing])
            ->assertSet('hasApplied', true)
            ->call('alreadyApplied')
            ->assertSet('hasApplied', true);
    }
}
