<?php

namespace App\Filament\Resources;

use App\Enums\JobType;
use App\Filament\Resources\JobListingResource\Pages;
use App\Filament\Resources\JobListingResource\RelationManagers;
use App\Models\JobCategory;
use App\Models\JobListing;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobListingResource extends Resource
{
    protected static ?string $model = JobListing::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Wizard::make([
                Forms\Components\Wizard\Step::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('job_title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('employment_type')
                            ->options(JobType::class)
                            ->required(),
                        Forms\Components\Select::make('job_category')
                            ->options(JobCategory::getAllCategories()->pluck('name', 'id')->toArray())
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Wizard\Step::make('Employer Details')
                    ->schema([
                        Forms\Components\TextInput::make('employer_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('employer_logo')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('employer_website')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('employer_company_type')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('publisher')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Wizard\Step::make('Location & Links')
                    ->schema([
                        Forms\Components\Toggle::make('is_remote')
                            ->default(false),
                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('state')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('country')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('latitude')
                            ->numeric()
                            ->step(0.000001)
                            ->placeholder('e.g., 41.88325')
                            ->helperText('Job location latitude coordinate'),
                        Forms\Components\TextInput::make('longitude')
                            ->numeric()
                            ->step(0.000001)
                            ->placeholder('e.g., -87.6323879')
                            ->helperText('Job location longitude coordinate'),
                        Forms\Components\TextInput::make('apply_link')
                            ->url()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('google_link')
                            ->url()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Wizard\Step::make('Compensation')
                    ->schema([
                        Forms\Components\TextInput::make('min_salary')
                            ->numeric(),
                        Forms\Components\TextInput::make('max_salary')
                            ->numeric(),
                        Forms\Components\TextInput::make('salary_currency')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('salary_period'),
                        Forms\Components\TagsInput::make('benefits')
                            ->separator(';'),
                    ])->columns(2),

                Forms\Components\Wizard\Step::make('Requirements & Timeline')
                    ->schema([
                        Forms\Components\TagsInput::make('qualifications')
                            ->separator(';'),
                        Forms\Components\TagsInput::make('responsibilities')
                            ->separator(';'),
                        Forms\Components\TextInput::make('required_experience')
                            ->numeric()
                            ->suffix('month'),
                        Forms\Components\TagsInput::make('skills')
                            ->separator(';'),
                        Forms\Components\DateTimePicker::make('posted_at')
                            ->native(false)
                            ->required(),
                        Forms\Components\DateTimePicker::make('expired_at')
                            ->native(false),
                    ])->columns(2),
            ])->skippable()
        ])->columns(1);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('job_title')
                    ->limit(50),
                Tables\Columns\TextColumn::make('publisher')
                    ->badge()
                    ->color('success')
                    ->label('Source'),
                Tables\Columns\TextColumn::make('employment_type')
                    ->label('Type')
                    ->color('info'),
                Tables\Columns\IconColumn::make('has_coordinates')
                    ->label('Map')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !empty($record->latitude) && !empty($record->longitude))
                    ->trueIcon('heroicon-o-map')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('posted_at')
                    ->label('Posted Date')
                    ->sortable()
                    ->dateTime('jS M Y'),
                Tables\Columns\TextColumn::make('expired_at')
                    ->label('Expired Date')
                    ->sortable()
                    ->dateTime('jS M Y')
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('publisher')
                    ->label('Source')
                    ->options(fn () => JobListing::distinct()
                        ->pluck('publisher', 'publisher')
                        ->toArray())
                    ->multiple(),
                Tables\Filters\TernaryFilter::make('is_remote')
                    ->label('Remote Only')
                    ->default(false)
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('view')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->url(function ($record) {
                            return route('job.show', $record->slug);
                        })
                        ->openUrlInNewTab(),

                    //Did not decided
                    //Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                ])
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
            'index' => Pages\ListJobListings::route('/'),
            'create' => Pages\CreateJobListing::route('/create'),
            'edit' => Pages\EditJobListing::route('/{record}/edit'),
            'view' => Pages\ViewJob::route('/{record}'),
        ];
    }

}
