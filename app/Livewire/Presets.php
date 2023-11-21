<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;

class Presets extends Component
{
    public $presets , $deleteModal = false, $presetIdToDelete = null;


    public function deleteConfirm($id)
    {
        $this->presetIdToDelete = $id;
        $this->deleteModal = true;
        $this->setAndSortPresets();
    }

    public function delete($id)
    {
        $this->presets->find($id)->delete();
        $this->setAndSortPresets();
        $this->deleteModal = false;
    }

    public function clone($id)
    {
        $presetToClone = $this->presets->find($id);

        if ($presetToClone) {
            $clonedPreset = $presetToClone->replicate();
            $clonedPreset->id = (string) Str::uuid();
            $clonedPreset->name .= " (copy)";
            $clonedPreset->push();

            $this->setAndSortPresets();
        }

        return redirect()->route('preset.edit', ['id' => $clonedPreset->id]);
    }

    public function setAndSortPresets() {
        $this->presets = auth()->user()->presets->sortBy('created_at');
    }

    public function mount()
    {
        $this->setAndSortPresets();
    }

    public function render()
    {
        return view('livewire.presets')->extends('layouts.dashboard')->section('dashboard-content');
    }
}
