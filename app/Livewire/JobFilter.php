<?php

namespace App\Livewire;

use App\Caches\JobFilterCache;
use App\Models\Country;
use App\Models\JobCategory;
use App\Models\JobListing;
use App\Traits\DetectsUserCountry;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class JobFilter extends Component
{
    use WithPagination, DetectsUserCountry;

    public function mount()
    {
        // Ensure component is properly initialized
        if (!isset($this->perPage)) {
            $this->perPage = 10;
        }
        if (!isset($this->hasMorePages)) {
            $this->hasMorePages = false;
        }
        if (!is_array($this->types)) {
            $this->types = [];
        }
    }

    #[Url]
    public $search = '';
    #[Url]
    public $source = '';
    #[Url]
    public $exclude_source = '';
    #[Url]
    public $country = '';
    #[Url]
    public $category = '';
    #[Url]
    public $remote = false;
    #[Url]
    public $types = [];

    public $perPage = 10;
    public $hasMorePages = false;

    public $sections = [
        'basic' => true,
        'source' => false,
        'location' => false,
        'jobType' => false
    ];

    protected $jobTypes = [
        'fulltime' => 'Full Time',
        'contractor' => 'Contractor',
        'parttime' => 'Part Time'
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }


    public function getActiveFilterCount()
    {
        return collect([
                $this->search,
                $this->source,
                $this->exclude_source,
                $this->country,
                $this->category,
                $this->remote,
            ])->filter()->count() + count($this->types);
    }

    protected function getCategories()
    {
        try {
            return JobFilterCache::getCategories();
        } catch (\Throwable $e) {
            Log::warning('Failed to get categories from cache', ['error' => $e->getMessage()]);
            return JobCategory::all();
        }
    }

    protected function getPublishers()
    {
        try {
            return JobFilterCache::getPublishers();
        } catch (\Throwable $e) {
            Log::warning('Failed to get publishers from cache', ['error' => $e->getMessage()]);
            return JobListing::distinct('publisher')->pluck('publisher')->filter();
        }
    }

    protected function getCountries()
    {
        try {
            return JobFilterCache::getCountries();
        } catch (\Throwable $e) {
            Log::warning('Failed to get countries from cache', ['error' => $e->getMessage()]);
            return Country::all()->keyBy('code');
        }
    }
    public function updatedSource()
    {
        $this->resetList();
    }
    public function updatedExcludeSource()
    {
        $this->resetList();
    }
    public function updatedCountry()
    {
        $this->resetList();
    }
    public function updatedCategory()
    {
        $this->resetList();
    }
    public function updatedRemote()
    {
        $this->resetList();
    }
    public function updatedTypes()
    {
        $this->resetList();
    }


    protected function resetList()
    {
        $this->perPage = 10;
    }

    public function clearAllFilters()
    {
        $this->reset([
            'search',
            'source',
            'exclude_source',
            'country',
            'category',
            'remote',
            'types',
            'perPage'
        ]);
        $this->resetPage();
    }

    public function render()
    {
        try {
            $perPage = $this->perPage;

            if ($this->search) {
                $searchQuery = JobListing::search($this->search);

            $searchQuery = $searchQuery->when($this->source, fn($query, $source) =>
                $query->where('publisher', $source))
                ->when($this->exclude_source, fn($query, $exclude_source) =>
                $query->where('publisher', '!=', $exclude_source))
                ->when($this->country, fn($query, $country) =>
                $query->where('country', $country))
                ->when($this->remote, fn($query) =>
                $query->where('is_remote', true))
                ->when(!empty($this->types), fn($query) =>
                $query->whereIn('employment_type', $this->types));

            if ($this->category) {
                $jobIds = $searchQuery->keys();

                $jobs = JobListing::whereIn('id', $jobIds)
                    ->when($this->category, fn($query, $category) =>
                        $query->whereHas('category', function($query) use ($category) {
                            $query->where('id', $category);
                        }))
                    ->with('category')
                    ->latest()
                    ->take($perPage)
                    ->get();

                $total = JobListing::whereIn('id', $jobIds)
                    ->when($this->category, fn($query, $category) =>
                        $query->whereHas('category', function($query) use ($category) {
                            $query->where('id', $category);
                        }))
                    ->count();
            } else {
                $searchResults = $searchQuery->paginate($perPage);
                $jobs = $searchResults->items();
                $total = $searchResults->total();
            }
        } else {
            $baseQuery = JobListing::query()
                ->when($this->source, fn($query, $source) =>
                    $query->where('publisher', $source))
                ->when($this->exclude_source, fn($query, $exclude_source) =>
                    $query->where('publisher', '!=', $exclude_source))
                ->when($this->country, fn($query, $country) =>
                    $query->where('country', $country))
                ->when($this->category, fn($query, $category) =>
                    $query->whereHas('category', function($query) use ($category) {
                        $query->where('id', $category);
                    }))
                ->when($this->remote, fn($query) =>
                    $query->where('is_remote', true))
                ->when(!empty($this->types), fn($query) =>
                    $query->whereIn('employment_type', $this->types));

            $cacheKey = 'job_filter_count_' . md5(json_encode([
                $this->source,
                $this->exclude_source,
                $this->country,
                $this->category,
                $this->remote,
                $this->types
            ]));

            $total = Cache::remember($cacheKey, now()->addMinutes(5), function() use ($baseQuery) {
                return $baseQuery->count();
            });

            if (!$this->country) {
                $userCountry = $this->getUserCountry();

                if ($userCountry) {
                    // Prioritize jobs from user's country
                    $countryJobs = (clone $baseQuery)
                        ->where('country', $userCountry)
                        ->latest('posted_at')
                        ->with('category')
                        ->take($perPage)
                        ->get();

                    $jobs = $countryJobs;

                    if ($jobs->count() < $perPage) {
                        $needed = $perPage - $jobs->count();
                        $excludeIds = $jobs->pluck('id')->toArray();

                        $internationalJobs = (clone $baseQuery)
                            ->where('country', '!=', $userCountry)
                            ->whereNotIn('id', $excludeIds)
                            ->latest('posted_at')
                            ->with('category')
                            ->take($needed)
                            ->get();

                        $jobs = $jobs->merge($internationalJobs);
                    }
                } else {
                    $jobs = $baseQuery->latest('posted_at')
                        ->with('category')
                        ->take($perPage)
                        ->get();
                }
            } else {
                // User has selected a specific country filter, use normal ordering
                $jobs = $baseQuery->latest('posted_at')
                    ->with('category')
                    ->take($perPage)
                    ->get();
            }
        }

        $this->hasMorePages = $total > $perPage;

            $this->dispatch('jobCountUpdated', $total);

            return view('livewire.job-filter', [
                'jobs' => $jobs ?? collect([]),
                'categories' => method_exists($this, 'getCategories') ? $this->getCategories() : collect([]),
                'publishers' => method_exists($this, 'getPublishers') ? $this->getPublishers() : collect([]),
                'countries' => method_exists($this, 'getCountries') ? $this->getCountries() : collect([]),
                'jobTypes' => $this->jobTypes ?? [],
                'totalJobs' => $total ?? 0
            ]);
        } catch (\Throwable $e) {
            \Log::error('JobFilter render error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_authenticated' => auth()->check(),
                'memory_usage' => memory_get_usage(true)
            ]);
            
            // Return basic view with empty results on error
            return view('livewire.job-filter', [
                'jobs' => collect([]),
                'categories' => collect([]),
                'publishers' => collect([]),
                'countries' => collect([]),
                'jobTypes' => $this->jobTypes,
                'totalJobs' => 0
            ]);
        }
    }
}
