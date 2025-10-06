<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\ProjectResource\Factories\ProjectTabFactory;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Bewerken'),
            Action::make('view-guest')
                ->label('Bekijk als gast')
                ->color('gray')
                ->url(fn () => route('choose.project', $this->record))
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
                Tabs::make('Project Details')
                    ->persistTabInQueryString()
                    ->columnSpanFull()
                    ->tabs(ProjectTabFactory::createInfolistTabs())
            ]);
    }

}


