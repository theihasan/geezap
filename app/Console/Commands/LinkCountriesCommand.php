<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\JobCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LinkCountriesCommand extends Command
{
    protected $signature = 'app:link-countries';
    protected $description = 'Link all active countries to all job categories';

    private array $countryCodes = ['US', 'GB', 'CA', 'AU', 'NZ'];

    public function handle(): void
    {
        $this->info('Starting to link 5 countries to categories...');

        $countries = Country::whereIn('code', $this->countryCodes)
            ->where('is_active', true)
            ->get();

        $this->info("Found {$countries->count()} countries");

        if ($countries->count() !== 5) {
            $foundCodes = $countries->pluck('code')->join(', ');
            $this->warn("Warning: Not all required countries found. Found countries: {$foundCodes}");
            if (! $this->confirm('Do you want to continue?')) {
                return;
            }
        }

        $categories = JobCategory::all();
        $this->info("Found {$categories->count()} categories");

        $countryIds = $countries->pluck('id')->toArray();

        $this->info('Removing existing country relationships...');
        DB::table('job_category_country')->truncate();

        $this->withProgressBar($categories, function ($category) use ($countryIds) {
            $category->countries()->sync($countryIds);
        });

        $this->newLine(2);
        $this->info('Selected countries have been linked to categories');

        $this->info('Verifying links...');
        JobCategory::withCount('countries')
            ->get()
            ->each(function ($category) {
                $this->line("{$category->name}: {$category->countries_count} countries");
            });
    }

}
