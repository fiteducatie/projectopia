<?php
namespace App\Filament\Resources\ProjectResource\Tabs;

use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class ScheduleTab
{
    public static function make(): array
    {
        return [
            Section::make('')
                ->description('Voeg taken of reminders toe die de teamleader actief met de studenten deelt.')
                ->schema([
                    Repeater::make('schedule')
                        ->label('Planning')
                        ->columns(4)
                        ->schema([
                            DateTimePicker::make('time_from')->label('Van (tijd)'),
                            DateTimePicker::make('time_until')->label('Tot (tijd)'),
                            TextInput::make('title')->label('Titel'),
                            Textarea::make('description')->label('Beschrijving')
                        ]),
                ]),
        ];
    }
}
