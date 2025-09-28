<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenAI\Laravel\Facades\OpenAI;

abstract class BaseChatController extends Controller
{
    protected abstract function getModel();
    protected abstract function getPromptTemplate(): string;
    protected abstract function getEntityDescription($entity): string;
    protected abstract function getProjectFromEntity($entity);

    public function stream(Request $request, int $entityId)
    {
        $request->validate([
            'messages' => 'required|array',
            'messages.*.role' => 'required|in:user,assistant,system',
            'messages.*.content' => 'required|string',
        ]);

        $entity = $this->findEntity($entityId);
        $messages = $this->prepareMessages($request->input('messages'), $entity);

        return response()->stream(function () use ($messages, $entity) {
            echo "data: " . json_encode(['type' => 'start']) . "\n\n";
            flush();

            $fileChoices = [];

            $project = $this->getProjectFromEntity($entity);

            $attachmentsToShare = $project->getMedia('*');

            foreach ($attachmentsToShare as $attachment) {
                $name = $attachment->file_name;
                $description = $attachment->getCustomProperty('description') ?? 'No description available';
                $fileChoices[$name] = $description;
            }

            // If no files are available, provide a minimal schema
            if (empty($fileChoices)) {
                $fileChoices = ['no_files' => 'No files available'];
            }

            $definition = $this->buildResponseSchema($fileChoices);

            try {
                $response = OpenAI::responses()->create([
                    'model' => 'gpt-4.1-mini',
                    'input' => $messages,
                    'text' => [
                        'format' => $definition,
                    ],
                ]);

                $output = $response->outputText;
                $content = json_decode($output);

                foreach ($content->files_to_share as $file) {
                    // Skip the "no_files" placeholder
                    if ($file->value === 'no_files') {
                        continue;
                    }
                    
                    $attachment = $project->getMedia('*')->firstWhere('file_name', $file->value);
                    if ($attachment) {
                        echo "data: " . json_encode([
                            'type' => 'file',
                            'file_name' => $attachment->file_name,
                            'file_url' => $attachment->getFullUrl(),
                        ]) . "\n\n";
                        flush();
                    }
                }

                echo "data: " . json_encode([
                    'type' => 'content',
                    'content' => $content->message,
                ]) . "\n\n";
                flush();
            } catch (\Exception $e) {
                echo "data: " . json_encode([
                    'type' => 'error',
                    'message' => 'Something went wrong. Please try again.',
                    'exception' => $e->getMessage()
                ]) . "\n\n";
                flush();
            }

            echo "data: [DONE]\n\n";
            flush();
        }, Response::HTTP_OK, [
            'Content-Type' => 'text/plain',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no', // Disable nginx buffering
        ]);
    }

    protected function prepareMessages(array $messages, $entity): array
    {
        $preparedMessages = [];

        // Add system message for entity
        $preparedMessages[] = [
            'role' => 'system',
            'content' => $this->getEntityDescription($entity)
        ];

        // Add conversation messages
        foreach ($messages as $message) {
            $preparedMessages[] = [
                'role' => $message['role'],
                'content' => $message['content']
            ];
        }

        return $preparedMessages;
    }

    protected function buildPromptFromTemplate($entity): string
    {
        $project = $this->getProjectFromEntity($entity);

        $prompt = $this->getPromptTemplate();

        // Add persona and user story information for teamleaders
        if ($this instanceof \App\Http\Controllers\Api\TeamleaderChatController && $project) {
            $prompt .= "\n\nBESCHIKBARE PERSONA'S EN USER STORIES:\n";
            $prompt .= $this->getPersonasAndUserStoriesInfo($project);
        }


        return str_replace(
            $this->getTemplatePlaceholders(),
            $this->getTemplateValues($entity, $project),
            $prompt
        );
    }

    protected function getPersonasAndUserStoriesInfo($project): string
    {
        $info = "";

        // Get all user stories for the project
        $userStories = $project->userStories;
        $info .= "USER STORIES IN DIT PROJECT:\n";

        if ($userStories->isNotEmpty()) {
            foreach ($userStories as $index => $story) {
                $info .= ($index + 1) . ". {$story->title}\n";
                if ($story->description) {
                    $info .= "   Beschrijving: {$story->description}\n";
                }
                if ($story->acceptance_criteria) {
                    $criteria = is_array($story->acceptance_criteria)
                        ? implode(', ', $story->acceptance_criteria)
                        : $story->acceptance_criteria;
                    $info .= "   Acceptatiecriteria: {$criteria}\n";
                }
                $info .= "\n";
            }
        } else {
            $info .= "Er zijn momenteel geen user stories gedefinieerd voor dit project.\n\n";
        }

        // Get personas with their associated user stories
        $personas = $project->personas()->with('userStories')->get();
        $info .= "BESCHIKBARE PERSONA'S:\n";

        foreach ($personas as $persona) {
            $info .= "- {$persona->name} ({$persona->role}): ";

            if ($persona->userStories->isNotEmpty()) {
                $storyTitles = $persona->userStories->pluck('title')->take(3)->toArray();
                $info .= "Kan helpen met user stories zoals: " . implode(', ', $storyTitles);
                if ($persona->userStories->count() > 3) {
                    $info .= " en " . ($persona->userStories->count() - 3) . " andere";
                }
            } else {
                $info .= "Geen specifieke user stories toegewezen";
            }
            $info .= "\n";
        }

        if ($personas->isEmpty()) {
            $info .= "Er zijn momenteel geen persona's toegewezen aan dit project.\n";
        }

        return $info;
    }

    protected function getTemplatePlaceholders(): array
    {
        return [
            '{entity.name}', '{entity.role}', '{entity.goals}', '{entity.traits}', '{entity.communication_style}',
            '{entity.summary}', '{entity.description}', '{entity.skillset}', '{entity.deliverables}',
            '{project.context}', '{project.objectives}', '{project.constraints}', '{project.start_date}', '{project.end_date}', '{project.risk_notes}'
        ];
    }

    protected function getTemplateValues($entity, $project): array
    {
        return [
            $entity->name,
            $entity->role ?? $entity->summary ?? 'Een behulpzame gesprekspartner',
            $entity->goals ?? 'De gebruiker zo goed mogelijk helpen',
            $entity->traits ?? 'Empathisch, geduldig en informatief',
            $entity->communication_style ?? 'Duidelijk en beknopt',
            $entity->summary ?? 'Een ervaren team leider',
            $entity->description ?? 'Verantwoordelijk voor het leiden van het team en begeleiden van projectactiviteiten.',
            $entity->skillset ?? 'Projectmanagement, teamleiderschap en communicatie',
            $entity->deliverables ?? 'Succesvol projectresultaat en gemotiveerd team',

            $project ? ($project->context ?? 'Niet van toepassing.') : 'Niet van toepassing.',
            $project ? ($project->objectives ?? 'Niet van toepassing.') : 'Niet van toepassing.',
            $project ? ($project->constraints ?? 'Niet van toepassing.') : 'Niet van toepassing.',
            $project && $project->start_date ? $project->start_date->toDateString() : 'Niet van toepassing.',
            $project && $project->end_date ? $project->end_date->toDateString() : 'Niet van toepassing.',
            $project ? ($project->risk_notes ?? 'Niet van toepassing.') : 'Niet van toepassing.'
        ];
    }

    private function buildResponseSchema(array $fileChoices): array
    {
        function fileOption(string $value, string $description): array
        {
            return [
                'type' => 'object',
                'properties' => [
                    'value' => ['const' => $value],
                    'description' => ['type' => 'string', 'const' => $description],
                ],
                'required' => ['value', 'description'],
            ];
        }

        $items = [];
        foreach ($fileChoices as $value => $desc) {
            $items[] = fileOption($value, $desc);
        }

        $definition = [
            'type' => 'json_schema',
            'name' => 'filesArray',
            'strict' => false,
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'message' => [
                        'type' => 'string',
                        'description' => "The persona's response message to the user.",
                    ],
                    'files_to_share' => [
                        'type' => 'array',
                        'description' => "Choose zero, one, or multiple files to offer to the user.",
                        'items' => [
                            'anyOf' => $items,
                        ],
                    ],
                ],
                'required' => ['message', 'files_to_share'],
            ],
        ];

        return $definition;
    }

    protected abstract function findEntity(int $id);
}
