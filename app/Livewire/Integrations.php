<?php

namespace App\Livewire;

use Livewire\Component;

class Integrations extends Component
{
    public $integrations, $deleteModal = false, $integrationIdToDelete = null;

    public function deleteConfirm($id)
    {
        $this->integrationIdToDelete = $id;
        $this->deleteModal = true;
        $this->setAndSortPresets();
    }

    public function delete($id)
    {
        $this->integrations->find($id)->delete();
        $this->setAndSortPresets();
        $this->deleteModal = false;
    }

    public function setAndSortPresets()
    {
        $this->integrations = auth()->user()->integrations->sortBy('created_at');
    }

    public function mount()
    {

        $this->setAndSortPresets();
    }

    public function render()
    {
        return view('livewire.integrations')->extends('layouts.dashboard')->section('dashboard-content');
    }
}
