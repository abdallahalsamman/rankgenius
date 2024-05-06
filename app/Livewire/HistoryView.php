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
        $this->selectedArticleId = $this->batch->articles[$this->selectedArticleIdx]->id;
    }

    public function previous()
    {
        $this->selectedArticleIdx = $this->selectedArticleIdx - 1;
        $this->selectedArticleId = $this->batch->articles[$this->selectedArticleIdx]->id;
    }

    public function updatedSelectedArticleId($id)
    {
        $this->selectedArticleIdx = $this->batch->articles->search($id);
    }

    public function publishBatchToIntegration()
    {
        if (!$this->integration_id) {
            return;
        }

        $integration = Integration::find($this->integration_id);
        $integration->publishBatch($this->batch);
    }

    public function mount()
    {
        $this->id = \Route::current()->parameter('id');
        $this->batch = Batch::find($this->id);

        $this->integrationOptions = auth()->user()->integrations()->get();
        $this->integration_id = $this->integrationOptions->first()?->id;
    }

    public function render()
    {
        return view('livewire.history-view')->extends('layouts.dashboard')->section('dashboard-content');
    }
}
