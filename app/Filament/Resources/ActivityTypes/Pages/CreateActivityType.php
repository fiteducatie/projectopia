<?php

namespace App\Filament\Resources\ActivityTypes\Pages;

use App\Filament\Resources\ActivityTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateActivityType extends CreateRecord
{
    protected static string $resource = ActivityTypeResource::class;

    // before create
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['template'] = json_encode($data['template'], true);
        return $data;
    }
}
