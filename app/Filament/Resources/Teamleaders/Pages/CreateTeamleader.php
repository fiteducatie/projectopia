<?php

namespace App\Filament\Resources\Teamleaders\Pages;

use App\Filament\Resources\TeamleaderResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateTeamleader extends CreateRecord
{
    protected static string $resource = TeamleaderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenant = Filament::getTenant();
        if ($tenant) {
            $data['team_id'] = $tenant->getKey();
        }

        return $data;
    }
}
