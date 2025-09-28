<?php

namespace App\Livewire;

use App\Models\Teamleader;
use Livewire\Component;

class TeamleaderComponent extends Component
{
    public Teamleader $teamleader;

    public function mount(Teamleader $teamleader)
    {
        $this->teamleader = $teamleader;
    }

    public function render()
    {
        return view('livewire.teamleader-component');
    }

    public function startChat(): void
    {
        $this->dispatch('open-teamleader-chat', id: $this->teamleader->id);
    }
}
