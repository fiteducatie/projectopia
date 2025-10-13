<?php

namespace App\Filament\Resources\Teamleaders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamleaderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Basis Informatie')
                    ->description('Algemene informatie over de team leider')
                    ->schema([
                        TextEntry::make('team_leader_help')
                            ->label('Team Leider')
                            ->state('Volledige naam van de teamleider.')
                            ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                        TextInput::make('name')
                            ->hiddenLabel()
                            ->required()
                            ->maxLength(255),

                        TextEntry::make('avatar_url_help')
                            ->label('Avatar URL')
                            ->state('Optioneel: URL naar een afbeelding van de team leider.')
                            ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                        TextInput::make('avatar_url')
                            ->hiddenLabel()
                            ->url()
                            ->nullable(),

                        TextEntry::make('summary_help')
                            ->label('Samenvatting')
                            ->state('Korte samenvatting van de team leider en zijn rol in het project.')
                            ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                        Textarea::make('summary')
                            ->hiddenLabel()
                            ->rows(3)
                            ->columnSpanFull()
                            ->nullable()
                    ])
                    ->columns(2),

                Section::make('Profiel')
                    ->description('Details over de team leider')
                    ->schema([
                        TextEntry::make('description_help')
                            ->label('Beschrijving')
                            ->state('Korte beschrijving van de team leider, en zijn rol in het project.')
                            ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                        Textarea::make('description')
                            ->hiddenLabel()
                            ->rows(3)
                            ->nullable(),

                        TextEntry::make('communication_style_help')
                            ->label('Communicatiestijl')
                            ->state('Hoe communiceert deze team leider? (bijv. direct, diplomatiek, data-gedreven)')
                            ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                        Textarea::make('communication_style')
                            ->hiddenLabel()
                            ->rows(3)
                            ->nullable(),
                    ])
                    ->columns(1),

                Section::make('Vaardigheden & Deliverables')
                    ->description('Expertise en verwachte output')
                    ->schema([
                        TextEntry::make('skillset_help')
                            ->label('Vaardigheden')
                            ->state('Technische en softskills van de teamleider?')
                            ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                        Textarea::make('skillset')
                            ->hiddenLabel()
                            ->rows(3)
                            ->nullable(),

                        TextEntry::make('deliverables_help')
                            ->label('Deliverables')
                            ->state('Wat vind deze team leider belangrijk voor het project? (interviews met klant, diagrammen, database, schone code, heldere prototypes, etc.)')
                            ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                        Textarea::make('deliverables')
                            ->hiddenLabel()
                            ->rows(3)
                            ->nullable()
                    ])
                    ->columns(1),
            ]);
    }
}
