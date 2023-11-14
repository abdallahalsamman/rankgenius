<?php

namespace App\Livewire;

use Livewire\Component;

class Articleview extends Component
{
    public function render()
    {
        return view('livewire.articleview')->layout('layouts.dashboard');
    }
}
