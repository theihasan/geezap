<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class JobCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'query_name',
        'page',
        'num_page',
        'timeframe',
        'category_image',
    ];


    public function jobs()
    {
        return $this->hasMany(JobListing::class, 'job_category');
    }

    public function listings()
    {
        return $this->hasMany(JobListing::class, 'job_category');
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'job_category_country')
            ->withTimestamps();
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }

    public static function getTopCategories()
    {
        return Cache::remember('jobCategories', 24 * 60, function () {
            return static::query()
                ->withCount('jobs')
                ->with('countries')
                ->orderByDesc('jobs_count')
                ->take(8)
                ->get();
        });
    }

    public static function getAllCategories()
    {
        return Cache::remember('jobCategories', 24 * 60, function () {
            return static::query()
                ->withCount('jobs')
                ->orderByDesc('jobs_count')
                ->get();
        });
    }

    protected function categoryImage(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value !== null ? $value : ''
        );
    }
}
