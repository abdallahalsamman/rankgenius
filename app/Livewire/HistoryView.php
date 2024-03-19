<?php

namespace App\Livewire;

use App\Models\Batch;
use App\Models\Integration;
use Livewire\Component;

class HistoryView extends Component
{
    private $id;
    public $batch;
    public $selectedArticleId, $selectedArticleIdx = 0, $integration_id;
    public $integrationOptions = [];

    public function next()
    {
        $this->selectedArticleIdx = $this->selectedArticleIdx + 1;
    }

    public function previous()
    {
        $this->selectedArticleIdx = $this->selectedArticleIdx - 1;
    }

    public function updatedSelectedArticleId($id)
    {
        $this->selectedArticleIdx = $this->batch->articles->search(function ($article) use ($id) {
            return $article->id == $id;
        });
    }

    public function publishBatchToIntegration()
    {
        dd($this->integration_id);
    }

    public function mount()
    {
        $this->id = \Route::current()->parameter('id');
        $this->batch = Batch::where('id', $this->id)->first();

        $this->integrationOptions = Integration::where('user_id', auth()->user()->id)->get()->toArray();
        if (count($this->integrationOptions) > 0) {
            $this->integration_id = $this->integrationOptions[0]['id'];
        }
    }

    public function render()
    {
        return view('livewire.history-view')->extends('layouts.dashboard')->section('dashboard-content');
    }
}
