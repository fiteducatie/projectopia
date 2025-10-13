<?php

namespace App\Filament\Resources\ActivityResource\Tabs;

use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class TeamLeadersTab
{
    public static function make(): array
    {
        return [
            Section::make()
                ->description('Selecteer teamleiders voor deze activiteit.')
                ->schema([
                    TextEntry::make('team_leaders_help')
                        ->label('Teamleiders')
                        ->state('Selecteer een of meer teamleiders die betrokken zijn bij deze activiteit.')
                        ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                    Forms\Components\Select::make('team_leader_ids')
                        ->hiddenLabel()
                        ->multiple()
                        ->placeholder('Selecteer teamleiders')
                        ->relationship('teamLeaders', 'name')
                        ->preload()
                        ->searchable()
                        ->createOptionForm([
                            //basis informatie
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('team_leader_help')
                                        ->label('Teamleider')
                                        ->state('Volledige naam van de teamleider.')
                                        ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                                    TextInput::make('name')
                                        ->hiddenLabel()
                                        ->required()
                                        ->maxLength(255),

                                    TextEntry::make('avatar_url_help')
                                        ->label('Avatar URL')
                                        ->state('Optioneel: URL naar een afbeelding van de teamleider.')
                                        ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                                    TextInput::make('avatar_url')
                                        ->hiddenLabel()
                                        ->url()
                                        ->nullable(),
                                ]),


                        //profiel
                            TextEntry::make('summary_help')
                                ->label('Samenvatting')
                                ->state('Korte samenvatting van de teamleider en zijn rol in het project.')
                                ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                            Textarea::make('summary')
                                ->hiddenLabel()
                                ->rows(2)
                                ->columnSpanFull()
                                ->nullable(),

                            TextEntry::make('description_help')
                                ->label('Beschrijving')
                                ->state('Korte beschrijving van de teamleider en zijn rol in het project.')
                                ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                            Textarea::make('description')
                                ->hiddenLabel()
                                ->rows(2)
                                ->nullable(),

                            TextEntry::make('communication_style_help')
                                ->label('Communicatiestijl')
                                ->state('Hoe communiceert deze teamleider? (bijv. direct, diplomatiek, data-gedreven)')
                                ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                            Textarea::make('communication_style')
                                ->hiddenLabel()
                                ->rows(1)
                                ->nullable(),

                            //Vaardigheden & Deliverables
                            TextEntry::make('skillset_help')
                                ->label('Vaardigheden')
                                ->state('Technische en softskills van de teamleider?')
                                ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                            Textarea::make('skillset')
                                ->hiddenLabel()
                                ->rows(2)
                                ->nullable(),

                            TextEntry::make('deliverables_help')
                                ->label('Deliverables')
                                ->state('Wat vind deze teamleider belangrijk voor het project? (interviews met klant, diagrammen, database, schone code, heldere prototypes, etc.)')
                                ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                            Textarea::make('deliverables')
                                ->hiddenLabel()
                                ->rows(2)
                                ->nullable()

                        ])
                ]),
        ];
    }
}

