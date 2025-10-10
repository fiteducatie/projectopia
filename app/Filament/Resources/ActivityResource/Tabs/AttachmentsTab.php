<?php

namespace App\Filament\Resources\ActivityResource\Tabs;

use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Section;

class AttachmentsTab
{
    public static function make(): array
    {
        return [
            Section::make()
                ->description('Upload relevante documenten zoals Word, PDF, afbeeldingen of video\'s. Voeg extra details toe.')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('attachments')
                        ->label('Bestanden uploaden')
                        ->helperText('Upload relevante documenten zoals Word, PDF, afbeeldingen of video\'s.')
                        ->collection('attachments')
                        ->downloadable()
                        ->multiple()
                        ->panelLayout('grid', ['grid-cols-6'])
                        ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/*', 'video/*', 'application/zip', 'application/x-zip-compressed', 'application/x-zip', '.zip'])
                        ->maxSize(10240) // 10MB
                        ->reorderable()
                        ->appendFiles()
                        ->responsiveImages()
                        ->conversion('thumb'),

                    Repeater::make('attachment_metadata')
                        ->visibleOn('edit')
                        ->label('Bestandsdetails')
                        ->helperText('Voeg extra details toe aan geüploade bestanden zoals naam, beschrijving en relevante persona\'s.')
                        ->schema([
                            Hidden::make('media_id'),

                            Forms\Components\TextInput::make('file_name')
                                ->label('Bestandsnaam')
                                ->disabled()
                                ->dehydrated(false),

                            Forms\Components\TextInput::make('name')
                                ->label('Weergavenaam')
                                ->placeholder('Geef een beschrijvende naam op')
                                ->required(),

                            Forms\Components\Textarea::make('description')
                                ->label('Beschrijving')
                                ->placeholder('Voeg een beschrijving toe aan dit bestand')
                                ->rows(3),

                            Forms\Components\Select::make('persona_ids')
                                ->label('Relevante Persona\'s')
                                ->multiple()
                                ->options(function ($record) {
                                    if (!$record) {
                                        return [];
                                    }

                                    return $record->personas()->get()->mapWithKeys(function ($persona) {
                                        return [$persona->id => $persona->role . ': ' . $persona->name];
                                    })->toArray();
                                })
                                ->placeholder('Selecteer persona\'s die toegang hebben tot dit bestand'),
                        ])
                        ->itemLabel(
                            fn(array $state): string =>
                            $state['name'] ?? ($state['file_name'] ?? 'Nieuw bestand')
                        )
                        ->collapsed()
                        ->grid(1)
                        ->addActionLabel('Bestand toevoegen')
                        ->reorderable(false)
                        ->live()
                        ->afterStateUpdated(function ($state, $component) {
                            // This will be handled in the page classes
                        }),
                ]),
        ];
    }
}

