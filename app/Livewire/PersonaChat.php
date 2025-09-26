<?php

namespace App\Livewire;

use App\Models\Persona;
use Livewire\Component;
use Livewire\Attributes\On;
use OpenAI\Laravel\Facades\OpenAI;

class PersonaChat extends Component
{
    public $isOpen = false;
    public $message = '';
    public $messages = [];
    public $personaId = null;
    public ?Persona $persona = null;

    #[On('open-persona-chat')]
    public function open($id = null)
    {
        $this->personaId = $id;
        $this->persona = $id ? Persona::query()->find($id) : null;
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function send()
    {
        if (trim($this->message) === '') return;

        $this->messages[] = [
            'type' => 'outgoing',
            'text' => $this->message,
            'time' => now()->format('H:i')
        ];

        $this->message = '';

        $response = OpenAI::responses()->create([
            'model' => 'gpt-5',
            'input' => $this->getConversationState(),
        ]);

        $responseText = $response->outputText;

        $this->messages[] = [
            'type' => 'incoming',
            'text' => $responseText,
            'time' => now()->format('H:i')
        ];
    }

    /**
     * Converts the messages to the format required by the OpenAI API:
     * [
     *   ['role' => 'system', 'content' => '...'],
     *   ['role' => 'user', 'content' => '...'],
     * ]
     */
    private function getConversationState()
    {
        $conversation = [];

        if ($this->persona) {
            $conversation[] = [
                'role' => 'system',
                'content' => $this->getPersonaDescription()
            ];
        } else {
            $conversation[] = [
                'role' => 'system',
                'content' => "You are a helpful assistant."
            ];
        }

        foreach ($this->messages as $message) {
            $role = $message['type'] === 'outgoing' ? 'user' : 'assistant';
            $conversation[] = [
                'role' => $role,
                'content' => $message['text']
            ];
        }

        return $conversation;
    }

    /**
     * Generate a description of the persona for the AI model.
     */
    private function getPersonaDescription()
    {
        $description = "You are role-playing as {$this->persona->name}, who is a {$this->persona->role}.";

        if ($this->persona->goals) {
            $description .= " Your goals are: {$this->persona->goals}.";
        }

        if ($this->persona->traits) {
            $description .= " Your traits include: {$this->persona->traits}.";
        }

        if ($this->persona->communication_style) {
            $description .= " Your communication style is: {$this->persona->communication_style}.";
        }

        return $description;
    }

    public function render()
    {
        return view('livewire.persona-chat');
    }
}
