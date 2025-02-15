<?php

namespace App\Filament\Resources\JobListingResource\Pages;

use App\Filament\Resources\JobListingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJobListing extends CreateRecord
{
    protected static string $resource = JobListingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['benefits'] = array_map('trim', explode(';', $data['benefits']));
        $data['qualifications'] = array_map('trim', explode(';', $data['qualifications']));
        $data['responsibilities'] = array_map('trim', explode(';', $data['responsibilities']));

        return $data;
    }

}
