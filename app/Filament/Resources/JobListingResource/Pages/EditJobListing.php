<?php

namespace App\Filament\Resources\JobListingResource\Pages;

use App\Filament\Resources\JobListingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobListing extends EditRecord
{
    protected static string $resource = JobListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->color('success')
                ->openUrlInNewTab()
                ->icon('heroicon-o-eye'),
            Actions\Action::make('list')
                ->color('info')
                ->icon('heroicon-o-arrow-turn-right-up')
                ->label('All Jobs')
                ->url(fn () => route('filament.geezap.resources.job-listings.index')),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-arrow-path')
                ->label('Update Job Info'),
            Actions\Action::make('cancel')
                ->label('Cancel')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->url(route('filament.geezap.resources.job-listings.index')),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['benefits'] = array_map('trim', explode(';', $data['benefits']));
        $data['qualifications'] = array_map('trim', explode(';', $data['qualifications']));
        $data['responsibilities'] = array_map('trim', explode(';', $data['responsibilities']));

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['benefits'] = implode(';', $data['benefits'] ?? []);
        $data['qualifications'] = implode(';', $data['qualifications'] ?? []);
        $data['responsibilities'] = implode(';', $data['responsibilities'] ?? []);

        return $data;
    }
}
