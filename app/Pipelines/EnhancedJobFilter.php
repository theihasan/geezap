<?php

namespace App\Pipelines;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class EnhancedJobFilter
{
    /**
     * Build Typesense filter string from request parameters
     */
    public static function buildTypesenseFilters(array $filters): string
    {
        $filterParts = [];

        foreach ($filters as $key => $value) {
            $filterPart = self::buildFilterPart($key, $value);
            if ($filterPart) {
                $filterParts[] = $filterPart;
            }
        }

        return implode(' && ', $filterParts);
    }

    /**
     * Handle Eloquent query filters (fallback for non-search queries)
     */
    public function handle($request, Closure $next)
    {
        $query = $next($request);

        if ($request->has('search') && !empty($request->input('search'))) {
            // Use Scout search instead of SQL
            return $query;
        }

        // Apply SQL filters for non-search queries
        return $this->applyEloquentFilters($query, $request);
    }

    /**
     * Apply Eloquent filters for SQL queries
     */
    private function applyEloquentFilters(Builder $query, $request): Builder
    {
        // Country filter
        if ($request->filled('country')) {
            $countries = is_array($request->country) ? $request->country : [$request->country];
            $query->whereIn('country', $countries);
        }

        // City filter
        if ($request->filled('city')) {
            $cities = is_array($request->city) ? $request->city : [$request->city];
            $query->whereIn('city', $cities);
        }

        // State filter
        if ($request->filled('state')) {
            $states = is_array($request->state) ? $request->state : [$request->state];
            $query->whereIn('state', $states);
        }

        // Remote work filter
        if ($request->filled('is_remote')) {
            $query->where('is_remote', $request->boolean('is_remote'));
        }

        // Employment type filter
        if ($request->filled('employment_type')) {
            $types = is_array($request->employment_type) ? $request->employment_type : [$request->employment_type];
            $query->whereIn('employment_type', $types);
        }

        // Job category filter
        if ($request->filled('job_category')) {
            $categories = is_array($request->job_category) ? $request->job_category : [$request->job_category];
            $query->whereIn('job_category', $categories);
        }

        // Publisher filter
        if ($request->filled('publisher')) {
            $publishers = is_array($request->publisher) ? $request->publisher : [$request->publisher];
            $query->whereIn('publisher', $publishers);
        }

        // Salary filters
        if ($request->filled('salary_min')) {
            $query->where('min_salary', '>=', (int) $request->salary_min);
        }

        if ($request->filled('salary_max')) {
            $query->where('max_salary', '<=', (int) $request->salary_max);
        }

        // Experience filter
        if ($request->filled('experience_min')) {
            $query->where('required_experience', '>=', (int) $request->experience_min);
        }

        if ($request->filled('experience_max')) {
            $query->where('required_experience', '<=', (int) $request->experience_max);
        }

        // Date filters
        if ($request->filled('posted_since')) {
            $since = is_string($request->posted_since) 
                ? \Carbon\Carbon::parse($request->posted_since) 
                : $request->posted_since;
            $query->where('posted_at', '>=', $since);
        }

        // Location radius filter (using raw SQL for geographic calculations)
        if ($request->filled(['latitude', 'longitude', 'radius'])) {
            $lat = (float) $request->latitude;
            $lng = (float) $request->longitude;
            $radius = (float) $request->radius;

            $query->whereRaw("
                (6371 * ACOS(
                    COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + 
                    SIN(RADIANS(?)) * SIN(RADIANS(latitude))
                )) <= ?
            ", [$lat, $lng, $lat, $radius]);
        }

        return $query;
    }

    /**
     * Build individual filter part for Typesense
     */
    private static function buildFilterPart(string $key, $value): ?string
    {
        if (empty($value) && $value !== 0 && $value !== false) {
            return null;
        }

        switch ($key) {
            case 'country':
            case 'countries':
                return self::buildArrayFilter('country', $value);

            case 'city':
            case 'cities':
                return self::buildArrayFilter('city', $value);

            case 'state':
            case 'states':
                return self::buildArrayFilter('state', $value);

            case 'is_remote':
            case 'remote':
                return "is_remote:=" . ($value ? 'true' : 'false');

            case 'employment_type':
            case 'employment_types':
                return self::buildArrayFilter('employment_type', $value);

            case 'job_category':
            case 'job_categories':
                return self::buildArrayFilter('job_category', $value);

            case 'publisher':
            case 'publishers':
                return self::buildArrayFilter('publisher', $value);

            case 'salary_min':
                return "salary_min:>=" . (int) $value;

            case 'salary_max':
                return "salary_max:<=" . (int) $value;

            case 'salary_range':
                if (is_array($value) && isset($value['min'], $value['max'])) {
                    $filters = [];
                    if ($value['min'] > 0) {
                        $filters[] = "salary_min:>={$value['min']}";
                    }
                    if ($value['max'] > 0) {
                        $filters[] = "salary_max:<={$value['max']}";
                    }
                    return implode(' && ', $filters);
                }
                return null;

            case 'experience_min':
                return "required_experience:>=" . (int) $value;

            case 'experience_max':
                return "required_experience:<=" . (int) $value;

            case 'experience_range':
                if (is_array($value) && isset($value['min'], $value['max'])) {
                    return "required_experience:[{$value['min']}..{$value['max']}]";
                }
                return null;

            case 'location_radius':
                if (is_array($value) && isset($value['lat'], $value['lng'], $value['radius'])) {
                    return "location_geopoint:({$value['lat']},{$value['lng']},{$value['radius']} km)";
                }
                return null;

            case 'posted_since':
                $timestamp = is_string($value) ? strtotime($value) : $value;
                return "posted_at:>={$timestamp}";

            case 'skills':
                return self::buildArrayFilter('qualifications', $value, ':');

            case 'benefits':
                return self::buildArrayFilter('benefits', $value, ':');

            case 'qualifications':
                return self::buildArrayFilter('qualifications', $value, ':');

            default:
                return null;
        }
    }

    /**
     * Build array filter for multiple values
     */
    private static function buildArrayFilter(string $field, $value, string $operator = ':='): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (is_array($value)) {
            if (count($value) === 1) {
                return "{$field}{$operator}" . self::escapeFilterValue($value[0]);
            }
            $escapedValues = array_map([self::class, 'escapeFilterValue'], $value);
            return "{$field}:[" . implode(',', $escapedValues) . "]";
        }

        return "{$field}{$operator}" . self::escapeFilterValue($value);
    }

    /**
     * Escape filter value for Typesense
     */
    private static function escapeFilterValue($value): string
    {
        $value = (string) $value;
        
        // If value contains special characters, wrap in backticks
        if (preg_match('/[&|(),\[\]]/', $value)) {
            return '`' . str_replace('`', '\\`', $value) . '`';
        }

        return $value;
    }

    /**
     * Get available facets for the given filters
     */
    public static function getAvailableFacets(array $filters = []): array
    {
        return [
            'country' => self::getCountryFacets($filters),
            'city' => self::getCityFacets($filters),
            'state' => self::getStateFacets($filters),
            'employment_type' => self::getEmploymentTypeFacets($filters),
            'is_remote' => self::getRemoteFacets($filters),
            'job_category' => self::getJobCategoryFacets($filters),
            'publisher' => self::getPublisherFacets($filters),
            'salary_range' => self::getSalaryRangeFacets($filters),
            'experience_level' => self::getExperienceFacets($filters),
        ];
    }

    /**
     * Get country facets
     */
    private static function getCountryFacets(array $filters): array
    {
        // This will be implemented to return country facet counts
        // For now, return static data for testing
        return [
            'US' => 1500,
            'GB' => 800,
            'CA' => 600,
            'DE' => 400,
            'AU' => 300,
        ];
    }

    /**
     * Get employment type facets
     */
    private static function getEmploymentTypeFacets(array $filters): array
    {
        return [
            'Full-time' => 2500,
            'Part-time' => 400,
            'Contract' => 600,
            'Freelance' => 200,
            'Internship' => 100,
        ];
    }

    /**
     * Get remote work facets
     */
    private static function getRemoteFacets(array $filters): array
    {
        return [
            'true' => 1200,
            'false' => 2600,
        ];
    }

    // Additional facet methods would be implemented similarly...
    private static function getCityFacets(array $filters): array { return []; }
    private static function getStateFacets(array $filters): array { return []; }
    private static function getJobCategoryFacets(array $filters): array { return []; }
    private static function getPublisherFacets(array $filters): array { return []; }
    private static function getSalaryRangeFacets(array $filters): array { return []; }
    private static function getExperienceFacets(array $filters): array { return []; }
}