<?php

namespace App\Livewire;

use Livewire\Component;

class WelcomePage extends Component
{
    public function render()
    {
        return view('livewire.welcome')->extends('components.layouts.app');
    }
}
