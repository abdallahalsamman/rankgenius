<?php

namespace App\Livewire;

use App\Enums\BatchModeEnum;
use App\Enums\BatchStatusEnum;
use App\Jobs\RunBatch;
use App\Models\Batch;
use App\Models\Preset;
use Livewire\Component;
use Illuminate\Support\Str;

class GenerateArticles extends Component
{
    public $url, $topic, $external_linking, $sitemap_url;
    public $quantity = 1, $language = "English";
    public $preset = 0, $presetOptions = [];
    public $titles = "How to make bread at home?\nBest recipes for making bread\nWhat are the different types of bread";
    public $keywords;

    public $simple_mode_allowed_article_quantity = [1, 3, 5, 10, 20];
    public $preset_mode_allowed_article_quantity = [1, 3, 5, 10, 20, 40, 60, 80, 100, 150, 300];

    public function mount()
    {
        // $this->presetOptions = auth()->user()->presets->toArray();
    }

    public function render()
    {
        return view('livewire.generate-articles')->extends('layouts.dashboard')->section('dashboard-content');
    }

    public function simpleMode()
    {
        $this->validateInputs('simple_mode_allowed_article_quantity', BatchModeEnum::CONTEXT);
    }

    public function presetMode()
    {
        $this->validateInputs('preset_mode_allowed_article_quantity', Preset::find($this->preset)->mode, true);
    }

    public function titleMode()
    {
        $this->validate([
            'titles' => "required|min:10|max:1024",
        ]);

        // remove empty lines
        $this->titles = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $this->titles);
        $quantity = substr_count($this->titles, "\n") + 1;
        $this->generateArticles([
            'mode' => BatchModeEnum::TITLE,
            'url' => null,
            'details' => $this->titles,
            'quantity' => $quantity,
            'language' => $this->language,
            'external_linking' => $this->external_linking,
            'sitemap_url' => $this->sitemap_url,
        ]);
    }

    public function keywordMode()
    {
        $this->validate([
            'keywords' => "required|min:10|max:1024",
        ]);

        // remove empty lines
        $this->keywords = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $this->keywords);
        $quantity = substr_count($this->keywords, "\n") + 1;
        $this->generateArticles([
            'mode' => BatchModeEnum::KEYWORD,
            'url' => null,
            'details' => $this->keywords,
            'quantity' => $quantity,
            'language' => $this->language,
            'external_linking' => $this->external_linking,
            'sitemap_url' => $this->sitemap_url,
        ]);
    }

    private function validateInputs($allowedQuantities, $mode, $isPreset = false)
    {
        $details = $isPreset ? Preset::find($this->preset)->details : $this->topic;
        $this->validate([
            'url' => "nullable|url",
            'topic' => "required|min:30|max:1024",
            'sitemap_url' => "nullable|url",
            'quantity' => "required|integer|in:" . join(",", $this->$allowedQuantities),
            'preset' => $isPreset ? "required|exists:presets,id" : "nullable",
        ]);

        $this->generateArticles([
            'mode' => $mode,
            'url' => $this->url,
            'details' => $details,
            'quantity' => $this->quantity,
            'language' => $this->language,
            'external_linking' => $this->external_linking,
            'sitemap_url' => $this->sitemap_url,
        ]);
    }

    private function generateArticles($data)
    {
        $batch = Batch::create([
            'id' => Str::uuid(),
            'mode' => $data['mode'],
            'url' => $data['url'],
            'details' => $data['details'],
            'language' => $data['language'],
            'quantity' => $data['quantity'],
            'status' => BatchStatusEnum::IN_PROGRESS,
            'user_id' => auth()->user()->id,
        ]);

        RunBatch::dispatch($batch);
        return redirect()->route('history.view', ['id' => $batch->id]);
    }
}
