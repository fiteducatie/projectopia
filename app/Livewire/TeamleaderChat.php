<?php

namespace App\Livewire;

use App\Models\Teamleader;
use Livewire\Component;
use Livewire\Attributes\On;

class TeamleaderChat extends Component
{
    public $isOpen = false;
    public $teamleaderId = null;
    public ?Teamleader $teamleader = null;

    #[On('open-teamleader-chat')]
    public function open($id = null)
    {
        $this->teamleaderId = $id;
        $this->teamleader = $id ? Teamleader::query()->find($id) : null;
        $this->isOpen = true;
    }

    public function isProjectClosed(): bool
    {
        if (!$this->teamleader) {
            return false;
        }

        // Check if any project associated with this teamleader is closed
        return $this->teamleader->team && $this->teamleader->team->projects()->where('status', 'closed')->exists();
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.teamleader-chat');
    }
}
