<?php

namespace App\Filament\Resources\ActivityResource\Tabs;

use App\Models\ActivityType;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;

class OverviewTab
{
    public static function make(): array
    {
        return [
            Section::make()
                ->columns(3)
                ->description('Basis activiteit informatie')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('banner')
                        ->label('Activiteit afbeelding')
                        ->columnSpanFull()
                        ->image()
                        ->imageEditor()
                        ->collection('banner'),
                    Forms\Components\TextInput::make('name')->label('Naam')
                        ->columnSpanFull()
                        ->helperText('Korte, herkenbare activiteit naam.')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('domain')->label('Domein')
                        ->columns(1)
                        ->options([
                            'software' => 'Software',
                            'marketing' => 'Marketing',
                            'event' => 'Evenement',
                        ])->required()->default('software')
                        ->helperText('Bepaalt de standaardtemplates (Software, Marketing, Evenement).'),
                    Forms\Components\Select::make('difficulty')->label('Complexiteit')
                        ->columns(1)
                        ->options([
                            'laag' => 'Laag',
                            'middel' => 'Middel',
                            'hoog' => 'Hoog',
                        ])->required()->default('middel')
                        ->helperText('Bepaalt de complexiteit van de activiteit.'),
                    Forms\Components\Select::make('status')->label('Status')
                        ->options([
                            'open' => 'Open',
                            'closed' => 'Gesloten',
                        ])->required()->default('open')
                        ->helperText('Gesloten activiteiten hebben geen actieve chat functionaliteit.'),
                ]),
            Section::make()
                ->schema([
                    Select::make('activiteitstemplate')->label('Activiteit template')
                        ->live()
                        ->afterStateUpdated(function (callable $set, callable $get, $state) {

                            if ( !OverviewTab::isTiptapEmpty($get('content')) ) {
                                Notification::make()
                                    ->danger()
                                    ->title('Er is al inhoud voor de activiteit. Sla deze eerst leeg op voor je een nieuw template kunt kiezen.')
                                    ->send();
                                return;
                            }
                            $activityType = ActivityType::find($state);
                            $set('content', $activityType->template);
                        })
                        ->options(function () {
                            $activityTypes = ActivityType::all();
                            return $activityTypes->pluck('name', 'id');
                        }),
                    RichEditor::make('content')->json()->label('Activiteit inhoud')->live()
                ]),

        ];
    }

    public static function isTiptapEmpty(?array $value): bool
    {
        if (empty($value['content'])) {
            return true;
        }

        foreach ($value['content'] as $block) {
            if (!empty($block['content'])) {
                foreach ($block['content'] as $inner) {
                    if (!empty($inner['text'])) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
