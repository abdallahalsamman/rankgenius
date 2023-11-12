<?php

namespace App\Livewire;

use Livewire\Component;

class PresetEdit extends Component
{
    public function render()
    {
        return view('livewire.preset-view')->layout('layouts.dashboard');
    }
}
