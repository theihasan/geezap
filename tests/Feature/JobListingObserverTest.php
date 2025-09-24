<?php

namespace Tests\Feature;

use App\Models\JobListing;
use App\Observers\JobListingObserver;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\TestCase;

class JobListingObserverTest extends TestCase
{
    public function test_observer_can_be_instantiated(): void
    {
        $observer = new JobListingObserver();
        $this->assertInstanceOf(JobListingObserver::class, $observer);
    }

    public function test_clear_cache_method_exists(): void
    {
        $observer = new JobListingObserver();
        $this->assertTrue(method_exists($observer, 'clearCache'));
    }

    public function test_observer_methods_exist(): void
    {
        $observer = new JobListingObserver();
        
        $this->assertTrue(method_exists($observer, 'creating'));
        $this->assertTrue(method_exists($observer, 'updating'));
        $this->assertTrue(method_exists($observer, 'created'));
        $this->assertTrue(method_exists($observer, 'updated'));
        $this->assertTrue(method_exists($observer, 'deleted'));
    }
}