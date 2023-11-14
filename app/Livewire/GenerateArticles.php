<?php

namespace App\Livewire;

use App\Enums\BatchModeEnum;
use App\Enums\BatchStatusEnum;
use App\Models\Batch;
use App\Models\Preset;
use Livewire\Component;
use Illuminate\Support\Str;

class GenerateArticles extends Component
{
    public $businessUrl, $businessDescription;
    public $quantity = 1, $language = "English";
    public $preset = 0, $presetOptions = [];
    public $titles, $keywords;

    public $simple_mode_allowed_article_quantity = [1, 3, 5, 10, 20];
    public $preset_mode_allowed_article_quantity = [1, 3, 5, 10, 20, 40, 60, 80, 100, 150, 300];

    public function render()
    {
        return view('livewire.generate-articles')->layout('layouts.dashboard');
    }

    public function simpleMode()
    {
        $this->validate([
            'businessDescription' => "required|min:50|max:1024",
            'quantity' => "required|integer|in:" . join(",", $this->simple_mode_allowed_article_quantity)
        ]);

        $mode = BatchModeEnum::CONTEXT;
        $details = trim($this->businessUrl . "\n" . $this->businessDescription);
        $quantity = $this->quantity;
        return $this->generateArticles($mode, $details, $quantity);
    }

    public function titleMode()
    {
        $this->validate([
            'titles' => "required|min:10|max:1024",
        ]);

        $mode = BatchModeEnum::TITLE;
        $details = $this->titles;
        $quantity = substr_count($this->titles, "\n") + 1;
        return $this->generateArticles($mode, $details, $quantity);
    }

    public function keywordMode()
    {
        $this->validate([
            'keywords' => "required|min:10|max:1024",
        ]);

        $mode = BatchModeEnum::KEYWORD;
        $details = $this->keywords;
        $quantity = substr_count($this->titles, "\n") + 1;
        return $this->generateArticles($mode, $details, $quantity);
    }

    public function presetMode()
    {
        $this->validate([
            'preset' =>"required|exists:presets,id",
            'quantity' => "required|integer|in:" . join(",", $this->preset_mode_allowed_article_quantity)
        ]);

        $preset = Preset::where('id', $this->preset)->first();
        $details = $preset->details;
        $mode = $preset->mode;
        $quantity = $this->quantity;
        return $this->generateArticles($mode, $details, $quantity);
    }

    public function mount()
    {
        $this->presetOptions = auth()->user()->presets->toArray();
    }

    public function generateArticles($mode, $details, $quantity)
    {
        Batch::create([
            'id' => Str::uuid(),
            'mode' => $mode,
            'details' => $details,
            'language' => $this->language,
            'quantity' => $quantity,
            'status' => BatchStatusEnum::IN_PROGRESS,
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('history');
    }
}
