<?php

namespace App\Filament\Resources\ApiKeyResource\Pages;

use App\Filament\Resources\ApiKeyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApiKey extends EditRecord
{
    protected static string $resource = ApiKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('list')
                ->color('info')
                ->icon('heroicon-o-arrow-turn-right-up')
                ->label('Back to List')
                ->url(fn () => route('filament.geezap.resources.api-keys.index')),
            Actions\DeleteAction::make(),
        ];
    }
}
