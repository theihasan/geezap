<?php

namespace App\Filament\Resources\JobCategoryResource\Pages;

use App\Filament\Resources\JobCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobCategory extends EditRecord
{
    protected static string $resource = JobCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
