<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Filament\Resources\ActivityResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Actions\Action;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('change-all-activities-status')
                ->label('Sluit alle activiteiten')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    \App\Models\Activity::query()->update(['status' => 'closed']);
                    $this->redirect($this->getResource()::getUrl('index'));
                })
        ];
    }
}




