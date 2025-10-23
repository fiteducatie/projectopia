<?php

namespace App\Livewire;

use Livewire\Component;

class ShowInfoPopup extends Component
{
    public $infoPopup;
    public $show = true;

    public function mount()
    {
        $activity = request()->route('activity');
        $this->infoPopup = $activity->info_popup;
    }

    public function render()
    {
        return view('livewire.show-info-popup', [
            'infoPopup' => $this->infoPopup
        ]);
    }
}
