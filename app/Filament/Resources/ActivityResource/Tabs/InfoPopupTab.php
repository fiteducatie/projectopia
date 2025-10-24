<?php

namespace App\Filament\Resources\ActivityResource\Tabs;

use Filament\Forms\Components\RichEditor;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;

class InfoPopupTab
{
    public static function make(): array
    {
        return [
            Section::make()
                ->description('Geef een instructie hoe de student deze activiteitenpagina kan gebruiken.')
                ->schema([
                    TextEntry::make('info_popup_description')
                        ->label('Info Popup')
                        ->state('Deze info popup wordt getoond aan gebruikers wanneer ze de activiteit bekijken. Het beschrijft hoe ze de pagina van deze activiteit kunnen gebruiken of interpreteren.')
                        ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                    RichEditor::make('info_popup')
                        ->hiddenLabel()
                        ->nullable()
                        ->toolbarButtons([
                            ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                            ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                            ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                            ['table'],
                            ['undo', 'redo'],
                        ])
                        ->columnSpanFull(),
                ]),
        ];
    }
}
