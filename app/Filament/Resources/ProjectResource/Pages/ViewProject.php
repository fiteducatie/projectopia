<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Dom\Text;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Infolists\Components\CodeEntry;
use Phiki\Grammar\Grammar;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Bewerken'),
            Action::make('view-guest')
                ->label('Bekijk als gast')
                ->color('gray')
                ->url(fn () => route('choose.project', $this->record))
                ->openUrlInNewTab(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                InfoSection::make('Overzicht')
                    ->description('Context, doelen en tijdlijn in één oogopslag.')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('name')->label('Naam')->weight('bold')->size('xl'),
                                TextEntry::make('domain')->label('Domein')->badge()->columnSpan(1),
                                TextEntry::make('difficulty')->label('Moeilijkheid')->badge()->color('warning')->columnSpan(1),
                            ])->columnSpanFull(),
                        InfoSection::make('Context')
                            ->schema([
                                TextEntry::make('context')->markdown()->hiddenLabel(),
                            ])->collapsible()->collapsed(),
                        InfoSection::make('Doelstellingen')
                            ->schema([
                                TextEntry::make('objectives')->markdown()->hiddenLabel(),
                            ])->collapsible()->collapsed(),
                        InfoSection::make('Randvoorwaarden')
                            ->schema([
                                TextEntry::make('constraints')->markdown()->hiddenLabel(),
                            ])->collapsible()->collapsed(),
                        ]),

                InfoSection::make('Persona’s')
                    ->description('Belangrijkste stakeholders en doelgroepen')
                    ->schema([
                        RepeatableEntry::make('personas')
                            ->schema([
                                Grid::make(5)
                                    ->schema([
                                        ImageEntry::make('avatar_url')->circular()->height(56)->columnSpan(1),
                                        TextEntry::make('role')->label('Rol')->weight('semibold')->columnSpan(2),
                                        TextEntry::make('name')->label('Naam')->columnSpan(2),
                                        TextEntry::make('goals')->label('Doelen')->columnSpanFull()->hint('Wat wil deze persona bereiken?'),
                                        TextEntry::make('traits')->label('Eigenschappen')->columnSpan(3),
                                        TextEntry::make('communication_style')->label('Communicatiestijl')->columnSpan(2),
                                    ]),
                            ])
                            ->columns(1),
                    ])->collapsed(),

                InfoSection::make('User Stories')
                    ->columnSpanFull()
                    ->description('Gedetailleerde beschrijvingen van functionaliteiten vanuit gebruikersperspectief.')
                    ->schema([
                        //TODO: not yet found out how to make a repeatable entry collapsible

                        RepeatableEntry::make('userStories')
                            ->label('')
                            ->schema([
                                TextEntry::make('user_story')->label('User Story')->weight('semibold')->columnSpanFull()
                                    ->limitList(1),
                                TextEntry::make('acceptance_criteria')->label('Acceptatiecriteria')
                                    ->listWithLineBreaks()
                                    ->formatStateUsing(function ($state) {

                                        return collect($state)->map(function ($criteria) {
                                            return '✔️ ' . $criteria;
                                        })->implode("\n");
                                    }),
                                TextEntry::make('mvp')
                                    ->badge()
                                    ->formatStateUsing(fn ($state) => $state ? 'MVP' : 'NTH')
                                    ->color(fn ($state) => $state ? 'success' : 'secondary')
                                    ->columnSpan(1),
                                TextEntry::make('priority')->label('Prioriteit')->badge()->color(fn ($state) => match ($state) {
                                    'high' => 'danger',
                                    'medium' => 'warning',
                                    'low' => 'success',
                                    default => 'secondary',
                                })->columnSpan(1),
                            ])
                    ])->collapsible(true)->collapsed(),

                InfoSection::make('Bijlagen')
                    ->description('Relevante documenten en bestanden met extra details')
                    ->schema([
                        RepeatableEntry::make('attachments')
                            ->getStateUsing(fn () => $this->record->getAttachmentMetadata())
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        ImageEntry::make('url')
                                            ->label('Preview')
                                            ->height(120)
                                            ->width(160)
                                            ->columnSpan(1)
                                            ->visible(fn ($state) => str_starts_with($state, 'http')),

                                        TextEntry::make('name')
                                            ->label('Bestandsnaam')
                                            ->weight('semibold')
                                            ->columnSpan(2),

                                        TextEntry::make('file_name')
                                            ->label('Originele naam')
                                            ->color('gray')
                                            ->columnSpan(1),

                                        TextEntry::make('description')
                                            ->label('Beschrijving')
                                            ->columnSpanFull()
                                            ->placeholder('Geen beschrijving beschikbaar')
                                            ->color(fn ($state) => $state ? null : 'gray'),

                                        TextEntry::make('mime_type')
                                            ->label('Type')
                                            ->badge()
                                            ->color('secondary')
                                            ->columnSpan(1),

                                        TextEntry::make('size')
                                            ->label('Grootte')
                                            ->formatStateUsing(fn ($state) => $this->formatFileSize($state))
                                            ->color('gray')
                                            ->columnSpan(1),

                                        TextEntry::make('persona_names')
                                            ->label('Relevante Persona\'s')
                                            ->formatStateUsing(function ($state) {
                                                if (!$state) {
                                                    return 'Geen persona\'s geselecteerd';
                                                }

                                                // Ensure $state is an array
                                                if (is_string($state)) {
                                                    return $state;
                                                }

                                                if (is_array($state)) {
                                                    return implode(', ', $state);
                                                }

                                                return 'Geen persona\'s geselecteerd';
                                            })
                                            ->color(fn ($state) => $state ? null : 'gray')
                                            ->columnSpan(2),
                                    ]),
                            ])
                            ->columns(1)

                    ])->collapsible(true)->collapsed(),
            ]);
    }

    protected function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}


