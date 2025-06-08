<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Enums\JobSavedStatus;
use App\Livewire\MyApplications;
use App\Models\JobListing;
use App\Models\JobUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\CreatesJobListings;

class MyApplicationsTest extends TestCase
{
    use RefreshDatabase, CreatesJobListings;
    
    protected User $user;
    protected array $savedJobs;
    protected array $appliedJobs;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        
        // Create saved jobs
        $this->savedJobs = [];
        for ($i = 0; $i < 3; $i++) {
            $job = $this->createJobListing();
            $this->user->jobs()->attach($job->id, ['status' => JobSavedStatus::SAVED->value]);
            $this->savedJobs[] = $job;
        }
        
        // Create applied jobs
        $this->appliedJobs = [];
        for ($i = 0; $i < 2; $i++) {
            $job = $this->createJobListing();
            $this->user->jobs()->attach($job->id, ['status' => JobSavedStatus::APPLIED->value]);
            $this->appliedJobs[] = $job;
        }
    }
    
    #[Test]
    public function renders_successfully(): void
    {
        Livewire::actingAs($this->user)
            ->test(MyApplications::class)
            ->assertStatus(200);
    }
    
    #[Test]
    public function shows_all_applications_by_default(): void
    {
        Livewire::actingAs($this->user)
            ->test(MyApplications::class)
            ->assertSet('activeTab', 'all')
            ->assertPropertyWired('applications')
            ->assertCount('applications', 5); // 3 saved + 2 applied
    }
    
    #[Test]
    public function tab_switching_resets_page()
    {
        // Create a user with multiple pages of applications
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Create enough job listings to have pagination
        $jobs = JobListing::factory()->count(15)->create();
        
        // Attach jobs to user with different statuses
        foreach ($jobs as $index => $job) {
            $status = $index < 8 ? JobSavedStatus::APPLIED->value : JobSavedStatus::SAVED->value;
            $user->jobs()->attach($job->id, ['status' => $status]);
        }
        
        // Test tab switching resets pagination
        Livewire::test(MyApplications::class)
            ->call('setTab', 'saved')
            ->assertSet('activeTab', 'saved');
    }
    
    #[Test]
    public function can_filter_by_saved_jobs(): void
    {
        Livewire::actingAs($this->user)
            ->test(MyApplications::class)
            ->call('setTab', JobSavedStatus::SAVED->value)
            ->assertSet('activeTab', JobSavedStatus::SAVED->value)
            ->assertCount('applications', 3);
    }
    
    #[Test]
    public function can_filter_by_applied_jobs(): void
    {
        Livewire::actingAs($this->user)
            ->test(MyApplications::class)
            ->call('setTab', JobSavedStatus::APPLIED->value)
            ->assertSet('activeTab', JobSavedStatus::APPLIED->value)
            ->assertCount('applications', 2);
    }
    
    #[Test]
    public function can_remove_saved_job(): void
    {
        $jobToRemove = $this->savedJobs[0];
        
        Livewire::actingAs($this->user)
            ->test(MyApplications::class)
            ->call('removeSavedJob', $jobToRemove->id)
            ->assertDispatched('notify', function ($name, $data) {
                return $data[0]['type'] === 'success' && 
                       str_contains($data[0]['message'], 'removed');
            });
        
        // Verify the job was removed
        $this->assertFalse(
            JobUser::where('job_id', $jobToRemove->id)
                ->where('user_id', $this->user->id)
                ->exists()
        );
    }
    
    #[Test]
    public function pagination_works_correctly(): void
    {
        // Create more jobs to test pagination
        for ($i = 0; $i < 10; $i++) {
            $job = $this->createJobListing();
            $this->user->jobs()->attach($job->id, ['status' => JobSavedStatus::SAVED->value]);
        }
        
        Livewire::actingAs($this->user)
            ->test(MyApplications::class)
            ->assertSet('isLoading', false)
            ->call('setPage', 2)
            ->assertSet('isLoading', false);
    }

}
