<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Enums\BatchModeEnum;

class PresetView extends Component
{
    public $language = "English";
    public $generationMode;
    public $extraLinksCount = [];
    public $externalLinksEnabled = false, $featureImageEnabled= true, $inArticleImageEnabled = false;
    public $automateYoutubeVideosEnabled = false;

    public function mount()
    {
        $this->generationMode = BatchModeEnum::CONTEXT->value;
    }

    public function incrementLinkCount()
    {
        $this->extraLinksCount[] = (string) Str::uuid();
    }

    public function removeLink($id)
    {
        $this->extraLinksCount = array_filter($this->extraLinksCount, function($link) use ($id) {
            return $link !== $id;
        });
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
