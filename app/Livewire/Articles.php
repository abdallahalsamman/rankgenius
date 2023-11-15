<?php

namespace App\Livewire;

use Livewire\Component;

class Articles extends Component
{

    public $articles, $selectedArticle, $articleModal = false;

    public function mount()
    {
        $this->articles = auth()->user()->articles->take(50);
        $this->articleModal = true;
        $this->selectedArticle = $this->articles->first();
    }

    public function viewArticle($id)
    {
        $this->selectedArticle = $this->articles->find($id);
        $this->articleModal = true;
    }

    public function render()
    {
        return view('livewire.articles')->layout('layouts.dashboard');
    }
}
