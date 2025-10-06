<?php

namespace App\Filament\Resources\ProjectResource\InfolistTabs;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Grid;
use Carbon\Carbon;

class ScheduleInfolistTab
{
    public static function make(): array
    {
        return [
            RepeatableEntry::make('schedule')
                ->getStateUsing(function ($record) {
                    $schedule = $record->schedule ?? [];
                    $now = Carbon::now();

                    return collect($schedule)->map(function ($item, $index) use ($now) {
                        $timeFrom = Carbon::parse($item['time_from'] ?? '');
                        $timeUntil = Carbon::parse($item['time_until'] ?? '');
                        $isActive = $now->between($timeFrom, $timeUntil);

                        return [
                            'id' => $index,
                            'title' => $item['title'] ?? 'Geen titel',
                            'description' => $item['description'] ?? 'Geen beschrijving',
                            'time_from' => $timeFrom->format('d-m-Y H:i'),
                            'time_until' => $timeUntil->format('d-m-Y H:i'),
                            'is_active' => $isActive,
                            'status' => $isActive ? 'Actief' : ($now->gt($timeUntil) ? 'Afgelopen' : 'Gepland'),
                        ];
                    })->sortBy(function ($item) {
                        return $item['time_from'];
                    })->values();
                })
                ->schema([
                    Grid::make(4)
                        ->schema([
                            TextEntry::make('title')
                                ->label('Titel')
                                ->weight('bold')
                                ->columnSpan(2),

                            TextEntry::make('time_from')
                                ->label('Van')
                                ->color('gray')
                                ->columnSpan(1),

                            TextEntry::make('time_until')
                                ->label('Tot')
                                ->color('gray')
                                ->columnSpan(1),

                            TextEntry::make('status')
                                ->label('Status')
                                ->badge()
                                ->color(fn ($state, $record) => match ($record['status']) {
                                    'Actief' => 'success',
                                    'Gepland' => 'warning',
                                    'Afgelopen' => 'secondary',
                                    default => 'secondary',
                                })
                                ->columnSpan(1),

                            TextEntry::make('description')
                                ->label('Beschrijving')
                                ->columnSpanFull()
                                ->placeholder('Geen beschrijving beschikbaar')
                                ->color(fn ($state) => $state ? null : 'gray'),
                        ])
                ])
                ->columns(1)
        ];
    }
}



