<?php

namespace App\Filament\Resources\ActivityResource\InfolistTabs;

use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;

class TeamLeadersInfolistTab
{
    public static function make(): array
    {
        return [
            RepeatableEntry::make('teamleaders')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            ImageEntry::make('avatar_url')
                                ->label('Avatar')
                                ->circular()
                                ->columnSpan(1),
                            Grid::make(1)
                                ->schema([
                                    TextEntry::make('name')
                                        ->label('Naam')
                                        ->weight('bold')
                                        ->size('lg'),
                                    TextEntry::make('summary')
                                        ->label('Samenvatting'),
                                ])
                                ->columnSpan(2),
                        ]),

                    TextEntry::make('description')
                        ->label('Beschrijving')
                        ->columnSpanFull(),
                    TextEntry::make('communication_style')
                        ->label('Communicatiestijl')
                        ->columnSpanFull(),
                    TextEntry::make('skillset')
                        ->label('Vaardigheden')
                        ->columnSpanFull(),
                    TextEntry::make('deliverables')
                        ->label('Verwachte Deliverables')
                        ->columnSpanFull(),
                ])
                ->columns(1),
        ];
    }
}

