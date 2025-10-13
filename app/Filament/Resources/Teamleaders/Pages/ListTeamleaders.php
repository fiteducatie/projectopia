<?php

namespace App\Filament\Resources\Teamleaders\Pages;

use App\Filament\Resources\TeamleaderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTeamleaders extends ListRecords
{
    protected static string $resource = TeamleaderResource::class;

    protected static ?string $modelLabel = 'Teamleider';
    protected static ?string $pluralModelLabel = 'Team Leiders';

    public function getTitle(): string
    {
        return 'Overzicht van alle Teamleiders';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nieuwe teamleider'),
        ];
    }
}
