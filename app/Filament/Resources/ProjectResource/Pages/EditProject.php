<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Illuminate\Support\Facades\Storage;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected array $attachmentsData = [];

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('Bekijken')->color('primary'),
            Action::make('view-guest')
                ->label('Bekijk als gast')
                ->color('gray')
                ->url(fn () => route('choose.project', $this->record))
                ->openUrlInNewTab(),

            Action::make('change-project-status')
                ->label(fn () => $this->record->status === 'closed' ? 'Heropen project' : 'Sluit project')
                ->color(fn () => $this->record->status === 'closed' ? 'success' : 'danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->status = $this->record->status === 'closed' ? 'open' : 'closed';
                    $this->record->save();
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
                }),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load existing attachment metadata
        $attachments = $this->record->getAttachmentMetadata();
        $data['attachment_metadata'] = $attachments;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Extract attachment metadata data
        $this->attachmentsData = $data['attachment_metadata'] ?? [];

        // Remove attachment_metadata from the data array as it's not a database field
        unset($data['attachment_metadata']);

        return $data;
    }

    protected function afterSave(): void
    {
        // Handle attachment metadata updates
        $attachments = $this->attachmentsData ?? [];
        foreach ($attachments as $attachment) {
            if (isset($attachment['media_id']) && !empty($attachment['media_id'])) {
                // i want persona ids to ba stored as an array of integers, not strings
                $attachment['persona_ids'] = array_map('intval', $attachment['persona_ids']);
                $this->record->updateAttachmentMetadata($attachment['media_id'], [
                    'name' => $attachment['name'] ?? '',
                    'description' => $attachment['description'] ?? '',
                    'persona_ids' => $attachment['persona_ids'] ?? [],
                ]);
            }
        }
    }
}



