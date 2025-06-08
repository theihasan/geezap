<?php

namespace App\Filament\Resources;

use App\Enums\ApiName;
use App\Filament\Resources\ApiKeyResource\Pages;
use App\Filament\Resources\ApiKeyResource\RelationManagers;
use App\Models\ApiKey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApiKeyResource extends Resource
{
    protected static ?string $model = ApiKey::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('api_key')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('api_secret')
                            ->maxLength(255),
                        Forms\Components\Select::make('api_name')
                            ->default(ApiName::JOB->value)
                            ->options(ApiName::class)
                            ->required(),
                        Forms\Components\TextInput::make('request_remaining')
                            ->required()
                            ->numeric()
                            ->default(100),
                    ])
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('api_key'),
                Tables\Columns\TextColumn::make('rate_limit_reset'),
                Tables\Columns\TextColumn::make('api_name')
                    ->formatStateUsing(function ($state){
                        return ucfirst($state);
                    }),
                Tables\Columns\TextColumn::make('request_remaining')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sent_request')
                    ->label('Sent Request'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('jS M Y'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('jS M Y'),
            ])
            ->searchable()
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApiKeys::route('/'),
            'create' => Pages\CreateApiKey::route('/create'),
            'edit' => Pages\EditApiKey::route('/{record}/edit'),
        ];
    }
}
