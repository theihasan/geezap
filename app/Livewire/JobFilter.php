<?php

namespace App\Livewire;

use App\Caches\JobFilterCache;
use App\Models\Country;
use App\Models\JobCategory;
use App\Models\JobListing;
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


    public function clearAllFilters()
    {
        $this->reset([
            'search',
            'source',
            'exclude_source',
            'country',
            'category',
            'remote',
            'types'
        ]);
        $this->resetPage();
    }

    public function render()
    {
        $jobs = JobListing::query()
            ->when($this->search, fn($query, $search) =>
            $query->where('job_title', 'like', '%' . $search . '%'))
            ->when($this->source, fn($query, $source) =>
            $query->where('publisher', $source))
            ->when($this->exclude_source, fn($query, $exclude_source) =>
            $query->where('publisher', '!=', $exclude_source))
            ->when($this->country, fn($query, $country) =>
            $query->where('country', $country))
            ->when($this->category, fn($query, $category) =>
            $query->whereRelation('category', 'id', $category))
            ->when($this->remote, fn($query) =>
            $query->where('is_remote', true))
            ->when(!empty($this->types), fn($query) =>
            $query->whereIn('employment_type', $this->types))
            ->latest()
            ->paginate(10);

        return view('livewire.job-filter', [
            'jobs' => $jobs,
            'categories' => $this->getCategories(),
            'publishers' => $this->getPublishers(),
            'countries' => $this->getCountries(),
            'jobTypes' => $this->jobTypes
        ]);
    }
}
