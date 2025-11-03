<?php

namespace App\Filament\Resources\ActivityResource\InfolistTabs;
use Filament\Forms\Components\RichEditor;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section as InfoSection;
use Illuminate\Contracts\View\View;

class InfoPopupInfolistTab
{
    public static function make(): array
    {
        return [
            InfoSection::make('Info Popup')
                ->schema([
                    TextEntry::make('info_popup')
                        ->hiddenLabel()
                        ->formatStateUsing(fn (string $state): View => view('filament.infolists.components.info-popup-viewer', ['state' => $state],))
                        ->columnSpanFull(),
                ]),
        ];
    }
}
