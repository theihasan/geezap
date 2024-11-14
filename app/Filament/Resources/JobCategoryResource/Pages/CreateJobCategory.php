<?php

namespace App\Filament\Resources\JobCategoryResource\Pages;

use App\Filament\Resources\JobCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJobCategory extends CreateRecord
{
    protected static string $resource = JobCategoryResource::class;
}
