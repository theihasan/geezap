<?php

namespace App\Models;

use Abbasudo\Purity\Traits\Filterable;
use App\Models\Scopes\JobListingScope;
use App\Observers\JobListingObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

#[ObservedBy([JobListingObserver::class])]
#[ScopedBy([JobListingScope::class])]
class JobListing extends Model
{
    use Filterable, HasFactory, MassPrunable, Searchable;

    protected $fillable = [
        'job_id',
        'uuid',
        'employer_name',
        'employer_logo',
        'employer_website',
        'employer_company_type',
        'publisher',
        'employment_type',
        'job_title',
        'slug',
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

    // Accessor to provide apply_options as an array (for backward compatibility with tests)
    public function getApplyOptionsAttribute(): array
    {
        return $this->applyOptions()->orderBy('publisher', 'desc')->get()->map(function ($option) {
            return [
                'publisher' => $option->publisher,
                'apply_link' => $option->apply_link,
                'is_direct' => $option->is_direct,
            ];
        })->toArray();
    }

    public function prunable(): Builder
    {
        return static::query()->where('created_at', '<=', now()->subDays(14));
    }

    public function toSearchableArray(): array
    {
        $array = $this->toArray();

        // Convert datetime fields to timestamps for Typesense compatibility
        if (isset($array['posted_at']) && $array['posted_at']) {
            $array['posted_at'] = $this->posted_at->timestamp;
        } else {
            // Use created_at as fallback for null posted_at
            $array['posted_at'] = $this->created_at->timestamp;
        }

        if (isset($array['expired_at']) && $array['expired_at']) {
            $array['expired_at'] = $this->expired_at->timestamp;
        }

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
