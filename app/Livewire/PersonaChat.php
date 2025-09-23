<?php

namespace App\Livewire;

use Livewire\Component;

class PersonaChat extends Component
{
    public $isOpen = false;
    public $message = '';
    public $messages = [];

    public function open()
    {
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
