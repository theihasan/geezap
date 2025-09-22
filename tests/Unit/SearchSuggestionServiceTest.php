<?php

namespace Tests\Unit;

use App\Models\JobListing;
use App\Models\SearchAnalytics;
use App\Services\SearchSuggestionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchSuggestionServiceTest extends TestCase
{
    use RefreshDatabase;

    private SearchSuggestionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(SearchSuggestionService::class);
    }

    public function test_get_suggestions_returns_popular_when_no_query()
    {
        // Create some job listings
        JobListing::factory()->create(['job_title' => 'Frontend Developer']);
        JobListing::factory()->create(['job_title' => 'Backend Developer']);
        JobListing::factory()->create(['job_title' => 'Full Stack Developer']);

        $suggestions = $this->service->getSuggestions('', 5);

        $this->assertIsArray($suggestions);
        $this->assertGreaterThan(0, count($suggestions));
    }

    public function test_get_suggestions_returns_job_titles_for_query()
    {
        // Create job listings with specific titles
        JobListing::factory()->create(['job_title' => 'Senior React Developer']);
        JobListing::factory()->create(['job_title' => 'Junior React Developer']);
        JobListing::factory()->create(['job_title' => 'Vue Developer']);

        $suggestions = $this->service->getSuggestions('react', 5);

        $this->assertIsArray($suggestions);
        $this->assertGreaterThan(0, count($suggestions));

        // Should find React Developers
        $reactSuggestions = collect($suggestions)->filter(fn ($s) => str_contains($s['text'], 'React'));
        $this->assertGreaterThan(0, $reactSuggestions->count());
        $this->assertEquals('job_title', $reactSuggestions->first()['type']);
    }

    public function test_get_suggestions_returns_companies_for_query()
    {
        // Create job listings with company names
        JobListing::factory()->create(['employer_name' => 'Google']);
        JobListing::factory()->create(['employer_name' => 'Google']);
        JobListing::factory()->create(['employer_name' => 'Facebook']);

        $suggestions = $this->service->getSuggestions('google', 5);

        $this->assertIsArray($suggestions);

        // Should find Google
        $googleSuggestion = collect($suggestions)->first(fn ($s) => $s['text'] === 'Google');
        $this->assertNotNull($googleSuggestion);
        $this->assertEquals('company', $googleSuggestion['type']);
    }

    public function test_track_search_stores_analytics()
    {
        $data = [
            'query' => 'frontend developer',
            'results_count' => 25,
            'filters' => ['is_remote' => 1],
        ];

        $this->service->trackSearch($data);

        $this->assertDatabaseHas('search_analytics', [
            'query' => 'frontend developer',
            'results_count' => 25,
            'filters_applied' => json_encode(['is_remote' => 1]),
        ]);
    }

    public function test_get_search_stats_returns_correct_data()
    {
        // Create some analytics data
        SearchAnalytics::factory()->create(['query' => 'developer']);
        SearchAnalytics::factory()->create(['query' => 'developer']);
        SearchAnalytics::factory()->create(['query' => 'designer']);

        $stats = $this->service->getSearchStats();

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_searches', $stats);
        $this->assertArrayHasKey('unique_queries', $stats);
        $this->assertEquals(3, $stats['total_searches']);
        $this->assertEquals(2, $stats['unique_queries']);
    }
}
