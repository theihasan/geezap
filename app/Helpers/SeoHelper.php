<?php

if (!function_exists('seo_meta')) {
    /**
     * Generate SEO meta tags for the current page
     */
    function seo_meta(?string $title = null, ?string $description = null, ?string $keywords = null, ?string $image = null): \App\DTO\MetaTagDTO
    {
        return app(\App\Services\SeoMetaService::class)->generateMeta($title, $description, $keywords, $image);
    }
}

if (!function_exists('seo_job_meta')) {
    /**
     * Generate SEO meta tags for job listing pages
     */
    function seo_job_meta($job): \App\DTO\MetaTagDTO
    {
        return app(\App\Services\SeoMetaService::class)->generateJobDetailMeta($job);
    }
}

if (!function_exists('seo_jobs_index_meta')) {
    /**
     * Generate SEO meta tags for jobs index pages
     */
    function seo_jobs_index_meta(\Illuminate\Http\Request $request, int $totalJobs): \App\DTO\MetaTagDTO
    {
        return app(\App\Services\SeoMetaService::class)->generateJobsIndexMeta($request, $totalJobs);
    }
}

if (!function_exists('seo_home_meta')) {
    /**
     * Generate SEO meta tags for homepage
     */
    function seo_home_meta(int $availableJobs, int $todayAddedJobsCount, int $jobCategoriesCount, int $lastWeekAddedJobsCount, $jobCategories): \App\DTO\MetaTagDTO
    {
        return app(\App\Services\SeoMetaService::class)->generateHomePageMeta($availableJobs, $todayAddedJobsCount, $jobCategoriesCount, $lastWeekAddedJobsCount, $jobCategories);
    }
}