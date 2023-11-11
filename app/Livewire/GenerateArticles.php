<?php

namespace App\Livewire;

use App\Models\Batch;
use Livewire\Component;
use Illuminate\Support\Str;

class GenerateArticles extends Component
{
    public $businessUrl, $businessDescription;
    public $articleCount = 1, $outputLanguage = "en";
    public $preset = 0;

    public function mount()
    {
        $this->outputLanguage = "en";
    }

    public function render()
    {
        return view('livewire.generate-articles')->layout('layouts.dashboard');
    }

        public function generateArticles()
        {
            Batch::create([
                'id' => Str::uuid(),
                'summary' => $this->businessUrl . "\n" . $this->businessDescription,
                'status' => Batch::$STATUS['IN_PROGRESS'],
            ]);

            redirect()->route('history');
        }
}
