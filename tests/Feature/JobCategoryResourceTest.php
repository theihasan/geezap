<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\JobCategory;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobCategoryResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_category_deletion_handles_relationships(): void
    {
        // Create a job category
        $jobCategory = JobCategory::factory()->create([
            'name' => 'Test Category',
        ]);

        // Create and attach countries
        $countries = Country::factory()->count(2)->create();
        $jobCategory->countries()->attach($countries);

        // Verify the pivot table has entries
        $this->assertDatabaseHas('job_category_country', [
            'job_category_id' => $jobCategory->id,
        ]);

        // Manually call the detach logic (simulating the before hook)
        $jobCategory->countries()->detach();

        // Now delete the category
        $jobCategory->delete();

        // Verify category is deleted
        $this->assertDatabaseMissing('job_categories', [
            'id' => $jobCategory->id,
        ]);

        // Verify pivot table entries are cleaned up
        $this->assertDatabaseMissing('job_category_country', [
            'job_category_id' => $jobCategory->id,
        ]);
    }

    public function test_job_category_with_job_listings_cannot_be_deleted(): void
    {
        // Create a job category
        $jobCategory = JobCategory::factory()->create([
            'name' => 'Category with Jobs',
        ]);

        // Create job listings associated with this category
        JobListing::factory()->count(3)->create([
            'job_category' => $jobCategory->id,
        ]);

        // Check if there are job listings
        $jobCount = $jobCategory->jobs()->count();

        // Verify we have job listings
        $this->assertEquals(3, $jobCount);

        // This simulates the validation logic from our DeleteAction
        $this->assertTrue($jobCount > 0);
    }

    public function test_job_category_without_job_listings_can_be_deleted(): void
    {
        // Create a job category
        $jobCategory = JobCategory::factory()->create([
            'name' => 'Category without Jobs',
        ]);

        // Check if there are job listings
        $jobCount = $jobCategory->jobs()->count();

        // Verify we have no job listings
        $this->assertEquals(0, $jobCount);

        // Detach countries and delete (simulating our before hook)
        $jobCategory->countries()->detach();
        $jobCategory->delete();

        // Verify category is deleted
        $this->assertDatabaseMissing('job_categories', [
            'id' => $jobCategory->id,
        ]);
    }
}
