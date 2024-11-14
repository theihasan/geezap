<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Users';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(5)
                    ->schema([
                        Forms\Components\Section::make('General Information')
                            ->columnSpan(3)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('dob')
                                    ->label('Date of Birth')
                                    ->required()
                                    ->native(false),
                                Forms\Components\TextInput::make('occupation'),
                                Forms\Components\Textarea::make('bio'),
                                Forms\Components\Select::make('timezone')
                                    ->options(timezone_identifiers_list())
                                    ->searchable(),
                            ])->collapsed(),

                        Forms\Components\Section::make('Authentication Information')
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->required()
                                    ->reactive()
                                    ->revealable()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->maxLength(255),
                                Forms\Components\Select::make('role')
                                    ->options(Role::class)
                            ])->collapsed(),

                        Forms\Components\Section::make('Contact Information')
                            ->columnSpan(3)
                            ->schema([
                                Forms\Components\TextInput::make('address')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(20),
                                Forms\Components\TextInput::make('state')
                                    ->label('City'),
                                Forms\Components\TextInput::make('country'),
                            ])
                            ->collapsed()
                    ])

            ]);

    }


    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('country')
                                        ->badge()
                                        ->color(fn (string $state): string =>
                                        match ($state) {
                                            'USA' => 'success',
                                            'UK' => 'danger',
                                            'Canada' => 'warning',
                                            default => 'primary',
                                        }),
                Tables\Columns\TextColumn::make('created_at')
                                        ->label('Joined Date')
                                        ->dateTime('jS-M-Y')
                                        ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('country')
                    ->options(function () {
                        return User::query()->distinct()
                            ->whereNotNull('country')
                            ->pluck('country', 'country')
                            ->toArray();
                    })
                    ->searchable(),
                Tables\Filters\SelectFilter::make('timezone')
                    ->options(function () {
                        return User::query()->distinct()
                            ->whereNotNull('timezone')
                            ->pluck('timezone', 'timezone')
                            ->toArray();
                    })
                    ->searchable()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
