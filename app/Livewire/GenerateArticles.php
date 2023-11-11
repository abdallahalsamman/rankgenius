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

        const STATUS = [
            'DONE' => 'DONE',
            'CANCELED' => 'CANCELED',
            'GENERATING' => 'GENERATING',
        ];

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
                'status' => self::STATUS['GENERATING'],
            ]);

            redirect()->route('history');
        }
}
