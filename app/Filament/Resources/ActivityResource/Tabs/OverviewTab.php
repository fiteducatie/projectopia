<?php

namespace App\Filament\Resources\ActivityResource\Tabs;

use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Section;

class OverviewTab
{
    public static function make(): array
    {
        return [
            Section::make()
                ->description('Basis activiteit informatie')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('banner')
                        ->label('Activiteit afbeelding')
                        ->image()
                        ->imageEditor()
                        ->collection('banner'),
                    Forms\Components\TextInput::make('name')->label('Naam')
                        ->helperText('Korte, herkenbare activiteit naam.')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('domain')->label('Domein')
                        ->options([
                            'software' => 'Software',
                            'marketing' => 'Marketing',
                            'event' => 'Evenement',
                        ])->required()->default('software')
                        ->helperText('Bepaalt de standaardtemplates (Software, Marketing, Evenement).'),
                    Forms\Components\Select::make('difficulty')->label('Complexiteit')
                        ->options([
                            'laag' => 'Laag',
                            'middel' => 'Middel',
                            'hoog' => 'Hoog',
                        ])->required()->default('middel')
                        ->helperText('Bepaalt de complexiteit van de activiteit.'),
                    Forms\Components\Select::make('status')->label('Status')
                        ->options([
                            'open' => 'Open',
                            'closed' => 'Gesloten',
                        ])->required()->default('open')
                        ->helperText('Gesloten activiteiten hebben geen actieve chat functionaliteit.'),
                ]),
            Section::make()
                ->description('Beschrijf wat er gebouwd moet worden, voor wie en waarom. Dit vormt de basis voor alle beslissingen.')
                ->schema([
                    Forms\Components\RichEditor::make('context')->label('Context')
                        ->helperText('1–3 alinea\'s met achtergrond, doelgroep en gewenste impact.'),
                ]),
            Section::make()
                ->description('Welke meetbare doelen moet de activiteit bereiken? Denk aan KPI\'s of leerdoelen.')
                ->schema([
                    Forms\Components\RichEditor::make('objectives')->label('Doelstellingen')
                        ->helperText('Gebruik opsommingen; maak doelen concreet en toetsbaar.'),
                ]),
            Section::make()
                ->description('Beperkingen zoals tijd, budget, techniek, compliance of scope-afbakening.')
                ->schema([
                    Forms\Components\RichEditor::make('constraints')->label('Randvoorwaarden')
                        ->helperText('Som de belangrijkste beperkingen op; dit helpt bij prioriteren.'),
                ]),
        ];
    }
}

