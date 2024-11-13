<?php

namespace App\Filament\Resources\JobListingResource\Pages;

use App\Filament\Resources\JobListingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJobListing extends CreateRecord
{
    protected static string $resource = JobListingResource::class;
}
