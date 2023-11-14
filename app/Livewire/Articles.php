<?php

namespace App\Livewire;

use Livewire\Component;

class Articles extends Component
{

    public $articles, $selectedArticle, $myModal = false;

    public function mount()
    {
        $this->articles = auth()->user()->articles->take(50);
    }

    public function viewArticle($id)
    {
        $this->selectedArticle = $this->articles->find($id);
        $this->myModal = true;
    }

    public function render()
    {
        return view('livewire.articles')->layout('layouts.dashboard');
    }
}
