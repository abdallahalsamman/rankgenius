<?php

namespace App\Livewire;

use Livewire\Component;

class Articles extends Component
{

    public $articles;

    public function mount()
    {
        $this->articles = auth()->user()->articles->take(50);
    }

    public function render()
    {
        return view('livewire.articles')->layout('layouts.dashboard');
    }
}
