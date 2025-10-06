<?php

namespace App\Filament\Resources\ProjectResource\InfolistTabs;

use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;

class AttachmentsInfolistTab
{
    public static function make(): array
    {
        return [
            RepeatableEntry::make('attachments')
                ->getStateUsing(fn ($record) => $record->getAttachmentMetadata())
                ->schema([
                    Grid::make(4)
                        ->schema([
                            ImageEntry::make('url')
                                ->label('Preview')
                                ->height(120)
                                ->width(160)
                                ->columnSpan(1)
                                ->visible(fn ($state) => str_starts_with($state, 'http')),

                            TextEntry::make('name')
                                ->label('Bestandsnaam')
                                ->weight('semibold')
                                ->columnSpan(2),

                            TextEntry::make('file_name')
                                ->label('Originele naam')
                                ->color('gray')
                                ->columnSpan(1),

                            TextEntry::make('description')
                                ->label('Beschrijving')
                                ->columnSpanFull()
                                ->placeholder('Geen beschrijving beschikbaar')
                                ->color(fn ($state) => $state ? null : 'gray'),

                            TextEntry::make('mime_type')
                                ->label('Type')
                                ->badge()
                                ->color('secondary')
                                ->columnSpan(1),

                            TextEntry::make('size')
                                ->label('Grootte')
                                ->formatStateUsing(fn ($state) => self::formatFileSize($state))
                                ->color('gray')
                                ->columnSpan(1),

                            TextEntry::make('persona_names')
                                ->label('Relevante Persona\'s')
                                ->formatStateUsing(function ($state) {
                                    if (!$state) {
                                        return 'Geen persona\'s geselecteerd';
                                    }

                                    // Ensure $state is an array
                                    if (is_string($state)) {
                                        return $state;
                                    }

                                    if (is_array($state)) {
                                        return implode(', ', $state);
                                    }

                                    return 'Geen persona\'s geselecteerd';
                                })
                                ->color(fn ($state) => $state ? null : 'gray')
                                ->columnSpan(2),
                        ]),
                ])
                ->columns(1)
        ];
    }

    protected static function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
