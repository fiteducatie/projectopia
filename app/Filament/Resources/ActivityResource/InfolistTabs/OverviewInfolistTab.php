<?php

namespace App\Filament\Resources\ActivityResource\InfolistTabs;

use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\ViewEntry;

class OverviewInfolistTab
{
    public static function make(): array
    {
        return [
            ImageEntry::make('banner')
                ->label('')
                ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('banner'))

                ->columnSpanFull()
                ->visible(fn ($record) => $record->getFirstMediaUrl('banner') !== null),

            Grid::make(4)
                ->schema([
                    TextEntry::make('name')->label('Naam')->weight('bold')->size('xl')->columnSpan(2),
                    TextEntry::make('domain')->label('Domein')->badge()->columnSpan(1),
                    TextEntry::make('difficulty')->label('Moeilijkheid')->badge()->color('warning')->columnSpan(1),
                    TextEntry::make('status')->label('Status')->badge()->color(fn($state) => $state === 'open' ? 'success' : 'danger')->columnSpan(2),
                ])->columnSpanFull(),

            ViewEntry::make('content')->view('filament.components.rich-editor.rich-content-renderer'),
        ];
    }
}

