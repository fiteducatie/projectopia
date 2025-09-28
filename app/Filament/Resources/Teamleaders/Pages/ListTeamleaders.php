<?php

namespace App\Filament\Resources\Teamleaders\Pages;

use App\Filament\Resources\TeamleaderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTeamleaders extends ListRecords
{
    protected static string $resource = TeamleaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
