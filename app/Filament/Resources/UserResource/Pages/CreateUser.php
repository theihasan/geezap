<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getFormActions(): array
    {
       return [
           Actions\CreateAction::make()
                ->icon('heroicon-o-user-plus')
                ->label('Add New User'),
           Actions\Action::make('cancel')
               ->label('Cancel')
               ->icon('heroicon-o-x-circle')
               ->color('danger')
               ->url(route('filament.geezap.resources.users.index')),
       ];
    }
}
