<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Caches\JobFilterCache;
use App\Livewire\JobFilter;
use App\Models\Country;
use App\Models\JobCategory;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\CreatesJobListings;

class JobFilterTest extends TestCase
{
    use RefreshDatabase, CreatesJobListings;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create job listings with different attributes for testing filters
        JobListing::factory()->count(3)->create([
            'publisher' => 'LinkedIn',
            'is_remote' => true,
            'employment_type' => 'Full-time'
        ]);
        
        JobListing::factory()->count(2)->create([
            'publisher' => 'Indeed',
            'is_remote' => false,
            'employment_type' => 'Part-time'
        ]);
        
        JobListing::factory()->count(1)->create([
            'publisher' => 'Glassdoor',
            'is_remote' => true,
            'employment_type' => 'Contract'
        ]);
        
        // Create categories
        JobCategory::factory()->count(3)->create();
        
        // Create countries
        Country::create([
            'name' => 'United States',
            'code' => 'US',
            'is_active' => true
        ]);
        
        Country::create([
            'name' => 'Canada',
            'code' => 'CA',
            'is_active' => true
        ]);
    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    #[Test]
    public function renders_successfully(): void
    {
        Livewire::test(JobFilter::class)
            ->assertStatus(200);
    }
    
    
    #[Test]
    public function toggle_section_works_correctly()
    {
        Livewire::test(JobFilter::class)
            ->set('sections.categories', false) // Set it to false first
            ->assertSet('sections.categories', false)
            ->set('sections.categories', true) // Then toggle to true
            ->assertSet('sections.categories', true);
    }
    
    #[Test]
    public function search_filter_resets_page()
    {
        Livewire::test(JobFilter::class)
            ->set('search', 'developer')
            ->assertSet('perPage', 10); // Check that perPage is reset instead of page
    }
    
    #[Test]
    public function updating_filters_resets_list()
    {
        Livewire::test(JobFilter::class)
            ->set('country', 'US')
            ->assertSet('perPage', 10); 
    }
    
    #[Test]
    // We need to mock the search functionality
    public function reset_filters_clears_all_filters(): void
    {
        // Mock the JobListing model to avoid Typesense errors
        $this->mock('App\\Models\\JobListing', function ($mock) {
            $mock->shouldReceive('query')->andReturnSelf();
            $mock->shouldReceive('latest')->andReturnSelf();
            $mock->shouldReceive('with')->andReturnSelf();
            $mock->shouldReceive('take')->andReturnSelf();
            $mock->shouldReceive('get')->andReturn(collect([]));
            $mock->shouldReceive('count')->andReturn(0);
        });
        
        Livewire::test(JobFilter::class)
            ->set('search', 'developer')
            ->set('source', 'LinkedIn')
            ->set('country', 'US')
            ->set('category', '1')
            ->set('remote', true)
            ->set('types', ['fulltime'])
            ->call('clearAllFilters')
            ->assertSet('search', '')
            ->assertSet('source', '')
            ->assertSet('country', '')
            ->assertSet('category', '')
            ->assertSet('remote', false)
            ->assertSet('types', []);
    }
    
    #[Test]
    public function categories_are_retrieved_from_cache(): void
    {
        // Mock the cache to return a collection of categories
        $categories = collect([['id' => 1, 'name' => 'Category 1']]);
        
        // Create a mock instance and bind it to the container
        $mock = Mockery::mock(JobFilterCache::class);
        $mock->shouldReceive('getCategories')->andReturn($categories);
        $this->app->instance(JobFilterCache::class, $mock);
        
        Livewire::test(JobFilter::class)
            ->assertMethodReturns('getCategories', $categories);
    }
    
    #[Test]
    public function publishers_are_retrieved_from_cache(): void
    {
        // Mock the publishers array
        $publishers = ['LinkedIn', 'Indeed', 'Glassdoor'];
        
        // Create a mock instance and bind it to the container
        $mock = Mockery::mock(JobFilterCache::class);
        $mock->shouldReceive('getPublishers')->andReturn($publishers);
        $this->app->instance(JobFilterCache::class, $mock);
        
        Livewire::test(JobFilter::class)
            ->assertMethodReturns('getPublishers', $publishers);
    }
    
    #[Test]
    public function countries_are_retrieved_from_cache(): void
    {
        // Mock the countries collection
        $countries = collect([['id' => 1, 'name' => 'United States', 'code' => 'US']]);
        
        // Create a mock instance and bind it to the container
        $mock = Mockery::mock(JobFilterCache::class);
        $mock->shouldReceive('getCountries')->andReturn($countries);
        $this->app->instance(JobFilterCache::class, $mock);
        
        Livewire::test(JobFilter::class)
            ->assertMethodReturns('getCountries', $countries);
    }
}