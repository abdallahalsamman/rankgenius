<?php

namespace App\Livewire;

use Livewire\Component;

class Presets extends Component
{
    public function render()
    {
        return view('livewire.presets')->layout('layouts.dashboard');
    }
}
