<?php

namespace App\DTO;

use Carbon\Carbon;

readonly class JobDTO
{
    public function __construct(
        public string  $employerName,
        public ?string $employerLogo,
        public ?string $employerWebsite,
        public string  $publisher,
        public ?string  $employmentType,
        public string  $jobTitle,
        public int     $jobCategory,
        public string  $categoryImage,
        public string  $applyLink,
        public string  $description,
        public bool    $isRemote,
        public ?string $city,
        public ?string $state,
        public ?string $country,
        public ?string $googleLink,
        public ?string $postedAt,
        public ?string $expiredAt,
        public ?float  $minSalary,
        public ?float  $maxSalary,
        public ?string $salaryPeriod,
        public ?array  $benefits,
        public ?array  $qualifications,
        public ?array  $responsibilities,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            employerName: $data['employer_name'],
            employerLogo: $data['employer_logo'],
            employerWebsite: $data['employer_website'],
            publisher: $data['job_publisher'],
            employmentType: $data['job_employment_type'],
            jobTitle: $data['job_title'],
            jobCategory: $data['job_category'],
            categoryImage: $data['category_image'],
            applyLink: $data['job_apply_link'],
            description: $data['job_description'],
            isRemote: $data['job_is_remote'],
            city: $data['job_city'],
            state: $data['job_state'],
            country: $data['job_country'],
            googleLink: $data['job_google_link'],
            postedAt: isset($data['job_posted_at_datetime_utc']) ? Carbon::parse($data['job_posted_at_datetime_utc'])->toDateTimeString() : null,
            expiredAt: isset($data['job_offer_expiration_datetime_utc']) ? Carbon::parse($data['job_offer_expiration_datetime_utc'])->toDateTimeString() : null,
            minSalary: $data['job_min_salary'],
            maxSalary: $data['job_max_salary'],
            salaryPeriod: $data['job_salary_period'],
            benefits: $data['job_highlights']['Benefits'] ?? null,
            qualifications: $data['job_highlights']['Qualifications'] ?? null,
            responsibilities: $data['job_highlights']['Responsibilities'] ?? null,

        );
    }

    public function toArray(): array
    {
        return [
            'employer_name' => $this->employerName,
            'employer_logo' => $this->employerLogo,
            'employer_website' => $this->employerWebsite,
            'publisher' => $this->publisher,
            'employment_type' => $this->employmentType,
            'job_title' => $this->jobTitle,
            'job_category' => $this->jobCategory,
            'category_image' => $this->categoryImage,
            'apply_link' => $this->applyLink,
            'description' => $this->description,
            'is_remote' => $this->isRemote,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'google_link' => $this->googleLink,
            'posted_at' => $this->postedAt,
            'expired_at' => $this->expiredAt,
            'min_salary' => $this->minSalary,
            'max_salary' => $this->maxSalary,
            'salary_period' => $this->salaryPeriod,
            'benefits' => $this->benefits,
            'qualifications' => $this->qualifications,
            'responsibilities' => $this->responsibilities,
        ];
    }
}
