<?php

namespace App\Filament\Resources\ProjectResource\Tabs;

use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;

class PersonasTab
{
    public static function make(): array
    {
        return [
            Section::make()
                ->description('Voeg belangrijke stakeholders of doelgroepen toe. Ze worden gebruikt om mee te communiceren om meer van het project te weten te komen.')
                ->schema([
                    Repeater::make('personas_data')
                        ->relationship('personas')
                        ->itemLabel(fn(array $state): string => trim(($state['role'] ?? 'Rol') . ': ' . ($state['name'] ?? 'Naam')))
                        ->label('Persona\'s')
                        ->helperText('Minimaal 1 persona is aan te raden: Klant, Product Owner of Doelgroep.')
                        ->schema([
                            Forms\Components\TextInput::make('name')->label('Naam')->required()
                                ->helperText('Naam van de persona (mag fictief).'),
                            Forms\Components\TextInput::make('role')->label('Rol')->required()
                                ->helperText('Rol t.o.v. het project (bijv. Klant, Product Owner, Doelgroep).'),
                            Forms\Components\TextInput::make('avatar_url')->label('Avatar-URL')->url()->nullable()
                                ->helperText('Optioneel; laat leeg om automatisch te genereren.'),
                            Forms\Components\Textarea::make('goals')->label('Doelen')->nullable()
                                ->helperText('Wat wil deze persona bereiken met dit project?'),
                            Forms\Components\Textarea::make('traits')->label('Eigenschappen')->nullable()
                                ->helperText('Kernwoorden zoals direct, risico-avers, kwaliteitsgericht.'),
                            Forms\Components\Textarea::make('communication_style')->label('Communicatiestijl')->nullable()
                                ->helperText('Bijv. kort en bondig, data-gedreven, enthousiasmerend.'),
                            Repeater::make('workingHours')
                                ->relationship('workingHours')
                                ->label('Werkdagen / werktijden')
                                ->helperText('Geef aan op welke dagen en tijden deze persona beschikbaar is voor chat of overleg')
                                ->schema([
                                    Select::make('day_of_week')
                                        ->options([
                                            0 => 'Sunday',
                                            1 => 'Monday',
                                            2 => 'Tuesday',
                                            3 => 'Wednesday',
                                            4 => 'Thursday',
                                            5 => 'Friday',
                                            6 => 'Saturday',
                                        ]),
                                    TimePicker::make('start_time'),
                                    TimePicker::make('end_time'),
                                ])
                                ->columns(3)
                        ])
                        ->collapsed()
                        ->grid(2),
                ]),
        ];
    }
}
