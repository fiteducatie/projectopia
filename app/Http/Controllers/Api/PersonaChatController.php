<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenAI\Laravel\Facades\OpenAI;

class PersonaChatController extends Controller
{
    private const PROMPT_TEMPLATE = <<<EOT
Je speelt de rol van {persona.name}, die een {persona.role} is.
Jouw doelen zijn: {persona.goals}.
Jouw eigenschappen zijn onder andere: {persona.traits}.
Jouw communicatiestijl is: {persona.communication_style}.

Dit project heeft de volgende context:
{project.context}

Doelen van het project:
{project.objectives}

Beperkingen van het project:
{project.constraints}

Het project loopt van {project.start_date} tot {project.end_date}.

Risicofactoren in het project zijn:
{project.risk_notes}

Blijf altijd in karakter en beantwoord de vragen van de gebruiker op een manier die overeenkomt met jouw rol, doelen, eigenschappen en communicatiestijl.
EOT;

    public function stream(Request $request, int $personaId)
    {
        $request->validate([
            'messages' => 'required|array',
            'messages.*.role' => 'required|in:user,assistant,system',
            'messages.*.content' => 'required|string',
        ]);

        $persona = Persona::findOrFail($personaId);
        $messages = $this->prepareMessages($request->input('messages'), $persona);

        return response()->stream(function() use ($messages) {
            echo "data: " . json_encode(['type' => 'start']) . "\n\n";
            flush();

            try {
                $stream = OpenAI::chat()->createStreamed([
                    'model' => 'gpt-4',
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
                    'message' => 'Something went wrong. Please try again.'
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

    private function prepareMessages(array $messages, Persona $persona): array
    {
        $preparedMessages = [];

        // Add system message for persona
        $preparedMessages[] = [
            'role' => 'system',
            'content' => $this->getPersonaDescription($persona)
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

    private function getPersonaDescription(Persona $persona): string
    {
        return str_replace(
            [
                '{persona.name}', '{persona.role}', '{persona.goals}', '{persona.traits}', '{persona.communication_style}',
                '{project.context}', '{project.objectives}', '{project.constraints}', '{project.start_date}', '{project.end_date}', '{project.risk_notes}'
            ],
            [
                $persona->name,

                // TODO: Ideally these fields should be required as we cannot have a meaningful prompt without them
                // TODO: We could consider having the PROMPT_TEMPLATE as a database field to allow customization per persona
                // TODO: That would also allow for localization if needed
                $persona->role ?? 'Een behulpzame gesprekspartner',
                $persona->goals ?? 'De gebruiker zo goed mogelijk helpen',
                $persona->traits ?? 'Empathisch, geduldig en informatief',
                $persona->communication_style ?? 'Duidelijk en beknopt',

                $persona->project->context ?? 'Niet van toepassing.',
                $persona->project->objectives ?? 'Niet van toepassing.',
                $persona->project->constraints ?? 'Niet van toepassing.',
                $persona->project->start_date ? $persona->project->start_date->toDateString() : 'Niet van toepassing.',
                $persona->project->end_date ? $persona->project->end_date->toDateString() : 'Niet van toepassing.',
                $persona->project->risk_notes ?? 'Niet van toepassing.'
            ],
            self::PROMPT_TEMPLATE
        );
    }
}
