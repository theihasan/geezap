<?php

namespace App\Models;

use Abbasudo\Purity\Traits\Filterable;
use App\Filters\JobFilter;
use App\Observers\JobListingObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy([JobListingObserver::class])]
class JobListing extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
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
        'google_link',
        'posted_at',
        'expaire_at',
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
            'expaire_at' => 'datetime',
            'required_experience' => 'integer',
            'qualifications' => 'array',
            'benefits' => 'array',
            'responsibilities' => 'array',
            'skills' => 'array',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'job_student', 'user_id');
    }



}
