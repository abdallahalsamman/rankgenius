<?php

namespace App\Livewire;

use App\Models\Batch;
use Livewire\Component;

class HistoryView extends Component
{
    private $id;
    public $batch;
    public $selectedArticle, $batchModal = false;
    public $selectedArticleId, $selectedArticleIdx = 0;

    public function viewBatch()
    {
        $this->selectedArticle = $this->batch->articles->first();
        $this->selectedArticleId = $this->selectedArticle->id;
        $this->selectedArticleIdx = 0;
        $this->batchModal = true;
    }

    public function next()
    {
        $this->selectedArticleIdx = $this->selectedArticleIdx + 1;
        $this->selectedArticle = $this->batch->articles[$this->selectedArticleIdx];
        $this->selectedArticleId = $this->selectedArticle->id;
    }

    public function previous()
    {
        $this->selectedArticleIdx = $this->selectedArticleIdx - 1;
        $this->selectedArticle = $this->batch->articles[$this->selectedArticleIdx];
        $this->selectedArticleId = $this->selectedArticle->id;
    }

    public function updatedSelectedArticleId($id)
    {
        $this->selectedArticle = $this->batch->articles->where("id", $id)->first();
        $this->selectedArticleIdx = $this->batch->articles->search(function ($article) use ($id) {
            return $article->id == $id;
        });
    }

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
