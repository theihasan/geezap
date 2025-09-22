<?php

namespace App\Services;

use App\Models\JobListing;
use App\Models\SearchAnalytics;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SearchSuggestionService
{
    /**
     * Get dynamic search suggestions based on query
     */
    public function getSuggestions(string $query = '', int $limit = 10): array
    {
        $query = trim($query);

        if (empty($query)) {
            return $this->getPopularSuggestions($limit);
        }

        return Cache::remember(
            'search_suggestions_'.md5($query)."_{$limit}",
            300, // 5 minutes
            fn () => $this->generateSuggestions($query, $limit)
        );
    }

    /**
     * Get popular suggestions when no query is provided
     */
    protected function getPopularSuggestions(int $limit = 10): array
    {
        return Cache::remember("popular_suggestions_{$limit}", 1800, function () use ($limit) {
            $suggestions = [];

            // Get trending searches from analytics
            $trending = SearchAnalytics::trending(24, 48)->limit($limit)->get();
            foreach ($trending as $item) {
                $suggestions[] = [
                    'text' => $item->query,
                    'type' => 'trending',
                    'icon' => 'las la-fire',
                    'count' => $item->recent_count,
                    'badge' => '🔥 Trending',
                ];
            }

            // Fill with popular job titles if needed
            if (count($suggestions) < $limit) {
                $needed = $limit - count($suggestions);
                $popularTitles = $this->getPopularJobTitles($needed);

                foreach ($popularTitles as $title) {
                    $suggestions[] = [
                        'text' => $title['title'],
                        'type' => 'popular',
                        'icon' => 'las la-briefcase',
                        'count' => $title['count'],
                        'badge' => 'Popular',
                    ];
                }
            }

            return array_slice($suggestions, 0, $limit);
        });
    }

    /**
     * Generate suggestions based on user query
     */
    protected function generateSuggestions(string $query, int $limit): array
    {
        $suggestions = [];

        // 1. Exact job title matches
        $jobTitles = $this->searchJobTitles($query, min($limit, 3));
        foreach ($jobTitles as $title) {
            $suggestions[] = [
                'text' => $title['title'],
                'type' => 'job_title',
                'icon' => 'las la-briefcase',
                'count' => $title['count'],
                'category' => $title['category'] ?? null,
            ];
        }

        // 2. Company name matches
        if (count($suggestions) < $limit) {
            $companies = $this->searchCompanies($query, min($limit - count($suggestions), 3));
            foreach ($companies as $company) {
                $suggestions[] = [
                    'text' => $company['name'],
                    'type' => 'company',
                    'icon' => 'las la-building',
                    'count' => $company['count'],
                ];
            }
        }

        // 3. Skill/tag matches
        if (count($suggestions) < $limit) {
            $skills = $this->searchSkills($query, min($limit - count($suggestions), 2));
            foreach ($skills as $skill) {
                $suggestions[] = [
                    'text' => $skill['skill'],
                    'type' => 'skill',
                    'icon' => 'las la-code',
                    'count' => $skill['count'],
                ];
            }
        }

        // 4. Similar searches from analytics
        if (count($suggestions) < $limit) {
            $similarSearches = $this->getSimilarSearches($query, $limit - count($suggestions));
            foreach ($similarSearches as $search) {
                $suggestions[] = [
                    'text' => $search['query'],
                    'type' => 'similar',
                    'icon' => 'las la-search',
                    'count' => $search['count'],
                ];
            }
        }

        return $suggestions;
    }

    /**
     * Search for job titles
     */
    protected function searchJobTitles(string $query, int $limit): array
    {
        return JobListing::select('job_title', DB::raw('COUNT(*) as count'))
            ->selectRaw('job_categories.name as category_name')
            ->where('job_title', 'LIKE', "%{$query}%")
            ->join('job_categories', 'job_listings.job_category', '=', 'job_categories.id')
            ->groupBy('job_title', 'job_categories.name')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'title' => $item->job_title,
                    'count' => $item->count,
                    'category' => $item->category_name,
                ];
            })
            ->toArray();
    }

    /**
     * Search for companies
     */
    protected function searchCompanies(string $query, int $limit): array
    {
        return JobListing::select('employer_name', DB::raw('COUNT(*) as count'))
            ->where('employer_name', 'LIKE', "%{$query}%")
            ->whereNotNull('employer_name')
            ->groupBy('employer_name')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->employer_name,
                    'count' => $item->count,
                ];
            })
            ->toArray();
    }

    /**
     * Search for skills (from job descriptions and requirements)
     */
    protected function searchSkills(string $query, int $limit): array
    {
        // Common tech skills to search for
        $skills = [
            'React', 'Vue', 'Angular', 'JavaScript', 'TypeScript', 'Node.js',
            'Python', 'Django', 'Flask', 'PHP', 'Laravel', 'Symfony',
            'Java', 'Spring', 'Kotlin', 'Swift', 'iOS', 'Android',
            'HTML', 'CSS', 'SASS', 'Tailwind', 'Bootstrap',
            'MySQL', 'PostgreSQL', 'MongoDB', 'Redis',
            'AWS', 'Docker', 'Kubernetes', 'Git', 'CI/CD',
        ];

        $matchingSkills = array_filter($skills, function ($skill) use ($query) {
            return stripos($skill, $query) !== false;
        });

        // Count occurrences in job listings
        $results = [];
        foreach (array_slice($matchingSkills, 0, $limit) as $skill) {
            $count = JobListing::where('description', 'LIKE', "%{$skill}%")->count();

            if ($count > 0) {
                $results[] = [
                    'skill' => $skill,
                    'count' => $count,
                ];
            }
        }

        return array_slice($results, 0, $limit);
    }

    /**
     * Get similar searches from analytics
     */
    protected function getSimilarSearches(string $query, int $limit): array
    {
        $words = explode(' ', $query);
        $conditions = [];

        foreach ($words as $word) {
            if (strlen($word) > 2) {
                $conditions[] = "query LIKE '%{$word}%'";
            }
        }

        if (empty($conditions)) {
            return [];
        }

        $whereClause = implode(' OR ', $conditions);

        return SearchAnalytics::select('query', DB::raw('COUNT(*) as count'))
            ->whereRaw($whereClause)
            ->where('query', '!=', $query)
            ->groupBy('query')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get popular job titles for fallback
     */
    protected function getPopularJobTitles(int $limit): array
    {
        return JobListing::select('job_title', DB::raw('COUNT(*) as count'))
            ->groupBy('job_title')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'title' => $item->job_title,
                    'count' => $item->count,
                ];
            })
            ->toArray();
    }

    /**
     * Track a search query
     */
    public function trackSearch(array $data): void
    {
        SearchAnalytics::create([
            'query' => trim($data['query']),
            'user_id' => $data['user_id'] ?? null,
            'ip_address' => $data['ip_address'] ?? request()->ip(),
            'user_agent' => $data['user_agent'] ?? request()->userAgent(),
            'results_count' => $data['results_count'] ?? 0,
            'filters_applied' => $data['filters'] ?? [],
            'session_id' => $data['session_id'] ?? session()->getId(),
            'searched_at' => now(),
        ]);
    }

    /**
     * Clear suggestion caches
     */
    public function clearCaches(): void
    {
        Cache::forget('popular_suggestions_*');
        Cache::forget('search_suggestions_*');
    }

    /**
     * Get search statistics
     */
    public function getSearchStats(): array
    {
        return Cache::remember('search_stats', 3600, function () {
            return [
                'total_searches' => SearchAnalytics::count(),
                'unique_queries' => SearchAnalytics::distinct('query')->count(),
                'searches_today' => SearchAnalytics::whereDate('searched_at', today())->count(),
                'popular_queries' => SearchAnalytics::popular(10)->get(),
                'trending_queries' => SearchAnalytics::trending()->limit(5)->get(),
            ];
        });
    }
}
