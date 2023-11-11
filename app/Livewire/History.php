<?php

namespace App\Livewire;

use App\Models\Batch;
use Livewire\Component;

class History extends Component
{

    public $batches;

    public function mount()
    {
        $this->batches = Batch::take(50)->get();
    }

    public function render()
    {
        return view('livewire.history')->layout('layouts.dashboard');
    }

    public function delete()
    {

    }
}
