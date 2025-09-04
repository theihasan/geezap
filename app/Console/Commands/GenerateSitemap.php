<?php

namespace App\Console\Commands;

use App\Models\JobListing;
use App\Models\JobCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate XML sitemap for Geezap Job Aggregator';

    public function handle()
    {
        $sitemap = $this->generateSitemap();
        
        Storage::disk('public')->put('../sitemap.xml', $sitemap);
        
        $this->info('Sitemap generated successfully!');
    }

    private function generateSitemap(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n";
        $xml .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' . "\n";
        $xml .= '                            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n\n";

        // Static pages
        $staticPages = [
            ['url' => '', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => 'about', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => 'contact', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => 'privacy-policy', 'priority' => '0.4', 'changefreq' => 'yearly'],
            ['url' => 'terms', 'priority' => '0.4', 'changefreq' => 'yearly'],
            ['url' => 'jobs', 'priority' => '0.9', 'changefreq' => 'hourly'],
            ['url' => 'categories', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => 'job-preferences', 'priority' => '0.7', 'changefreq' => 'weekly'],
        ];

        foreach ($staticPages as $page) {
            $xml .= "    <url>\n";
            $xml .= "        <loc>" . config('app.url') . '/' . $page['url'] . "</loc>\n";
            $xml .= "        <lastmod>" . now()->toDateString() . "</lastmod>\n";
            $xml .= "        <changefreq>" . $page['changefreq'] . "</changefreq>\n";
            $xml .= "        <priority>" . $page['priority'] . "</priority>\n";
            $xml .= "    </url>\n\n";
        }

        // Job categories
        $categories = JobCategory::withCount('jobs')
            ->where('jobs_count', '>', 0)
            ->get();

        foreach ($categories as $category) {
            $xml .= "    <url>\n";
            $xml .= "        <loc>" . config('app.url') . '/categories/' . $category->slug . "</loc>\n";
            $xml .= "        <lastmod>" . $category->updated_at->toDateString() . "</lastmod>\n";
            $xml .= "        <changefreq>daily</changefreq>\n";
            $xml .= "        <priority>0.7</priority>\n";
            $xml .= "    </url>\n\n";
        }

        // Recent job listings (last 14 days)
        $jobs = JobListing::where('created_at', '>=', now()->subDays(14))
            ->where('expired_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->limit(1000)
            ->get();

        foreach ($jobs as $job) {
            $xml .= "    <url>\n";
            $xml .= "        <loc>" . config('app.url') . '/jobs/' . $job->job_id . "</loc>\n";
            $xml .= "        <lastmod>" . $job->updated_at->toDateString() . "</lastmod>\n";
            $xml .= "        <changefreq>weekly</changefreq>\n";
            $xml .= "        <priority>0.8</priority>\n";
            $xml .= "    </url>\n\n";
        }

        $xml .= "</urlset>\n";

        return $xml;
    }
}