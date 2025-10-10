<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Filament\Resources\ActivityResource;
use App\Models\Persona;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateActivity extends CreateRecord
{
    protected static string $resource = ActivityResource::class;

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

        // Extract personas repeater data so it doesn't attempt to persist on the activity model
        $this->personasData = $data['personas_data'] ?? [];
        unset($data['personas_data']);

        // Extract user stories repeater data so it doesn't attempt to persist on the activity model
        $this->userStoriesData = $data['user_stories_data'] ?? [];
        unset($data['user_stories_data']);

        // Extract attachment metadata repeater data so it doesn't attempt to persist on the activity model
        $this->attachmentsData = $data['attachment_metadata'] ?? [];
        unset($data['attachment_metadata']);

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

        // Handle attachment metadata
        $attachments = $this->attachmentsData ?? [];
        foreach ($attachments as $attachment) {
            if (isset($attachment['media_id']) && !empty($attachment['media_id'])) {
                $this->record->updateAttachmentMetadata($attachment['media_id'], [
                    'name' => $attachment['name'] ?? '',
                    'description' => $attachment['description'] ?? '',
                    'persona_ids' => $attachment['persona_ids'] ?? [],
                ]);
            }
        }
    }
}





