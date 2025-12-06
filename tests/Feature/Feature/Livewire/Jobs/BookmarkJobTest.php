<?php

use App\Enums\JobSavedStatus;
use App\Livewire\Jobs\BookmarkJob;
use App\Models\JobListing;
use App\Models\JobUser;
use App\Models\User;
use Livewire\Livewire;

test('bookmark job component renders successfully', function () {
    $job = JobListing::factory()->create();

    Livewire::test(BookmarkJob::class, ['jobId' => $job->id])
        ->assertStatus(200);
});

test('guest user cannot bookmark jobs', function () {
    $job = JobListing::factory()->create();

    Livewire::test(BookmarkJob::class, ['jobId' => $job->id])
        ->call('toggleBookmark')
        ->assertDispatched('notify', function ($event) {
            return $event['message'] === 'You need to login to bookmark jobs' && $event['type'] === 'error';
        });
});

test('authenticated user can bookmark a job', function () {
    $user = User::factory()->create();
    $job = JobListing::factory()->create();

    $this->actingAs($user);

    Livewire::test(BookmarkJob::class, ['jobId' => $job->id])
        ->assertSet('isBookmarked', false)
        ->call('toggleBookmark')
        ->assertSet('isBookmarked', true)
        ->assertDispatched('notify', function ($event) {
            return $event['message'] === 'Job bookmarked successfully' && $event['type'] === 'success';
        });

    // Verify database record was created
    expect(JobUser::where([
        'job_id' => $job->id,
        'user_id' => $user->id,
        'status' => JobSavedStatus::SAVED->value,
    ])->exists())->toBeTrue();
});

test('authenticated user can remove bookmark from a job', function () {
    $user = User::factory()->create();
    $job = JobListing::factory()->create();

    // Create existing bookmark
    JobUser::create([
        'job_id' => $job->id,
        'user_id' => $user->id,
        'status' => JobSavedStatus::SAVED->value,
    ]);

    $this->actingAs($user);

    Livewire::test(BookmarkJob::class, ['jobId' => $job->id])
        ->assertSet('isBookmarked', true)
        ->call('toggleBookmark')
        ->assertSet('isBookmarked', false)
        ->assertDispatched('notify', function ($event) {
            return $event['message'] === 'Job removed from bookmarks' && $event['type'] === 'success';
        });

    // Verify database record was deleted
    expect(JobUser::where([
        'job_id' => $job->id,
        'user_id' => $user->id,
        'status' => JobSavedStatus::SAVED->value,
    ])->exists())->toBeFalse();
});

test('bookmark status is correctly determined on component mount', function () {
    $user = User::factory()->create();
    $job = JobListing::factory()->create();

    $this->actingAs($user);

    // Test without existing bookmark
    Livewire::test(BookmarkJob::class, ['jobId' => $job->id])
        ->assertSet('isBookmarked', false);

    // Create bookmark and test again
    JobUser::create([
        'job_id' => $job->id,
        'user_id' => $user->id,
        'status' => JobSavedStatus::SAVED->value,
    ]);

    Livewire::test(BookmarkJob::class, ['jobId' => $job->id])
        ->assertSet('isBookmarked', true);
});
