<?php

namespace App\Livewire;

use App\Models\Persona;
use Livewire\Component;
use Livewire\Attributes\On;

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

        // hier kun je ook iets server-side mee doen
        $this->message = '';
    }
    public function render()
    {
        return view('livewire.persona-chat');
    }
}
