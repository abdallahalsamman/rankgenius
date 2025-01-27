<?php

namespace App\Livewire;

use Livewire\Component;

class Articles extends Component
{

    public $articles, $selectedArticle, $articleModal = false;

    public function mount()
    {
        $this->articles = auth()->user()->articles->sortByDesc('created_at')/*->take(50)*/;
    }

    public function viewArticle($id)
    {
        $this->selectedArticle = $this->articles->find($id);
        $this->articleModal = true;
    }

    public function render()
    {
        return view('livewire.articles')->extends('layouts.dashboard')->section('dashboard-content');
    }
}
