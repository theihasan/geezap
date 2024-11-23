<?php

namespace App\Filament\Resources\JobListingResource\Pages;

use App\Filament\Resources\JobListingResource;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;

class ViewJob extends ViewRecord
{
    protected static string $resource = JobListingResource::class;

    public  function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
               Section::make('Job Overview')
                    ->schema([
                        TextEntry::make('job_title')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->weight(FontWeight::Bold),
                        TextEntry::make('employment_type')
                            ->badge()
                            ->color('success'),
                        TextEntry::make('job_category')
                            ->badge(),
                        TextEntry::make('posted_at')
                            ->visible(fn (): bool => $this->record->posted_at !== null)
                            ->dateTime('jS M Y'),
                        TextEntry::make('expired_at')
                            ->visible(fn (): bool => $this->record->expired_at !== null)
                            ->dateTime('jS M Y'),
                    ])->columns(3),

                Section::make('Company Information')
                    ->schema([
                        ImageEntry::make('employer_logo')
                            ->visible(fn (): bool => $this->record->employer_logo !== null)
                            ->circular(),
                        TextEntry::make('employer_name')
                            ->visible(fn(): bool => $this->record->employer_name !== null),
                        TextEntry::make('employer_company_type')
                            ->visible(fn(): bool => $this->record->employer_company_type !== null),
                        TextEntry::make('employer_website')
                            ->visible(fn() : bool => $this->record->employer_website !== null),
                        TextEntry::make('publisher')
                            ->visible(fn() : bool => $this->record->publisher !== null),
                    ])->columns(3),

                Section::make('Job Description')
                    ->schema([
                        TextEntry::make('description')
                            ->visible(fn(): bool => $this->record->description !== null)
                            ->html()
                            ->columnSpanFull(),
                    ]),

                Section::make('Location')
                    ->schema([
                        TextEntry::make('is_remote')
                            ->label('Job Type')
                            ->formatStateUsing(function ($state){
                                return $state ? 'Remote' : 'On-site';
                            })
                            ->badge()
                            ->color(fn ($state) => $state ? 'success' : 'gray'),
                        TextEntry::make('city')
                            ->visible(fn(): bool => $this->record->city !== null),
                        TextEntry::make('state')
                            ->visible(fn(): bool => $this->record->state !== null),
                        TextEntry::make('country')
                            ->visible(fn(): bool => $this->record->country !== null),
                    ])->columns(4),

                Section::make('Salary & Benefits')
                    ->schema([
                        TextEntry::make('min_salary')
                            ->numeric()
                            ->prefix(fn ($record) => $record->salary_currency),
                        TextEntry::make('max_salary')
                            ->visible(fn(): bool => $this->record->max_salary !== null)
                            ->numeric()
                            ->prefix(fn ($record) => $record->salary_currency),
                        TextEntry::make('salary_period')
                            ->visible(fn(): bool => $this->record->salary_period !== null),
                        TextEntry::make('benefits')
                            ->listWithLineBreaks(),
                    ])->columns(2),

                Section::make('Requirements & Skills')
                    ->schema([
                        TextEntry::make('required_experience')
                            ->visible(fn(): bool => $this->record->required_experience !== null)
                            ->suffix(' years'),
                        TextEntry::make('qualifications')
                            ->visible(fn(): bool => $this->record->qualifications !== null)
                            ->listWithLineBreaks(),
                        TextEntry::make('responsibilities')
                            ->visible(fn(): bool => $this->record->responsibilities !== null)
                            ->listWithLineBreaks(),
                        TextEntry::make('skills')
                            ->visible(fn(): bool => $this->record->skills !== null)
                            ->listWithLineBreaks(),
                    ])->columns(2),

                Section::make('Application Links')
                    ->schema([
                        TextEntry::make('apply_link')
                            ->visible(fn(): bool => $this->record->apply_link !== null),
                        TextEntry::make('google_link')
                            ->visible(fn(): bool => $this->record->google_link !== null),
                    ])->columns(2),
            ]);
    }

}
