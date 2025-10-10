<?php

namespace App\Filament\Resources\ActivityResource\InfolistTabs;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class UserStoriesInfolistTab
{
    public static function make(): array
    {
        return [
            RepeatableEntry::make('userStories')
                ->label('')
                ->schema([
                    TextEntry::make('user_story')->label('User Story')->weight('semibold')->columnSpanFull()
                        ->limitList(1),
                    TextEntry::make('acceptance_criteria')->label('Acceptatiecriteria')
                        ->listWithLineBreaks()
                        ->formatStateUsing(function ($state) {
                            return collect($state)->map(function ($criteria) {
                                return '✔️ ' . $criteria;
                            })->implode("\n");
                        }),
                    TextEntry::make('mvp')
                        ->badge()
                        ->hiddenLabel()
                        ->formatStateUsing(fn ($state) => $state ? 'MVP' : 'NTH')
                        ->color(fn ($state) => $state ? 'success' : 'secondary')
                        ->columnSpan(1),
                    TextEntry::make('priority')->label('Prioriteit')->badge()->color(fn ($state) => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'success',
                        default => 'secondary',
                    })->columnSpan(1),
                ])
        ];
    }
}

