<?php

namespace App\Livewire;

use Livewire\Component;

class Presets extends Component
{
    public $presets;

    public function delete($id) {
        $this->presets->find($id)->delete();
        $this->presets = auth()->user()->presets;
    }

    public function mount()
    {
        $this->presets = auth()->user()->presets;
    }

    public function render()
    {
        return view('livewire.presets')->layout('layouts.dashboard');
    }
}
