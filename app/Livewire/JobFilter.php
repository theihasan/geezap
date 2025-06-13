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

            $jobs = $query->with('category')
                ->take($perPage)
                ->get();
        }

        $this->hasMorePages = $total > $perPage;

        $this->dispatch('jobCountUpdated', $total);
        
        return view('livewire.job-filter', [
            'jobs' => $jobs,
            'categories' => $this->getCategories(),
            'publishers' => $this->getPublishers(),
            'countries' => $this->getCountries(),
            'jobTypes' => $this->jobTypes,
            'totalJobs' => $total  
        ]);
    }
}
