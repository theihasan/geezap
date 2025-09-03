<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\DTO\MetaTagDTO generateMeta(?string $title = null, ?string $description = null, ?string $keywords = null, ?string $image = null, ?string $type = null, ?array $structuredData = null, array $customMeta = [])
 * @method static \App\DTO\MetaTagDTO generateHomePageMeta(int $availableJobs, int $todayAddedJobsCount, int $jobCategoriesCount, int $lastWeekAddedJobsCount, $jobCategories)
 * @method static \App\DTO\MetaTagDTO generateJobDetailMeta($job)
 * @method static \App\DTO\MetaTagDTO generateJobsIndexMeta(\Illuminate\Http\Request $request, int $totalJobs)
 * @method static \App\DTO\MetaTagDTO generateCategoriesMeta($categories)
 */
class Seo extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Services\SeoMetaService::class;
    }
}