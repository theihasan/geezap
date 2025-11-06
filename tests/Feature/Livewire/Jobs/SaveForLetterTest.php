<?php

namespace Tests\Feature\Livewire\Jobs;

use App\Livewire\Jobs\SaveForLetter;
use App\Models\JobCategory;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;


class SaveForLetterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function renders_successfully()
    {
        $jobCategory = JobCategory::factory()->create();
        $job = JobListing::factory()->create(['job_category' => $jobCategory->id]);

        Livewire::test(SaveForLetter::class, ['job' => $job])
            ->assertStatus(200);
    }
}
