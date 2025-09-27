<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Persona;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    /**
     * Temporarily store repeater data during create flow.
     * @var array<int, array<string, mixed>>
     */
    protected array $personasData = [];
    protected array $attachmentsData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenant = Filament::getTenant();
        if ($tenant) {
            $data['team_id'] = $tenant->getKey();
        }

        // Extract personas repeater data so it doesn't attempt to persist on the project model
        $this->personasData = $data['personas_data'] ?? [];
        unset($data['personas_data']);

        // Extract attachments repeater data so it doesn't attempt to persist on the project model
        $this->attachmentsData = $data['attachments_data'] ?? [];
        unset($data['attachments_data']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $personas = $this->personasData ?? [];
        foreach ($personas as $p) {
            $this->record->personas()->create([
                'name' => $p['name'] ?? '',
                'role' => $p['role'] ?? '',
                'avatar_url' => $p['avatar_url'] ?? null,
                'goals' => $p['goals'] ?? null,
                'traits' => $p['traits'] ?? null,
                'communication_style' => $p['communication_style'] ?? null,
            ]);
        }

        // Handle attachments using Spatie MediaLibrary
        $attachments = $this->attachmentsData ?? [];
        foreach ($attachments as $attachment) {
            if (!empty($attachment['file']) && is_array($attachment['file'])) {
                // Handle multiple files if uploaded
                $files = $attachment['file'];
                foreach ($files as $filePath) {
                    if ($filePath && \Storage::disk('public')->exists($filePath)) {
                        $media = $this->record->addMediaFromDisk($filePath, 'public')
                            ->toMediaCollection('attachments');

                        // Store additional metadata
                        $media->setCustomProperty('title', $attachment['title'] ?? '');
                        $media->setCustomProperty('details', $attachment['details'] ?? '');
                        $media->save();
                    }
                }
            } elseif (!empty($attachment['file']) && is_string($attachment['file'])) {
                // Handle single file
                $filePath = $attachment['file'];
                if (\Storage::disk('public')->exists($filePath)) {
                    $media = $this->record->addMediaFromDisk($filePath, 'public')
                        ->toMediaCollection('attachments');

                    // Store additional metadata
                    $media->setCustomProperty('title', $attachment['title'] ?? '');
                    $media->setCustomProperty('details', $attachment['details'] ?? '');
                    $media->save();
                }
            }
        }
    }
}



