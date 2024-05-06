<?php

namespace App\Livewire;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.home')->extends('livewire.welcome')->section('home-content');
    }
}
