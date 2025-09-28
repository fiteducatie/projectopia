<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Actions\Action;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('change-all-projects-status')
                ->label('Sluit alle projecten')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    \App\Models\Project::query()->update(['status' => 'closed']);
                    $this->redirect($this->getResource()::getUrl('index'));
                })
        ];
    }
}



