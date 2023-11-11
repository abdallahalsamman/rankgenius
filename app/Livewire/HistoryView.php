<?php

namespace App\Livewire;

use App\Models\Batch;
use Livewire\Component;

class HistoryView extends Component
{
    private $id;
    public $batch;

    public function mount()
    {
        $this->id = \Route::current()->parameter('id');
        $this->batch = Batch::where('id', $this->id)->first();
    }

    public function render()
    {
        return view('livewire.history-view')->layout('layouts.dashboard');
    }
}
