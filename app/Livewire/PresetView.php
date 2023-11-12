<?php

namespace App\Livewire;

use App\Enums\BatchModeEnum;
use Livewire\Component;

class PresetView extends Component
{
    public $language = "English";
    public $generationMode;

    public function mount()
    {
        $this->generationMode = BatchModeEnum::CONTEXT->value;
    }

    public function save()
    {
        $this->titles;
        $this->keywords;
        $this->businessDescription;
    }

    public function render()
    {
        return view('livewire.preset-view')->layout('layouts.dashboard');
    }
}
