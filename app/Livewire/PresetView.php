<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Enums\BatchModeEnum;
use App\Models\Preset;

class PresetView extends Component
{

    public $language = "English";
    public $generationMode;
    public $extraLinks = [];

    public $externalLinksEnabled = false;
    public $featureImageEnabled = true;
    public $inArticleImageEnabled = false;
    public $automateYoutubeVideosEnabled = false;
    public $name;
    public $details;
    public $creativity = 10;
    public $toneOfVoice;
    public $customInstructions;
    public $pointOfView = "automatic";
    public $callToAction;
    public $sitemapUrl;
    public $sitemapFilter;
    public $youtubeVideos;

    public function mount()
    {
        $this->generationMode = BatchModeEnum::CONTEXT->value;
    }

    public function incrementLinkCount()
    {
        $this->extraLinks[(string) Str::uuid()] = [
            "url" => "",
            "anchor" => ""
        ];
    }

    public function removeLink($id)
    {
        unset($this->extraLinks[$id]);
    }

    public function save()
    {
        Preset::create([
            'id' => Str::uuid(),
            'name' => $this->name,
            'mode' => $this->generationMode,
            'details' => $this->details,
            'language' => $this->language,
            'creativity' => $this->creativity,
            'tone_of_voice' => $this->toneOfVoice,
            'custom_instructions' => $this->customInstructions,
            'point_of_view' => $this->pointOfView,
            'call_to_action' => $this->callToAction,
            'sitemap_url' => $this->sitemapUrl,
            'sitemap_filter' => $this->sitemapFilter,
            'automatic_external_link' => $this->externalLinksEnabled,
            'extra_links' => json_encode($this->extraLinks),
            'featured_image_enabled' => $this->featureImageEnabled,
            'in_article_images' => $this->inArticleImageEnabled,
            'automatic_youtube_videos' => $this->automateYoutubeVideosEnabled,
            'youtube_videos' => $this->automateYoutubeVideosEnabled ? null : $this->youtubeVideos,
            'user_id' => auth()->user()->id
        ]);
        return redirect()->route('presets');
    }

    public function render()
    {
        return view('livewire.preset-view')->layout('layouts.dashboard');
    }
}
