<?php

namespace App\Filament\Resources\ActivityResource\Tabs;

use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;

class TeamLeadersTab
{
    public static function make(): array
    {
        return [
            Section::make()
                ->description('Selecteer team leiders voor deze activiteit.')
                ->schema([
                    TextEntry::make('team_leaders_help')
                        ->label('Team Leiders')
                        ->state('Selecteer een of meer team leiders die betrokken zijn bij deze activiteit.')
                        ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                    Forms\Components\Select::make('team_leader_ids')
                        ->hiddenLabel()
                        ->multiple()
                        ->placeholder('Selecteer team leiders')
                        ->relationship('teamLeaders', 'name')
                        ->preload()
                        ->searchable()
                ]),
        ];
    }
}

