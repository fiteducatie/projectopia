<?php

namespace App\Filament\Resources\ActivityResource\InfolistTabs;
use Filament\Forms\Components\RichEditor;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section as InfoSection;

class InfoPopupInfolistTab
{
    public static function make(): array
    {
        return [
            InfoSection::make('Info Popup')
                ->schema([
                    TextEntry::make('info_popup')
                        ->hiddenLabel()
                        ->html()
                        ->prose()
                        ->columnSpanFull(),
                ]),
        ];
    }
}
