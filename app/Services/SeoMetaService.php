<?php

namespace App\Services;

use App\DTO\MetaTagDTO;
use App\DTO\OpenGraphDTO;
use App\DTO\TwitterCardDTO;
use App\DTO\DiscordCardDTO;
use App\DTO\StructuredMetaDataDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class SeoMetaService
{
    private array $config;
    private Request $request;

    public function __construct(Request $request)
    {
        $this->config = config('seo', []);
        $this->request = $request;
    }

    public function generateMeta(
        ?string $title = null,
        ?string $description = null,
        ?string $keywords = null,
        ?string $image = null,
        ?string $type = null,
        ?array $structuredData = null,
        array $customMeta = []
    ): MetaTagDTO {
        $routeName = $this->request->route()?->getName();
        $routeConfig = $this->getRouteConfig($routeName);

        // Build title with proper formatting
        $finalTitle = $this->buildTitle(
            $title ?? $routeConfig['title'] ?? $this->config['defaults']['title']
        );

        // Build description
        $finalDescription = $description 
            ?? $routeConfig['description'] 
            ?? $this->config['defaults']['description'];

        // Build keywords
        $finalKeywords = $this->buildKeywords(
            $keywords ?? $routeConfig['keywords'] ?? $this->config['defaults']['keywords']
        );

        // Determine image
        $finalImage = $this->resolveImage($image);

        // Determine type
        $finalType = $type ?? $this->config['open_graph']['type'] ?? 'website';

        // Build canonical URL
        $canonicalUrl = $this->buildCanonicalUrl();

        return new MetaTagDTO(
            title: $finalTitle,
            description: Str::limit($finalDescription, 155),
            keywords: $finalKeywords,
            og: $this->buildOpenGraph($finalTitle, $finalDescription, $finalType, $finalImage, $canonicalUrl, $customMeta),
            twitter: $this->buildTwitterCard($finalTitle, $finalDescription, $finalImage, $customMeta),
            discord: $this->buildDiscordCard($finalTitle, $finalDescription, $finalImage, $customMeta),
            structuredData: $structuredData ? new StructuredMetaDataDTO($structuredData) : $this->buildDefaultStructuredData($finalTitle, $finalDescription),
            robots: $routeConfig['robots'] ?? $this->config['defaults']['robots'] ?? 'index,follow',
            canonical: $canonicalUrl,
            author: $this->config['defaults']['author'] ?? null
        );
    }

    public function generateHomePageMeta(
        int $availableJobs,
        int $todayAddedJobsCount,
        int $jobCategoriesCount,
        int $lastWeekAddedJobsCount,
        $jobCategories
    ): MetaTagDTO {
        $topCategories = $jobCategories->take(3)->pluck('name')->implode(', ');

        $title = "Find Your Next Tech Job | {$availableJobs}+ Opportunities Available";
        
        $description = "Discover your dream tech role from {$availableJobs}+ opportunities, " .
            "including {$todayAddedJobsCount} jobs added today and {$lastWeekAddedJobsCount} this week. " .
            "Browse {$jobCategoriesCount} categories including {$topCategories}. " .
            "AI-powered job matching from LinkedIn, Upwork, Indeed, and ZipRecruiter with smart cover letter generation.";

        $keywords = "job search, AI job matching, career platform, {$topCategories}, remote jobs, tech jobs, LinkedIn jobs, Upwork, Indeed, ZipRecruiter";

        $structuredData = array_merge(
            $this->config['structured_data']['website'] ?? [],
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => $this->config['defaults']['title'],
                'url' => URL::to('/'),
                'description' => $description,
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => URL::to('/jobs?search={search_term_string}'),
                    'query-input' => 'required name=search_term_string',
                ],
            ]
        );

        return $this->generateMeta(
            title: $title,
            description: $description,
            keywords: $keywords,
            structuredData: $structuredData
        );
    }

    public function generateJobDetailMeta($job): MetaTagDTO
    {
        $location = $job->is_remote ? 'Remote' : $job->city;
        $salary = ($job->min_salary && $job->max_salary)
            ? " • \${$job->min_salary}-\${$job->max_salary}/{$job->salary_period}"
            : '';

        $title = "{$job->job_title} at {$job->employer_name} • {$location}{$salary}";
        
        $description = "{$job->job_title} position at {$job->employer_name}. " .
            ($job->is_remote ? "Remote position" : "Location: {$job->city}") . ". " .
            "{$job->employment_type} role" .
            ($job->min_salary ? " with salary \${$job->min_salary}-\${$job->max_salary}" : "") . ". " .
            Str::limit(strip_tags($job->description), 100);

        $keywords = "{$job->job_title}, {$job->employer_name}, {$job->job_category}, {$location} jobs, tech jobs";

        $image = $job->employer_logo ?? $this->resolveImage();

        $structuredData = [
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
        ];

        if ($job->min_salary && $job->max_salary) {
            $structuredData['baseSalary'] = [
                '@type' => 'MonetaryAmount',
                'currency' => 'USD',
                'value' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => $job->min_salary,
                    'maxValue' => $job->max_salary,
                    'unitText' => strtoupper($job->salary_period)
                ]
            ];
        }

        return $this->generateMeta(
            title: $title,
            description: $description,
            keywords: $keywords,
            image: $image,
            type: 'article',
            structuredData: $structuredData
        );
    }

    public function generateJobsIndexMeta(Request $request, int $totalJobs): MetaTagDTO
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

        $title = implode(' ', $titleParts) . " | {$totalJobs}+ Opportunities";
        
        $description = implode(' ', $descriptionParts) . " {$totalJobs}+ job opportunities from top companies. " .
            "Find full-time, contract, and remote positions with competitive salaries. " .
            "AI-powered job matching from LinkedIn, Upwork, Indeed, and ZipRecruiter.";

        return $this->generateMeta(
            title: $title,
            description: $description,
            keywords: implode(', ', $keywords)
        );
    }

    public function generateCategoriesMeta($categories): MetaTagDTO
    {
        $count = $categories->count();
        $topCategories = $categories->take(5)->pluck('name')->implode(', ');

        $title = "Job Categories | {$count} Tech Categories";
        $description = "Browse {$count} technology job categories including {$topCategories}. " .
            "Find specialized roles in web development, mobile apps, data science, DevOps, and more. " .
            "Filter by technology stack and expertise level.";

        return $this->generateMeta(
            title: $title,
            description: $description,
            keywords: "job categories, tech specializations, {$topCategories}, programming jobs, software development"
        );
    }

    private function buildTitle(string $title): string
    {
        $separator = $this->config['defaults']['title_separator'] ?? ' | ';
        $siteName = $this->config['defaults']['title'] ?? config('app.name');

        // If title already contains site name, don't duplicate
        if (Str::contains($title, $siteName)) {
            return $title;
        }

        return $title . $separator . $siteName;
    }

    private function buildKeywords(string $keywords): string
    {
        // Add default keywords if not present
        $keywordArray = array_map('trim', explode(',', $keywords));
        $defaultKeywords = array_map('trim', explode(',', $this->config['defaults']['keywords'] ?? ''));
        
        $allKeywords = array_unique(array_merge($keywordArray, $defaultKeywords));
        return implode(', ', array_filter($allKeywords));
    }

    private function resolveImage(?string $image = null): string
    {
        if ($image) {
            return Str::startsWith($image, ['http://', 'https://']) ? $image : asset($image);
        }

        $defaultImage = $this->config['images']['default'] ?? null;
        if ($defaultImage && file_exists(public_path($defaultImage))) {
            return asset($defaultImage);
        }

        $fallbackImage = $this->config['images']['fallback'] ?? '/assets/images/favicon.ico';
        return asset($fallbackImage);
    }

    private function buildCanonicalUrl(): string
    {
        return $this->config['defaults']['canonical_url'] ?? $this->request->url();
    }

    private function buildOpenGraph(
        string $title, 
        string $description, 
        string $type, 
        string $image,
        string $url,
        array $customMeta = []
    ): OpenGraphDTO {
        return new OpenGraphDTO(
            title: $title,
            description: $description,
            type: $type,
            image: $image,
            url: $url,
            siteName: $this->config['open_graph']['site_name'] ?? config('app.name'),
            locale: $this->config['open_graph']['locale'] ?? 'en_US',
            imageWidth: $this->config['images']['width'] ?? 1200,
            imageHeight: $this->config['images']['height'] ?? 630,
            imageAlt: $title
        );
    }

    private function buildTwitterCard(
        string $title, 
        string $description, 
        string $image,
        array $customMeta = []
    ): TwitterCardDTO {
        return new TwitterCardDTO(
            title: $title,
            description: $description,
            image: $image,
            card: $this->config['twitter']['card'] ?? 'summary_large_image',
            site: $this->config['twitter']['site'] ?? null,
            creator: $this->config['twitter']['creator'] ?? null,
            imageAlt: $title
        );
    }

    private function buildDiscordCard(
        string $title, 
        string $description, 
        string $image,
        array $customMeta = []
    ): DiscordCardDTO {
        return new DiscordCardDTO(
            title: $title,
            description: $description,
            image: $image
        );
    }

    private function buildDefaultStructuredData(string $title, string $description): StructuredMetaDataDTO
    {
        $data = array_merge(
            $this->config['structured_data']['organization'] ?? [],
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => $title,
                'description' => $description,
                'url' => $this->request->url(),
            ]
        );

        return new StructuredMetaDataDTO($data);
    }

    private function getRouteConfig(?string $routeName): array
    {
        if (!$routeName || !isset($this->config['routes'][$routeName])) {
            return [];
        }

        return $this->config['routes'][$routeName];
    }

    private function getCategoryDisplayName(string $category): string
    {
        $categoryMap = [
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
        ];

        return $categoryMap[$category] ?? ucfirst(str_replace('-', ' ', $category));
    }
}