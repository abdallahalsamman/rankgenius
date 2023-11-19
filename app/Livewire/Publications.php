<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Publications extends Component
{

    public $publications;

    public function setAndSortAutoBlogs()
    {
        $this->publications = auth()->user()->publications()->get();
    }

    public function mount()
    {
        $this->setAndSortAutoBlogs();
    }

    public function render()
    {
        return view('livewire.publications')->layout('layouts.dashboard');
    }
}
