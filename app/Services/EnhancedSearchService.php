<?php

namespace App\Services;

use App\Models\JobListing;

class EnhancedSearchService
{
    /**
     * Perform an enhanced search with faceting and advanced filtering
     */
    public function search(array $params): SearchResponse
    {
        $query = $params['query'] ?? '';
        $filters = $params['filters'] ?? [];
        $facets = $params['facets'] ?? true;
        $page = $params['page'] ?? 1;
        $perPage = $params['per_page'] ?? 20;
        $sortBy = $params['sort_by'] ?? '_text_match:desc,posted_at:desc';

        // Build the search
        $searchBuilder = JobListing::search($query);

        // Apply filters
        $filterString = $this->buildFilterString($filters);
        if ($filterString) {
            $searchBuilder->options([
                'filter_by' => $filterString
            ]);
        }

        // Add faceting if requested
        if ($facets) {
            $facetFields = $this->getFacetFields();
            $searchBuilder->options([
                'facet_by' => implode(',', $facetFields),
                'max_facet_values' => 50,
            ]);
        }

        // Add sorting
        $searchBuilder->options([
            'sort_by' => $sortBy
        ]);

        // Execute search with pagination
        $results = $searchBuilder->paginate($perPage, 'page', $page);

        // Extract facet counts if available
        $facetCounts = [];
        if ($facets && method_exists($results, 'getOptions')) {
            $facetCounts = $this->extractFacetCounts($results);
        }

        return new SearchResponse([
            'results' => $results,
            'facets' => $facetCounts,
            'total' => $results->total(),
            'query' => $query,
            'filters' => $filters,
            'page' => $page,
            'per_page' => $perPage,
        ]);
    }

    /**
     * Get available facets with their counts
     */
    public function getFacets(array $filters = []): array
    {
        $searchBuilder = JobListing::search('*');

        // Apply existing filters (except the one we're getting facets for)
        $filterString = $this->buildFilterString($filters);
        if ($filterString) {
            $searchBuilder->options(['filter_by' => $filterString]);
        }

        // Get facets
        $facetFields = $this->getFacetFields();
        $searchBuilder->options([
            'facet_by' => implode(',', $facetFields),
            'max_facet_values' => 100,
        ]);

        $results = $searchBuilder->take(0)->get();

        return $this->extractFacetCounts($results);
    }

    /**
     * Get search suggestions with facet-aware autocomplete
     */
    public function getSuggestions(string $query, array $filters = []): array
    {
        if (strlen($query) < 2) {
            return [];
        }

        // Get job title suggestions
        $jobTitleSuggestions = $this->getJobTitleSuggestions($query, $filters);

        // Get employer suggestions
        $employerSuggestions = $this->getEmployerSuggestions($query, $filters);

        // Get location suggestions
        $locationSuggestions = $this->getLocationSuggestions($query, $filters);

        return [
            'job_titles' => $jobTitleSuggestions,
            'employers' => $employerSuggestions,
            'locations' => $locationSuggestions,
        ];
    }

    /**
     * Perform geographic radius search
     */
    public function searchByLocation(string $query, float $lat, float $lng, float $radiusKm, array $filters = []): SearchResponse
    {
        // Add geographic filter
        $filters['location_radius'] = [
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radiusKm,
        ];

        return $this->search([
            'query' => $query,
            'filters' => $filters,
        ]);
    }

    /**
     * Build filter string for Typesense
     */
    private function buildFilterString(array $filters): string
    {
        $filterParts = [];

        foreach ($filters as $key => $value) {
            $filterPart = $this->buildFilterPart($key, $value);
            if ($filterPart) {
                $filterParts[] = $filterPart;
            }
        }

        return implode(' && ', $filterParts);
    }

    /**
     * Build individual filter part
     */
    private function buildFilterPart(string $key, $value): ?string
    {
        switch ($key) {
            case 'country':
                return is_array($value) ? "country:[" . implode(',', $value) . "]" : "country:={$value}";

            case 'city':
                return is_array($value) ? "city:[" . implode(',', $value) . "]" : "city:={$value}";

            case 'state':
                return is_array($value) ? "state:[" . implode(',', $value) . "]" : "state:={$value}";

            case 'is_remote':
                return "is_remote:=" . ($value ? 'true' : 'false');

            case 'employment_type':
                return is_array($value) ? "employment_type:[" . implode(',', $value) . "]" : "employment_type:={$value}";

            case 'job_category':
                return is_array($value) ? "job_category:[" . implode(',', $value) . "]" : "job_category:={$value}";

            case 'publisher':
                return is_array($value) ? "publisher:[" . implode(',', $value) . "]" : "publisher:={$value}";

            case 'salary_min':
                return "salary_min:>=" . (int) $value;

            case 'salary_max':
                return "salary_max:<=" . (int) $value;

            case 'salary_range':
                if (isset($value['min']) && isset($value['max'])) {
                    return "salary_min:>={$value['min']} && salary_max:<={$value['max']}";
                }
                return null;

            case 'experience_range':
                if (isset($value['min']) && isset($value['max'])) {
                    return "required_experience:[{$value['min']}..{$value['max']}]";
                }
                return null;

            case 'location_radius':
                if (isset($value['lat'], $value['lng'], $value['radius'])) {
                    return "location_geopoint:({$value['lat']},{$value['lng']},{$value['radius']} km)";
                }
                return null;

            case 'posted_since':
                $timestamp = is_string($value) ? strtotime($value) : $value;
                return "posted_at:>={$timestamp}";

            default:
                return null;
        }
    }

    /**
     * Get facetable fields
     */
    private function getFacetFields(): array
    {
        return [
            'country',
            'city',
            'state',
            'employment_type',
            'is_remote',
            'job_category',
            'publisher',
            'salary_currency',
            'salary_period',
            'required_experience',
        ];
    }

    /**
     * Extract facet counts from search results
     */
    private function extractFacetCounts($results): array
    {
        return [];
    }

    /**
     * Get job title suggestions
     */
    private function getJobTitleSuggestions(string $query, array $filters): array
    {
        $searchBuilder = JobListing::search($query);

        if (!empty($filters)) {
            $filterString = $this->buildFilterString($filters);
            if ($filterString) {
                $searchBuilder->options(['filter_by' => $filterString]);
            }
        }

        $results = $searchBuilder->take(5)->get();

        return $results->pluck('job_title')->unique()->values()->toArray();
    }

    /**
     * Get employer suggestions
     */
    private function getEmployerSuggestions(string $query, array $filters): array
    {
        $searchBuilder = JobListing::search($query);

        if (!empty($filters)) {
            $filterString = $this->buildFilterString($filters);
            if ($filterString) {
                $searchBuilder->options(['filter_by' => $filterString]);
            }
        }

        $results = $searchBuilder->take(5)->get();

        return $results->pluck('employer_name')->unique()->values()->toArray();
    }

    /**
     * Get location suggestions
     */
    private function getLocationSuggestions(string $query, array $filters): array
    {
        $searchBuilder = JobListing::search($query);

        if (!empty($filters)) {
            $filterString = $this->buildFilterString($filters);
            if ($filterString) {
                $searchBuilder->options(['filter_by' => $filterString]);
            }
        }

        $results = $searchBuilder->take(10)->get();

        $locations = [];
        foreach ($results as $job) {
            if ($job->city && $job->country) {
                $location = $job->city . ', ' . $job->country;
                if ($job->state) {
                    $location = $job->city . ', ' . $job->state . ', ' . $job->country;
                }
                $locations[] = $location;
            }
        }

        return array_values(array_unique($locations));
    }
}
