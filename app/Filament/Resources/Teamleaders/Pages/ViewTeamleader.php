<?php

namespace App\Filament\Resources\Teamleaders\Pages;

use App\Filament\Resources\TeamleaderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTeamleader extends ViewRecord
{
    protected static string $resource = TeamleaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
