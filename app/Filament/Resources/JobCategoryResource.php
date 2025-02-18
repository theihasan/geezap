<?php

namespace App\Filament\Resources;

use App\Enums\JobTimeFrame;
use App\Filament\Resources\JobCategoryResource\Pages;
use App\Filament\Resources\JobCategoryResource\RelationManagers;
use App\Models\JobCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobCategoryResource extends Resource
{
    protected static ?string $model = JobCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->hint('This is the name of the category')
                    ->hintColor('info')
                    ->hintIcon('heroicon-o-exclamation-circle')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('query_name')
                    ->hint('This is the query. Mainly it will be used to scrape the jobs')
                    ->hintColor('info')
                    ->hintIcon('heroicon-o-exclamation-circle')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('page')
                            ->numeric()
                            ->required()
                            ->hint('This is the number of pages to scrape')
                            ->hintColor('info')
                            ->hintIcon('heroicon-o-exclamation-circle')
                            ->default(1),
                        Forms\Components\TextInput::make('num_page')
                            ->numeric()
                            ->required()
                            ->hint('Number of job to get. Max value 20')
                            ->hintColor('info')
                            ->hintIcon('heroicon-o-exclamation-circle')
                            ->default(1),
                        Forms\Components\Select::make('timeframe')
                            ->native(false)
                            ->required()
                            ->options(JobTimeFrame::class),
                    ]),
                Forms\Components\Section::make('Country Configuration')
                    ->schema([
                        Forms\Components\Select::make('countries')
                            ->multiple()
                            ->relationship('countries', 'name')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->helperText('Select countries for this job category')
                    ]),

                Forms\Components\FileUpload::make('category_image')
                    ->image()
                    ->directory('category-images')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('query_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('page')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('num_page')
                    ->numeric(),
                Tables\Columns\TextColumn::make('timeframe')
                    ->numeric(),
                Tables\Columns\TextColumn::make('countries.name')
                    ->badge()
                    ->separator(',')
                    ->label('Countries'),
                Tables\Columns\ImageColumn::make('category_image')
                    ->circular(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
            ])
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
            'index' => Pages\ListJobCategories::route('/'),
            'create' => Pages\CreateJobCategory::route('/create'),
            'edit' => Pages\EditJobCategory::route('/{record}/edit'),
        ];
    }
}
