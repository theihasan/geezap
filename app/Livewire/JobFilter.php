<?php

namespace App\Livewire;

use App\Caches\JobFilterCache;
use App\Models\Country;
use App\Models\JobCategory;
use App\Models\JobListing;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class JobFilter extends Component
{
    use WithPagination;

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
        return JobFilterCache::getCategories();
    }


    protected function getPublishers()
    {
        return JobFilterCache::getPublishers();
    }


    protected function getCountries()
    {
        return JobFilterCache::getCountries();
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
        // Use Laravel's built-in pagination instead of manual pagination
        $perPage = $this->perPage;

        if ($this->search) {
            // Optimize search query by using Scout's query builder more efficiently
            $searchQuery = JobListing::search($this->search);

            // Apply filters to the search query
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

            // Get the category filter working with Scout
            if ($this->category) {
                // Since category filtering might not work directly with Scout,
                // we'll get all IDs that match the search criteria first
                $jobIds = $searchQuery->keys();

                // Then filter by category using Eloquent
                $jobs = JobListing::whereIn('id', $jobIds)
                    ->when($this->category, fn($query, $category) =>
                        $query->whereHas('category', function($query) use ($category) {
                            $query->where('id', $category);
                        }))
                    ->latest()
                    ->take($perPage)
                    ->get();

                // Get total for pagination
                $total = JobListing::whereIn('id', $jobIds)
                    ->when($this->category, fn($query, $category) =>
                        $query->whereHas('category', function($query) use ($category) {
                            $query->where('id', $category);
                        }))
                    ->count();
            } else {
                // If no category filter, we can use Scout's paginate directly
                // But we need to get the models with all their relations
                $searchResults = $searchQuery->paginate($perPage);
                $jobs = $searchResults->items();
                $total = $searchResults->total();
            }
        } else {
            // Optimize regular query by using eager loading and query caching
            $query = JobListing::query()
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
                    $query->whereIn('employment_type', $this->types))
                ->latest();

            // Use a more efficient approach to get both the count and the results
            // Cache the count query result for 5 minutes to improve performance
            $cacheKey = 'job_filter_count_' . md5(json_encode([
                $this->source,
                $this->exclude_source,
                $this->country,
                $this->category,
                $this->remote,
                $this->types
            ]));

            $total = Cache::remember($cacheKey, now()->addMinutes(5), function() use ($query) {
                return $query->count();
            });

            $jobs = $query->with('category') // Eager load the category relationship
                ->take($perPage)
                ->get();
        }

        $this->hasMorePages = $total > $perPage;

        return view('livewire.job-filter', [
            'jobs' => $jobs,
            'categories' => $this->getCategories(),
            'publishers' => $this->getPublishers(),
            'countries' => $this->getCountries(),
            'jobTypes' => $this->jobTypes
        ]);
    }
}
