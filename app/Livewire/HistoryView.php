<?php

namespace App\Livewire;

use Livewire\Component;

class HistoryView extends Component
{
    private $id;

    public function mount()
    {
        $this->id = \Route::current()->parameter('id');
    }

    public function render()
    {
        return view('livewire.history-view')->layout('layouts.dashboard');
    }
}
