<?php

namespace App\Models;

use App\Filters\JobFilter;
use Laravel\Scout\Searchable;
use Illuminate\Support\Facades\Log;
use App\Observers\JobListingObserver;
use Abbasudo\Purity\Traits\Filterable;
use App\Models\Scopes\JobListingScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([JobListingObserver::class])]
#[ScopedBy([JobListingScope::class])]
class JobListing extends Model
{
    use HasFactory, Filterable, MassPrunable, Searchable;

    protected $fillable = [
        'job_id',
        'employer_name',
        'employer_logo',
        'employer_website',
        'employer_company_type',
        'publisher',
        'employment_type',
        'job_title',
        'job_category',
        'category_image',
        'apply_link',
        'description',
        'is_remote',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'google_link',
        'posted_at',
        'expired_at',
        'min_salary',
        'max_salary',
        'salary_currency',
        'salary_period',
        'benefits',
        'qualifications',
        'responsibilities',
        'required_experience',
    ];

    protected function casts(): array
    {
        return [
            'posted_at' => 'datetime',
            'expired_at' => 'datetime',
            'required_experience' => 'integer',
            'qualifications' => 'array',
            'benefits' => 'array',
            'responsibilities' => 'array',
            'skills' => 'array',
            'is_remote' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'job_user', 'job_id', 'user_id')
            ->withTimestamps();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'job_category', 'id');
    }

    public function applyOptions(): HasMany
    {
        return $this->hasMany(JobApplyOption::class);
    }

    public function prunable(): Builder
    {
        return static::query()->where('created_at', '<=', now()->subDays(14));
    }


    public function toSearchableArray(): array
    {
        $array = $this->toArray();
        return array_merge($array, [
            'id' => (string) $this->id,
            'created_at' => $this->created_at->timestamp,
            'job_category' => (string) $this->job_category, 
            'is_remote' => (bool) $this->is_remote,
            'publisher' => (string) $this->publisher,
            'salary_min' => (int) $this->min_salary,
            'salary_max' => (int) $this->max_salary,
            'salary_currency' => (string) $this->salary_currency,
            'salary_period' => (string) $this->salary_period,
        ]);
    }

    public function searchableAs(): string
    {
        return 'listing_index';
    }

    public function package(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\Geezap\ContentFormatter\Models\Package::class, 'metadata->job_listing_id', 'id');
    }
}
