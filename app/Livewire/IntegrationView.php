<?php

namespace App\Livewire;

use Livewire\Component;

class IntegrationView extends Component
{
    public function render()
    {
        return view('livewire.integration-view')->layout('layouts.dashboard');
    }
}
