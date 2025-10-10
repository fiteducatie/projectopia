<?php

namespace App\Filament\Resources\ActivityResource\InfolistTabs;

use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;

class PersonasInfolistTab
{
    public static function make(): array
    {
        return [
            RepeatableEntry::make('personas')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            ImageEntry::make('avatar_url')->circular()->height(56)->columnSpan(1),
                            Grid::make(1)
                                ->schema([
                                    TextEntry::make('role')->label('Rol')->weight('semibold'),
                                    TextEntry::make('name')->label('Naam'),
                                ])->columnSpan(1),
                        ]),

                    TextEntry::make('goals')->label('Doelen')->columnSpanFull(),
                    TextEntry::make('traits')->label('Eigenschappen')->columnSpanFull(),
                    TextEntry::make('communication_style')->label('Communicatiestijl')->columnSpanFull(),

                    // Bestanden sectie per persona
                    TextEntry::make('attachments_list')
                        ->label('Kennis / in bezit van de volgende bestanden')
                        ->getStateUsing(function ($record) {
                            $attachments = $record->attachments()->get();

                            if ($attachments->isEmpty()) {
                                return 'Geen bestanden toegewezen';
                            }

                            return $attachments->map(function ($media) {
                                $customProps = $media->custom_properties ?? [];
                                $name = $customProps['name'] ?? $media->name;
                                $description = $customProps['description'] ?? '';

                                $result = "📎 [**{$name}**]({$media->getUrl()})";
                                if ($description) {
                                    $result .= " - *{$description}*";
                                }
                                $result .= " <small>({$media->mime_type})</small>";

                                return $result;
                            })->join("\n\n");
                        })
                        ->markdown()
                        ->columnSpanFull(),
                ])
                ->columns(1),
        ];
    }
}

