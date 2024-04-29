<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class Publications extends Component
{
    use WithPagination;

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
        return view('livewire.publications')->extends('layouts.dashboard')->section('dashboard-content');
    }
}
