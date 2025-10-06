<?php

namespace App\Filament\Resources\ProjectResource\Tabs;

use Filament\Forms;
use Filament\Schemas\Components\Section;

class TeamLeadersTab
{
    public static function make(): array
    {
        return [
            Section::make()
                ->description('Selecteer team leiders voor dit project.')
                ->schema([
                    Forms\Components\Select::make('team_leader_ids')
                        ->label('Team Leiders')
                        ->multiple()
                        ->relationship('teamLeaders', 'name')
                        ->preload()
                        ->searchable()
                        ->helperText('Selecteer een of meer team leiders die betrokken zijn bij dit project.'),
                ]),
        ];
    }
}
