<?php

namespace App\Livewire;

use App\Models\Persona;
use Livewire\Component;

class PersonaComponent extends Component
{
    public Persona $persona;

    public function mount(Persona $persona)
    {
        $this->persona = $persona;
    }

    public function render()
    {
        return view('livewire.persona-component');
    }

    public function startChat(): void
    {
        // Check if project is closed
        if ($this->persona->project->status === 'closed') {
            return;
        }

        $this->dispatch('open-persona-chat', id: $this->persona->id);
    }
}
