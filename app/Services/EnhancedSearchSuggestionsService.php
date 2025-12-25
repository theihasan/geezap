<?php

namespace App\Services;

use App\Models\JobListing;
use App\Models\SearchAnalytics;
use Illuminate\Support\Facades\Cache;

class EnhancedSearchSuggestionsService
{
    /**
     * Get intelligent search suggestions
     */
    public function getSuggestions(string $query, array $filters = [], int $limit = 10): array
    {
        if (strlen($query) < 2) {
            return $this->getPopularSuggestions($limit);
        }

        return [
            'job_titles' => $this->getJobTitleSuggestions($query, $filters, $limit),
            'employers' => $this->getEmployerSuggestions($query, $filters, $limit),
            'locations' => $this->getLocationSuggestions($query, $filters, $limit),
            'skills' => $this->getSkillSuggestions($query, $filters, $limit),
            'popular_searches' => $this->getPopularSearchSuggestions($query, $limit),
        ];
    }

    /**
     * Get job title suggestions with typo tolerance
     */
    public function getJobTitleSuggestions(string $query, array $filters = [], int $limit = 10): array
    {
        $cacheKey = "job_title_suggestions:" . md5($query . serialize($filters)) . ":{$limit}";

        return Cache::remember($cacheKey, 300, function () use ($query, $filters, $limit) {
            $searchBuilder = JobListing::search($query);

            if (!empty($filters)) {
                $filterString = \App\Pipelines\EnhancedJobFilter::buildTypesenseFilters($filters);
                if ($filterString) {
                    $searchBuilder->options(['filter_by' => $filterString]);
                }
            }

            $results = $searchBuilder->take($limit * 3)->get();

            $suggestions = [];
            foreach ($results as $job) {
                if ($job->job_title && !in_array($job->job_title, $suggestions)) {
                    $suggestions[] = [
                        'title' => $job->job_title,
                        'count' => $this->getJobTitleCount($job->job_title, $filters),
                        'type' => 'job_title'
                    ];
                }
            }

            // Sort by relevance and count
            usort($suggestions, function($a, $b) {
                return $b['count'] - $a['count'];
            });

            return array_slice($suggestions, 0, $limit);
        });
    }

    /**
     * Get employer suggestions
     */
    public function getEmployerSuggestions(string $query, array $filters = [], int $limit = 10): array
    {
        $cacheKey = "employer_suggestions:" . md5($query . serialize($filters)) . ":{$limit}";

        return Cache::remember($cacheKey, 300, function () use ($query, $filters, $limit) {
            $searchBuilder = JobListing::search($query);

            if (!empty($filters)) {
                $filterString = \App\Pipelines\EnhancedJobFilter::buildTypesenseFilters($filters);
                if ($filterString) {
                    $searchBuilder->options(['filter_by' => $filterString]);
                }
            }

            $results = $searchBuilder->take($limit * 2)->get();

            $employers = [];
            foreach ($results as $job) {
                if ($job->employer_name && !isset($employers[$job->employer_name])) {
                    $employers[$job->employer_name] = [
                        'name' => $job->employer_name,
                        'count' => $this->getEmployerJobCount($job->employer_name, $filters),
                        'type' => 'employer'
                    ];
                }
            }

            // Sort by job count
            uasort($employers, function($a, $b) {
                return $b['count'] - $a['count'];
            });

            return array_values(array_slice($employers, 0, $limit));
        });
    }

    /**
     * Get location suggestions with job counts
     */
    public function getLocationSuggestions(string $query, array $filters = [], int $limit = 10): array
    {
        $cacheKey = "location_suggestions:" . md5($query . serialize($filters)) . ":{$limit}";

        return Cache::remember($cacheKey, 300, function () use ($query, $filters, $limit) {
            $locationQuery = JobListing::query()
                ->select('city', 'state', 'country', 'latitude', 'longitude')
                ->selectRaw('COUNT(*) as job_count')
                ->where(function($q) use ($query) {
                    $q->where('city', 'like', "%{$query}%")
                      ->orWhere('state', 'like', "%{$query}%")
                      ->orWhere('country', 'like', "%{$query}%");
                })
                ->whereNotNull('city')
                ->groupBy('city', 'state', 'country', 'latitude', 'longitude')
                ->orderBy('job_count', 'desc')
                ->limit($limit);

            $results = $locationQuery->get();

            $suggestions = [];
            foreach ($results as $location) {
                $displayName = $this->formatLocationName($location);
                $suggestions[] = [
                    'name' => $displayName,
                    'city' => $location->city,
                    'state' => $location->state,
                    'country' => $location->country,
                    'lat' => $location->latitude,
                    'lng' => $location->longitude,
                    'count' => $location->job_count,
                    'type' => 'location'
                ];
            }

            return $suggestions;
        });
    }

    /**
     * Get skill suggestions from job qualifications
     */
    public function getSkillSuggestions(string $query, array $filters = [], int $limit = 10): array
    {
        $cacheKey = "skill_suggestions:" . md5($query . serialize($filters)) . ":{$limit}";

        return Cache::remember($cacheKey, 3600, function () use ($query, $limit) {
            $jobs = JobListing::whereNotNull('qualifications')
                ->orWhereNotNull('responsibilities')
                ->get();

            $skills = [];
            foreach ($jobs as $job) {
                $qualifications = $job->qualifications ?? [];
                $responsibilities = $job->responsibilities ?? [];

                $allSkills = array_merge($qualifications, $responsibilities);

                foreach ($allSkills as $skill) {
                    $skill = trim($skill);
                    if (stripos($skill, $query) !== false && strlen($skill) > 2) {
                        $skills[$skill] = ($skills[$skill] ?? 0) + 1;
                    }
                }
            }

            // Sort by frequency
            arsort($skills);

            $suggestions = [];
            foreach (array_slice($skills, 0, $limit, true) as $skill => $count) {
                $suggestions[] = [
                    'skill' => $skill,
                    'count' => $count,
                    'type' => 'skill'
                ];
            }

            return $suggestions;
        });
    }

    /**
     * Get popular search suggestions based on analytics
     */
    public function getPopularSearchSuggestions(string $query, int $limit = 5): array
    {
        if (!class_exists(SearchAnalytics::class)) {
            return [];
        }

        $cacheKey = "popular_searches:" . md5($query) . ":{$limit}";

        return Cache::remember($cacheKey, 1800, function () use ($query, $limit) {
            return SearchAnalytics::where('query', 'like', "%{$query}%")
                ->selectRaw('query, COUNT(*) as search_count')
                ->groupBy('query')
                ->orderBy('search_count', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    return [
                        'query' => $item->query,
                        'count' => $item->search_count,
                        'type' => 'popular_search'
                    ];
                })
                ->toArray();
        });
    }

    /**
     * Get popular suggestions when no query provided
     */
    public function getPopularSuggestions(int $limit = 10): array
    {
        return Cache::remember("popular_suggestions:{$limit}", 3600, function () use ($limit) {
            return [
                'trending_searches' => $this->getTrendingSearches($limit),
                'popular_job_titles' => $this->getPopularJobTitles($limit),
                'top_employers' => $this->getTopEmployers($limit),
                'popular_locations' => $this->getPopularLocations($limit),
            ];
        });
    }

    /**
     * Get autocomplete suggestions with smart ranking
     */
    public function getAutocompleteSuggestions(string $query, array $context = []): array
    {
        if (strlen($query) < 2) {
            return [];
        }

        $suggestions = [];

        // Combine different suggestion types
        $jobTitles = $this->getJobTitleSuggestions($query, $context, 3);
        $employers = $this->getEmployerSuggestions($query, $context, 2);
        $locations = $this->getLocationSuggestions($query, $context, 3);
        $skills = $this->getSkillSuggestions($query, $context, 2);

        // Merge and rank suggestions
        $allSuggestions = array_merge($jobTitles, $employers, $locations, $skills);

        // Sort by relevance score
        usort($allSuggestions, function($a, $b) use ($query) {
            $aScore = $this->calculateRelevanceScore($a, $query);
            $bScore = $this->calculateRelevanceScore($b, $query);
            return $bScore - $aScore;
        });

        return array_slice($allSuggestions, 0, 10);
    }

    /**
     * Calculate relevance score for suggestion ranking
     */
    private function calculateRelevanceScore(array $suggestion, string $query): float
    {
        $score = 0;
        $text = '';

        // Get text to match against
        if (isset($suggestion['title'])) {
            $text = $suggestion['title'];
        } elseif (isset($suggestion['name'])) {
            $text = $suggestion['name'];
        } elseif (isset($suggestion['skill'])) {
            $text = $suggestion['skill'];
        } elseif (isset($suggestion['query'])) {
            $text = $suggestion['query'];
        }

        // Exact match bonus
        if (stripos($text, $query) === 0) {
            $score += 100;
        } elseif (stripos($text, $query) !== false) {
            $score += 50;
        }

        // Frequency bonus
        $score += ($suggestion['count'] ?? 0) / 100;

        // Length penalty (prefer shorter matches)
        $score -= strlen($text) / 1000;

        return $score;
    }

    /**
     * Get job title count with filters
     */
    private function getJobTitleCount(string $jobTitle, array $filters = []): int
    {
        $query = JobListing::where('job_title', $jobTitle);

        // Apply filters
        if (isset($filters['country'])) {
            $query->where('country', $filters['country']);
        }
        if (isset($filters['is_remote'])) {
            $query->where('is_remote', $filters['is_remote']);
        }

        return $query->count();
    }

    /**
     * Get employer job count
     */
    private function getEmployerJobCount(string $employerName, array $filters = []): int
    {
        $query = JobListing::where('employer_name', $employerName);

        // Apply filters similar to job title count
        if (isset($filters['country'])) {
            $query->where('country', $filters['country']);
        }

        return $query->count();
    }

    /**
     * Format location name
     */
    private function formatLocationName($location): string
    {
        $parts = array_filter([
            $location->city,
            $location->state,
            $location->country
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get trending searches
     */
    private function getTrendingSearches(int $limit): array
    {
        if (!class_exists(SearchAnalytics::class)) {
            return $this->getStaticTrendingSearches($limit);
        }

        $oneWeekAgo = now()->subWeek();

        return SearchAnalytics::where('created_at', '>=', $oneWeekAgo)
            ->selectRaw('query, COUNT(*) as search_count')
            ->groupBy('query')
            ->orderBy('search_count', 'desc')
            ->limit($limit)
            ->pluck('search_count', 'query')
            ->toArray();
    }

    /**
     * Get popular job titles
     */
    private function getPopularJobTitles(int $limit): array
    {
        return JobListing::selectRaw('job_title, COUNT(*) as count')
            ->whereNotNull('job_title')
            ->groupBy('job_title')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->pluck('count', 'job_title')
            ->toArray();
    }

    /**
     * Get top employers
     */
    private function getTopEmployers(int $limit): array
    {
        return JobListing::selectRaw('employer_name, COUNT(*) as count')
            ->whereNotNull('employer_name')
            ->groupBy('employer_name')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->pluck('count', 'employer_name')
            ->toArray();
    }

    /**
     * Get popular locations
     */
    private function getPopularLocations(int $limit): array
    {
        return JobListing::selectRaw('CONCAT(city, ", ", country) as location, COUNT(*) as count')
            ->whereNotNull('city')
            ->whereNotNull('country')
            ->groupBy('city', 'country')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->pluck('count', 'location')
            ->toArray();
    }

    /**
     * Get static trending searches as fallback
     */
    private function getStaticTrendingSearches(int $limit): array
    {
        $trending = [
            'software engineer' => 450,
            'data scientist' => 320,
            'product manager' => 280,
            'frontend developer' => 250,
            'marketing manager' => 220,
            'sales representative' => 200,
            'graphic designer' => 180,
            'project manager' => 170,
            'business analyst' => 150,
            'customer support' => 130,
        ];

        return array_slice($trending, 0, $limit, true);
    }
}
