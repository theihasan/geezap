<?php

namespace App\Services;

use App\DTO\DiscordCardDTO;
use App\DTO\MetaTagDTO;
use App\DTO\OpenGraphDTO;
use App\DTO\StructuredMetaDataDTO;
use App\DTO\TwitterCardDTO;
use App\Models\JobListing;
use App\Models\JobCategory;
use App\Enums\JobCategory as JobCategoryEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class MetaTagGenerator
{
    public function getHomePageMeta(
        int $availableJobs,
        int $todayAddedJobsCount,
        int $jobCategoriesCount,
        int $lastWeekAddedJobsCount,
        Collection $jobCategories
    ): MetaTagDTO {
        $topCategories = $jobCategories->take(3)->pluck('name')->implode(', ');

        $title = "Find Your Next Tech Job | {$availableJobs}+ Opportunities Available | Geezap";

        $description = "Discover your dream tech role from {$availableJobs}+ opportunities, " .
            "including {$todayAddedJobsCount} jobs added today and {$lastWeekAddedJobsCount} this week. " .
            "Browse {$jobCategoriesCount} categories including {$topCategories}. " .
            "AI-powered job matching from LinkedIn, Upwork, Indeed, and ZipRecruiter with smart cover letter generation.";

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: "job search, AI job matching, career platform, {$topCategories}, remote jobs, tech jobs, LinkedIn jobs, Upwork, Indeed, ZipRecruiter",
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'website'
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description
            )
        );
    }

    public function getJobDetailsMeta(JobListing $job): MetaTagDTO
    {
        $location = $job->is_remote ? 'Remote' : $job->city;
        $salary = ($job->min_salary && $job->max_salary)
            ? " • \${$job->min_salary}-\${$job->max_salary}/{$job->salary_period}"
            : '';

        $title = "{$job->job_title} at {$job->employer_name} • {$location}{$salary} | Geezap";

        $description = "{$job->job_title} position at {$job->employer_name}. " .
            ($job->is_remote ? "Remote position" : "Location: {$job->city}") . ". " .
            "{$job->employment_type} role" .
            ($job->min_salary ? " with salary \${$job->min_salary}-\${$job->max_salary}" : "") . ". " .
            substr(strip_tags($job->description), 0, 150) . "...";

        $image = $job->employer_logo ?? asset('assets/images/favicon.ico');

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: "{$job->job_title}, {$job->employer_name}, {$job->job_category}, {$location} jobs, tech jobs",
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'article',
                image: $image
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description,
                image: $image
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description,
                image: $image
            ),
            structuredData: new StructuredMetaDataDTO([
                '@context' => 'https://schema.org',
                '@type' => 'JobPosting',
                'title' => $job->job_title,
                'description' => strip_tags($job->description),
                'datePosted' => $job->created_at->toISO8601String(),
                'employmentType' => $job->employment_type,
                'hiringOrganization' => [
                    '@type' => 'Organization',
                    'name' => $job->employer_name,
                    'logo' => $image,
                ],
                'jobLocation' => [
                    '@type' => 'Place',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressLocality' => $job->city,
                        'addressCountry' => $job->country ?? 'US'
                    ]
                ],
                'baseSalary' => [
                    '@type' => 'MonetaryAmount',
                    'currency' => 'USD',
                    'value' => [
                        '@type' => 'QuantitativeValue',
                        'minValue' => $job->min_salary,
                        'maxValue' => $job->max_salary,
                        'unitText' => strtoupper($job->salary_period)
                    ]
                ]
            ])
        );
    }

    public function getJobsIndexMeta(Request $request, int $totalJobs): MetaTagDTO
    {
        $category = $request->get('category');
        $location = $request->get('location');
        $remoteOnly = $request->get('remote_only');
        
        $titleParts = ['Browse'];
        $descriptionParts = ['Explore'];
        $keywords = ['tech jobs', 'software jobs', 'remote work'];

        if ($category) {
            $categoryName = $this->getCategoryDisplayName($category);
            $titleParts[] = $categoryName;
            $descriptionParts[] = $categoryName;
            $keywords[] = $category . ' jobs';
            $keywords[] = $categoryName;
        } else {
            $titleParts[] = 'Tech';
        }

        $titleParts[] = 'Jobs';

        if ($location && !$remoteOnly) {
            $titleParts[] = "in {$location}";
            $descriptionParts[] = "in {$location}";
            $keywords[] = $location . ' jobs';
        } elseif ($remoteOnly) {
            $titleParts[] = "Remote";
            $descriptionParts[] = "remote";
            $keywords[] = 'remote jobs';
        }

        $title = implode(' ', $titleParts) . " | {$totalJobs}+ Opportunities | Geezap";
        
        $description = implode(' ', $descriptionParts) . " {$totalJobs}+ job opportunities from top companies. " .
            "Find full-time, contract, and remote positions with competitive salaries. " .
            "AI-powered job matching from LinkedIn, Upwork, Indeed, and ZipRecruiter.";

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: implode(', ', $keywords),
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'website',
                image: asset('assets/images/favicon.ico')
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            )
        );
    }

    public function getCategoriesMeta(Collection $categories): MetaTagDTO
    {
        $count = $categories->count();
        $topCategories = $categories->take(5)->pluck('name')->implode(', ');

        $title = "Job Categories | {$count} Tech Categories | Geezap";
        $description = "Browse {$count} technology job categories including {$topCategories}. " .
            "Find specialized roles in web development, mobile apps, data science, DevOps, and more. " .
            "Filter by technology stack and expertise level.";

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: "job categories, tech specializations, {$topCategories}, programming jobs, software development",
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'website',
                image: asset('assets/images/favicon.ico')
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            )
        );
    }

    public function getDashboardMeta(): MetaTagDTO
    {
        $title = "Dashboard | Manage Your Job Search | Geezap";
        $description = "Track your job applications, manage saved jobs, update your profile, and monitor your job search progress. " .
            "Access personalized job recommendations and AI-generated cover letters.";

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: "job dashboard, application tracking, career management, job search progress",
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'website',
                image: asset('assets/images/favicon.ico')
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            )
        );
    }

    public function getAboutMeta(): MetaTagDTO
    {
        $title = "About Geezap | AI-Powered Job Aggregation Platform";
        $description = "Learn about Geezap's mission to revolutionize job searching with AI-powered matching. " .
            "We aggregate opportunities from LinkedIn, Upwork, Indeed, and ZipRecruiter to help you find your dream tech job.";

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: "about geezap, AI job matching, job aggregation, career platform, tech recruitment",
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'website',
                image: asset('assets/images/favicon.ico')
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            )
        );
    }

    public function getContactMeta(): MetaTagDTO
    {
        $title = "Contact Us | Get Support | Geezap";
        $description = "Need help with your job search? Contact Geezap support for assistance with your account, " .
            "job applications, or platform features. We're here to help you succeed.";

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: "contact geezap, customer support, help, assistance, job search support",
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'website',
                image: asset('assets/images/favicon.ico')
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            )
        );
    }

    public function getPrivacyPolicyMeta(): MetaTagDTO
    {
        $title = "Privacy Policy | Your Data Protection | Geezap";
        $description = "Learn how Geezap protects your personal information and job search data. " .
            "Our comprehensive privacy policy explains data collection, usage, and your rights.";

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: "privacy policy, data protection, user privacy, GDPR compliance, data security",
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'website',
                image: asset('assets/images/favicon.ico')
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            )
        );
    }

    public function getTermsMeta(): MetaTagDTO
    {
        $title = "Terms of Service | User Agreement | Geezap";
        $description = "Read Geezap's terms of service and user agreement. " .
            "Understand your rights and responsibilities when using our job search platform.";

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: "terms of service, user agreement, legal terms, platform rules, user responsibilities",
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'website',
                image: asset('assets/images/favicon.ico')
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            )
        );
    }

    public function getCoverLetterMeta(): MetaTagDTO
    {
        $title = "AI Cover Letter Generator | Create Professional Cover Letters | Geezap";
        $description = "Generate personalized, professional cover letters with AI technology. " .
            "Tailor your cover letter to specific job opportunities and increase your application success rate.";

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: "AI cover letter, cover letter generator, job application, professional writing, AI writing assistant",
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'website',
                image: asset('assets/images/favicon.ico')
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            )
        );
    }

    public function getApplicationsMeta(): MetaTagDTO
    {
        $title = "My Applications | Track Job Applications | Geezap";
        $description = "View and manage all your job applications in one place. " .
            "Track application status, follow up dates, and organize your job search efficiently.";

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: "job applications, application tracking, application status, job search management",
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'website',
                image: asset('assets/images/favicon.ico')
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            )
        );
    }

    public function getProfileUpdateMeta(): MetaTagDTO
    {
        $title = "Update Profile | Manage Your Information | Geezap";
        $description = "Update your professional profile, skills, experience, and preferences. " .
            "Keep your information current to receive better job recommendations.";

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: "profile update, professional profile, skills management, career preferences",
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'website',
                image: asset('assets/images/favicon.ico')
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            )
        );
    }

    public function getPreferencesMeta(): MetaTagDTO
    {
        $title = "Job Preferences | Customize Your Job Search | Geezap";
        $description = "Set your job search preferences including location, salary range, remote work options, " .
            "and notification settings to receive personalized job recommendations.";

        return new MetaTagDTO(
            title: $title,
            description: $description,
            keywords: "job preferences, job search settings, notifications, personalization, job alerts",
            og: new OpenGraphDTO(
                title: $title,
                description: $description,
                type: 'website',
                image: asset('assets/images/favicon.ico')
            ),
            twitter: new TwitterCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            ),
            discord: new DiscordCardDTO(
                title: $title,
                description: $description,
                image: asset('assets/images/favicon.ico')
            )
        );
    }

    private function getCategoryDisplayName(string $category): string
    {
        return match($category) {
            'laravel' => 'Laravel',
            'symfony' => 'Symfony',
            'wordpress' => 'WordPress',
            'vuejs' => 'Vue.js',
            'react' => 'React',
            'angular' => 'Angular',
            'django' => 'Django',
            'flask' => 'Flask',
            'express' => 'Express.js',
            'spring' => 'Spring',
            'ruby-on-rails' => 'Ruby on Rails',
            'aspnet' => 'ASP.NET',
            'nodejs' => 'Node.js',
            'python' => 'Python',
            'php' => 'PHP',
            'java' => 'Java',
            'csharp' => 'C#',
            'ruby' => 'Ruby',
            'go' => 'Go',
            'swift' => 'Swift',
            'kotlin' => 'Kotlin',
            'rust' => 'Rust',
            'scala' => 'Scala',
            'typescript' => 'TypeScript',
            'javascript' => 'JavaScript',
            'html' => 'HTML',
            'css' => 'CSS',
            'sql' => 'SQL',
            'nosql' => 'NoSQL',
            'mongodb' => 'MongoDB',
            'mysql' => 'MySQL',
            'postgresql' => 'PostgreSQL',
            'sqlite' => 'SQLite',
            'oracle' => 'Oracle',
            'mariadb' => 'MariaDB',
            'redis' => 'Redis',
            'elasticsearch' => 'Elasticsearch',
            'kafka' => 'Apache Kafka',
            'rabbitmq' => 'RabbitMQ',
            default => ucfirst(str_replace('-', ' ', $category))
        };
    }
}
