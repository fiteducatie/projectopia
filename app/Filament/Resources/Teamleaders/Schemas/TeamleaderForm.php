<?php

namespace App\Filament\Resources\Teamleaders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                        TextInput::make('name')
                            ->label('Naam')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Volledige naam van de team leider'),

                        TextInput::make('avatar_url')
                            ->label('Avatar URL')
                            ->url()
                            ->nullable()
                            ->helperText('Optioneel: URL naar een afbeelding van de team leider'),

                        Textarea::make('summary')
                            ->label('Samenvatting')
                            ->rows(3)
                            ->columnSpanFull()
                            ->nullable()
                            ->helperText('Korte samenvatting van de team leider, en zijn rol in het project.'),
                    ])
                    ->columns(2),

                Section::make('Profiel')
                    ->description('Details over de team leider')
                    ->schema([
                        Textarea::make('description')
                            ->label('Beschrijving')
                            ->rows(3)
                            ->nullable()
                            ->helperText('Korte beschrijving van de team leider, en zijn rol in het project.'),

                        Textarea::make('communication_style')
                            ->label('Communicatiestijl')
                            ->rows(3)
                            ->nullable()
                            ->helperText('Hoe communiceert deze team leider? (bijv. direct, diplomatiek, data-gedreven)'),
                    ])
                    ->columns(1),

                Section::make('Vaardigheden & Deliverables')
                    ->description('Expertise en verwachte output')
                    ->schema([
                        Textarea::make('skillset')
                            ->label('Vaardigheden')
                            ->rows(3)
                            ->nullable()
                            ->helperText('Technische en soft skills van de team leider'),

                        Textarea::make('deliverables')
                            ->label('Verwachte Deliverables')
                            ->rows(3)
                            ->nullable()
                            ->helperText('Wat vind deze team leider belangrijk voor het project? (interviews met klant, diagrammen, database, schone code, heldere prototypes, etc.)'),
                    ])
                    ->columns(1),
            ]);
    }
}
