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

        return response()->stream(function() use ($messages) {
            echo "data: " . json_encode(['type' => 'start']) . "\n\n";
            flush();

            try {
                $stream = OpenAI::chat()->createStreamed([
                    'model' => $this->getModel(),
                    'messages' => $messages,
                    'stream' => true,
                ]);

                foreach ($stream as $response) {
                    if (isset($response->choices[0]->delta->content)) {
                        $content = $response->choices[0]->delta->content;
                        echo "data: " . json_encode([
                            'type' => 'content',
                            'content' => $content
                        ]) . "\n\n";
                        flush();
                    }

                    if ($response->choices[0]->finishReason !== null) {
                        echo "data: " . json_encode(['type' => 'done']) . "\n\n";
                        flush();
                        break;
                    }
                }
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
            'X-Accel-Buffering' => 'no',
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

    protected abstract function findEntity(int $id);
}
