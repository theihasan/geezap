<?php

namespace App\Livewire;

use App\Models\Country;
use App\Models\JobCategory;
use App\Models\JobListing;
use Livewire\Component;
use Livewire\WithPagination;

class JobFilter extends Component
{
    use WithPagination;

    public $search = '';
    public $source = '';
    public $exclude_source = '';
    public $country = '';
    public $category = '';
    public $remote = false;
    public $types = [];

    public $sections = [
        'basic' => true,
        'source' => false,
        'location' => false,
        'jobType' => false
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'source' => ['except' => ''],
        'exclude_source' => ['except' => ''],
        'country' => ['except' => ''],
        'category' => ['except' => ''],
        'remote' => ['except' => false],
        'types' => ['except' => []]
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function clearAllFilters()
    {
        $this->reset(['search', 'source', 'exclude_source', 'country', 'category', 'remote', 'types']);
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

    public function getFilteredJobsProperty()
    {
        return JobListing::query()
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
    }

    public function render()
    {
        return view('livewire.job-filter', [
            'jobs' => $this->filteredJobs,
            'categories' => JobCategory::all(),
            'publishers' => JobListing::distinct()->pluck('publisher'),
            'countries' => Country::whereIn('code',
                JobListing::distinct()->whereNotNull('country')->pluck('country')
            )->get()->keyBy('code'),
            'jobTypes' => [
                'fulltime' => 'Full Time',
                'contractor' => 'Contractor',
                'parttime' => 'Part Time'
            ]
        ]);
    }
}
