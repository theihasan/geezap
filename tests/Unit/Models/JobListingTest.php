<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\JobApplyOption;
use App\Models\JobCategory;
use App\Models\JobListing;
use App\Models\User;
use App\Observers\JobListingObserver;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JobListingTest extends TestCase
{
    use RefreshDatabase;
    
    protected JobListing $jobListing;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->jobListing = JobListing::factory()->create();
    }
    
    #[Test]
    public function it_has_fillable_attributes(): void
    {
        $expectedFillable = [
            'employer_name',
            'employer_logo',
            'employer_website',
            'employer_company_type',
            'publisher',
            'employment_type',
            'job_title',
            'job_category',
            'category_image',
            'apply_link',
            'description',
            'is_remote',
            'city',
            'state',
            'country',
            'google_link',
            'posted_at',
            'expired_at',
            'min_salary',
            'max_salary',
            'salary_currency',
            'salary_period',
            'benefits',
            'qualifications',
            'responsibilities',
            'required_experience',
        ];
        
        $this->assertEquals($expectedFillable, $this->jobListing->getFillable());
    }
    
    #[Test]
    public function it_casts_attributes_correctly(): void
    {
        $expectedCasts = [
            'posted_at' => 'datetime',
            'expired_at' => 'datetime',
            'required_experience' => 'integer',
            'qualifications' => 'array',
            'benefits' => 'array',
            'responsibilities' => 'array',
            'skills' => 'array',
            'is_remote' => 'boolean',
        ];
        
        $this->assertEquals($expectedCasts, $this->jobListing->getCasts());
    }
    
    #[Test]
    public function it_belongs_to_many_users(): void
    {
        $user = User::factory()->create();
        $this->jobListing->users()->attach($user->id);
        
        $this->assertInstanceOf(Collection::class, $this->jobListing->users);
        $this->assertInstanceOf(User::class, $this->jobListing->users->first());
        $this->assertTrue($this->jobListing->users->contains($user));
    }
    
    #[Test]
    public function it_belongs_to_a_category(): void
    {
        $category = JobCategory::factory()->create();
        $jobListing = JobListing::factory()->create(['job_category' => $category->id]);
        
        $this->assertInstanceOf(JobCategory::class, $jobListing->category);
        $this->assertEquals($category->id, $jobListing->category->id);
    }
    
    #[Test]
    public function it_has_many_apply_options(): void
    {
        JobApplyOption::factory()->count(3)->create([
            'job_listing_id' => $this->jobListing->id
        ]);
        
        $this->assertInstanceOf(Collection::class, $this->jobListing->applyOptions);
        $this->assertInstanceOf(JobApplyOption::class, $this->jobListing->applyOptions->first());
        $this->assertCount(3, $this->jobListing->applyOptions);
    }
    
    #[Test]
    public function it_has_a_scope_to_filter_by_publisher(): void
    {
        $publisher = 'LinkedIn';
        JobListing::factory()->count(3)->create(['publisher' => $publisher]);
        JobListing::factory()->count(2)->create(['publisher' => 'Indeed']);
        
        $filteredJobs = JobListing::byPublisher($publisher)->get();
        
        $this->assertCount(3, $filteredJobs);
        $filteredJobs->each(function ($job) use ($publisher) {
            $this->assertEquals($publisher, $job->publisher);
        });
    }
    
    #[Test]
    public function it_is_prunable_after_fourteen_days(): void
    {
        $oldJob = JobListing::factory()->create([
            'created_at' => now()->subDays(15)
        ]);
        
        $recentJob = JobListing::factory()->create([
            'created_at' => now()->subDays(7)
        ]);
        
        $prunableJobs = $this->jobListing->prunable()->get();
        
        $this->assertTrue($prunableJobs->contains($oldJob));
        $this->assertFalse($prunableJobs->contains($recentJob));
    }
    
    #[Test]
    public function it_logs_before_pruning(): void
    {
        $logSpy = $this->spy('Illuminate\Support\Facades\Log');
        
        $this->invokeMethod($this->jobListing, 'pruning');
        
        $logSpy->shouldHaveReceived('info')
            ->with('Prepare for removing job: ' . $this->jobListing->id)
            ->once();
    }
    
    #[Test]
    public function it_is_searchable_with_correct_array(): void
    {
        $searchableArray = $this->jobListing->toSearchableArray();
        
        $this->assertIsString($searchableArray['id']);
        $this->assertIsInt($searchableArray['created_at']);
        $this->assertIsString($searchableArray['job_category']);
        $this->assertIsBool($searchableArray['is_remote']);
        $this->assertIsString($searchableArray['publisher']);
        $this->assertIsInt($searchableArray['salary_min']);
        $this->assertIsInt($searchableArray['salary_max']);
        $this->assertIsString($searchableArray['salary_currency']);
        $this->assertIsString($searchableArray['salary_period']);
    }
    
    #[Test]
    public function it_has_correct_searchable_index_name(): void
    {
        $this->assertEquals('listing_index', $this->jobListing->searchableAs());
    }
    
    #[Test]
    public function it_generates_uuid_and_slug_when_creating(): void
    {
        $observer = new JobListingObserver();
        
        $jobListing = new JobListing([
            'job_title' => 'Software Engineer'
        ]);
        
        $observer->creating($jobListing);
        
        $this->assertNotNull($jobListing->uuid);
        $this->assertTrue(Str::isUuid($jobListing->uuid));
        $this->assertNotNull($jobListing->slug);
        $this->assertStringContainsString('software-engineer', $jobListing->slug);
    }
    
    #[Test]
    public function it_clears_cache_when_job_listing_is_created(): void
    {
        // Mock the Cache facade
        $cacheSpy = $this->spy('Illuminate\Support\Facades\Cache');
        
        $observer = new JobListingObserver();
        
        $observer->created($this->jobListing);
        
        // Assert that the cache was cleared
        $cacheSpy->shouldHaveReceived('forget')->with('jobCategoriesJobsCount');
        $cacheSpy->shouldHaveReceived('forget')->with('jobCategoriesAll');
    }
    
    #[Test]
    public function it_handles_array_attributes_correctly(): void
    {
        $benefits = ['Health Insurance', 'Dental Insurance', 'Vision Insurance'];
        $qualifications = ['Bachelor\'s Degree', '2+ years experience'];
        $responsibilities = ['Team management', 'Project planning'];
        $skills = ['PHP', 'Laravel', 'JavaScript'];
        
        $jobListing = JobListing::factory()->create([
            'benefits' => $benefits,
            'qualifications' => $qualifications,
            'responsibilities' => $responsibilities,
            'skills' => $skills,
        ]);
        
        $jobListing->refresh();
        
        $this->assertEquals($benefits, $jobListing->benefits);
        $this->assertEquals($qualifications, $jobListing->qualifications);
        $this->assertEquals($responsibilities, $jobListing->responsibilities);
        $this->assertEquals($skills, $jobListing->skills);
    }
    
    /**
     * Call protected/private method of a class.
     *
     * @param object $object     Object instance
     * @param string $methodName Method name to call
     * @param array  $parameters Parameters to pass into method
     *
     * @return mixed Method return
     */
    protected function invokeMethod(object $object, string $methodName, array $parameters = []): mixed
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        
        return $method->invokeArgs($object, $parameters);
    }
}