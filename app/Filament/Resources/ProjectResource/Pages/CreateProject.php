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
    protected array $userStoriesData = [];
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

        // Extract user stories repeater data so it doesn't attempt to persist on the project model
        $this->userStoriesData = $data['user_stories_data'] ?? [];
        unset($data['user_stories_data']);

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

        // Handle user stories
        $userStories = $this->userStoriesData ?? [];
        foreach ($userStories as $story) {
            // Convert acceptance criteria from repeater format to array
            $acceptanceCriteria = [];
            if (isset($story['acceptance_criteria']) && is_array($story['acceptance_criteria'])) {
                foreach ($story['acceptance_criteria'] as $criteria) {
                    if (isset($criteria['criteria']) && !empty($criteria['criteria'])) {
                        $acceptanceCriteria[] = $criteria['criteria'];
                    }
                }
            }

            // Convert personas from comma-separated string to array
            $personas = [];
            if (isset($story['personas']) && !empty($story['personas'])) {
                $personas = array_map('trim', explode(',', $story['personas']));
            }

            $this->record->userStories()->create([
                'user_story' => $story['user_story'] ?? '',
                'acceptance_criteria' => $acceptanceCriteria,
                'personas' => $personas,
                'mvp' => $story['mvp'] ?? false,
                'priority' => $story['priority'] ?? 'medium',
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



