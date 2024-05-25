<?php

namespace App\Models;

use App\Observers\JobListingObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([JobListingObserver::class])]
class JobListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_name',
        'employer_logo',
        'employer_website',
        'employer_company_type',
        'publisher',
        'employment_type',
        'job_title',
        'job_category',
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
        ];
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}
