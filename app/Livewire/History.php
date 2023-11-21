<?php

namespace App\Livewire;

use App\Models\Batch;
use Livewire\Component;

class History extends Component
{

    public $batches;

    public function mount()
    {
        $this->batches = auth()->user()->batches->take(50)->sortByDesc('created_at');
    }

    public function render()
    {
        return view('livewire.history')->extends('layouts.dashboard')->section('dashboard-content');
    }
}
