<?php

namespace App\Livewire;

use App\Models\Batch;
use Livewire\Component;
use Illuminate\Support\Str;

class GenerateArticles extends Component
{
    public $businessUrl, $businessDescription;
    public $quantity = 1, $language = "English";
    public $preset = 0;
    public $titles, $keywords;

    public $simple_mode_allowed_article_quantity = [1,3,5,10,20];
    public $preset_mode_allowed_article_quantity = [1,3,5,10,20,40,60,80,100,150,300];

    public function render()
    {
        return view('livewire.generate-articles')->layout('layouts.dashboard');
    }

    public function simpleMode() {
        $this->validate([
            'businessDescription' => "required|min:50|max:1050",
            'quantity' => "required|integer|in:" . join(",", $this->simple_mode_allowed_article_quantity)
        ]);

        $mode = "CONTEXT";
        $summary = trim($this->businessUrl . "\n" . $this->businessDescription);
        $quantity = $this->quantity;
        return $this->generateArticles($mode, $summary, $quantity);
    }

    public function titleMode() {
        $this->validate([
            'titles' => "required|min:10|max:2000",
        ]);

        $mode = "TITLES";
        $summary = $this->titles;
        $quantity = substr_count($this->titles, "\n") + 1;
        return $this->generateArticles($mode, $summary, $quantity);
    }

    public function keywordMode() {
        $mode = "KEYWORDS";
        $summary = $this->keywords;
        $quantity = substr_count($this->titles, "\n") + 1;
        return $this->generateArticles($mode, $summary, $quantity);
    }

    public function presetMode() {
        $this->validate([
            'quantity' => "required|integer|in:" . join(",", $this->preset_mode_allowed_article_quantity)
        ]);

        $mode = "PRESET";
        $summary = Preset::where('id', $this->preset)->first()->Name;
        $quantity = $this->quantity;
        return $this->generateArticles($mode, $summary, $quantity);
    }

    public function generateArticles($mode, $summary, $quantity)
    {
        Batch::create([
            'id' => Str::uuid(),
            'mode' => $mode,
            'summary' => $summary,
            'language' => $this->language,
            'quantity' => $quantity,
            'status' => Batch::$STATUS['IN_PROGRESS'],
        ]);

        return redirect()->route('history');
    }
}
