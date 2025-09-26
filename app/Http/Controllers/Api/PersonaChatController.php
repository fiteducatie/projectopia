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
Je speelt de rol van {name}, die een {role} is. Jouw doelen zijn: {goals}. Jouw eigenschappen zijn onder andere: {traits}. Jouw communicatiestijl is: {communication_style}.
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
                '{name}', '{role}', '{goals}', '{traits}', '{communication_style}'
            ],
            [
                $persona->name,
                $persona->role ?? 'Een behulpzame gesprekspartner',
                $persona->goals ?? 'De gebruiker zo goed mogelijk helpen',
                $persona->traits ?? 'Empathisch, geduldig en informatief',
                $persona->communication_style ?? 'Duidelijk en beknopt'
            ],
            self::PROMPT_TEMPLATE
        );
    }
}
