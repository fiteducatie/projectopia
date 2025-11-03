<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Filament\Resources\ActivityResource;
use App\Filament\Resources\ActivityResource\Factories\ActivityTabFactory;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;

class ViewActivity extends ViewRecord
{
    protected static string $resource = ActivityResource::class;

    protected static ?string $modelLabel = 'Activiteit';
    protected static ?string $pluralModelLabel = 'Activiteiten';

    public function getTitle(): string
    {
        return 'Details van Activiteit: ' . $this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Bewerken')
                ->color('info'),
            Action::make('view-guest')
                ->label('Bekijk als gast')
                ->color('gray')
                ->url(fn () => route('choose.activity', $this->record))
                ->openUrlInNewTab(),
            DeleteAction::make()
                ->label('Verwijderen')
                ->color('danger')
                ->visible(fn () => !$this->record->trashed()),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Activity Details')
                    ->persistTabInQueryString()
                    ->columnSpanFull()
                    ->tabs(ActivityTabFactory::createInfolistTabs())
            ]);
    }

}



