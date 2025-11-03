<?php

namespace App\Filament\Resources\ActivityTypes\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ActivityTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->label('Naam')
                    ->required()
                    ->maxLength(255),
                TextInput::make('description')->label('Beschrijving')
                    ->required()
                    ->maxLength(255),
                ColorPicker::make('color')->label('Kleur')
                    ->required(),
                RichEditor::make('template')->label('Template')
                    ->json()
                    ->columnSpanFull()
                    ->required()
            ]);
    }
}
