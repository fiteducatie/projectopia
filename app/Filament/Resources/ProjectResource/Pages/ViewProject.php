<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Bewerken'),
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
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('context')->label('Context')->markdown()->columnSpan(2),
                                Grid::make(1)->schema([
                                    TextEntry::make('start_date')->label('Start')->date(),
                                    TextEntry::make('end_date')->label('Einde')->date(),
                                ])->columnSpan(1),
                            ])->columnSpanFull(),
                        InfoSection::make('Doelstellingen')
                            ->schema([
                                TextEntry::make('objectives')->label('Doelstellingen')->markdown(),
                            ])->collapsible(),
                        InfoSection::make('Randvoorwaarden')
                            ->schema([
                                TextEntry::make('constraints')->label('Randvoorwaarden')->markdown(),
                            ])->collapsible(),
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
                    ])->collapsed(false),
            ]);
    }
}


