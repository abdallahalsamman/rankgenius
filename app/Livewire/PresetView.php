<?php

namespace App\Livewire;

use App\Models\Preset;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Enums\BatchModeEnum;
use Illuminate\Support\Facades\Route;

class PresetView extends Component
{

    public $action, $presetId, $stepNumber = 1, $totalSteps = 7;
    public $preset = [
        'name' => null,
        'generationMode' => null,
        'details' => null,
        'language' => "English",
        'creativity' => 9,
        'toneOfVoice' => null,
        'customInstructions' => null,
        'pointOfView' => "automatic",
        'callToAction' => null,
        'sitemapUrl' => null,
        'sitemapFilter' => null,
        'externalLinksEnabled' => false,
        'extraLinks' => [],
        'featureImageEnabled' => true,
        'inArticleImageEnabled' => false,
        'automateYoutubeVideosEnabled' => false,
        'youtubeVideos' => '',
    ];

    public function mount()
    {
        $this->preset['generationMode'] = BatchModeEnum::TOPIC->value;
        $this->action = Route::currentRouteName() === 'preset.create' ? "create" : "edit";

        if ($this->action === 'edit') {
            $this->presetId = Route::current()->parameter('id');
            $preset = Preset::where('id', $this->presetId)->first()->toArray();
            $this->preset = [
                'name' => $preset['name'],
                'generationMode' => $preset['mode'],
                'details' => $preset['details'],
                'language' => $preset['language'],
                'creativity' => $preset['creativity'],
                'toneOfVoice' => $preset['tone_of_voice'],
                'customInstructions' => $preset['custom_instructions'],
                'pointOfView' => $preset['point_of_view'],
                'callToAction' => $preset['call_to_action'],
                'sitemapUrl' => $preset['sitemap_url'],
                'sitemapFilter' => $preset['sitemap_filter'],
                'externalLinksEnabled' => $preset['automatic_external_link'],
                'extraLinks' => json_decode($preset['extra_links'], true),
                'featureImageEnabled' => $preset['featured_image_enabled'],
                'inArticleImageEnabled' => $preset['in_article_images'],
                'automateYoutubeVideosEnabled' => $preset['automatic_youtube_videos'],
                'youtubeVideos' => $preset['youtube_videos'],
            ];
        }
    }

    public function nextStep()
    {
        $this->validatePreset();

        if ($this->stepNumber < $this->totalSteps) {
            $this->stepNumber++;
        }
    }

    public function previousStep()
    {
        $this->validatePreset();

        if ($this->stepNumber > 1) {
            $this->stepNumber--;
        }
    }

    public function incrementLinkCount()
    {
        $this->preset['extraLinks'][(string) Str::uuid()] = [
            "url" => "",
            "anchor" => ""
        ];
    }

    public function removeLink($id)
    {
        unset($this->preset['extraLinks'][$id]);
    }

    private function validatePreset()
    {
        return $this->validate([
            'preset.name' => 'required|max:100',
            'preset.details' => 'required|min:50|max:1024',
            'preset.toneOfVoice' => 'nullable|max:80',
            'preset.customInstructions' => 'nullable|max:250',
            'preset.language' => 'nullable|max:20',
            'preset.creativity' => 'nullable|integer|min:0|max:20',
            'preset.pointOfView' => 'nullable|max:50',
            'preset.callToAction' => 'nullable|url:http,https|max:255',
            'preset.sitemapUrl' => 'nullable|url:http,https|max:255',
            'preset.sitemapFilter' => 'nullable|max:255',
            'preset.youtubeVideos' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    $urls = explode("\n", $value);

                    foreach ($urls as $url) {
                        $url = trim($url);

                        if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
                            $fail('One or more URLs in the ' . $attribute . ' field are not valid: ' . $url);
                        }
                    }
                },
                'max:1000',
            ],
            'preset.extraLinks.*.url' => 'nullable|url:http,https|max:255',
            'preset.extraLinks.*.anchor' => 'nullable|max:255',
        ], [
            'preset.name.required' => 'The name field is required.',
            'preset.name.max' => 'The name may not be greater than :max characters.',

            'preset.details.required' => 'The details field is required.',
            'preset.details.min' => 'The details must be at least :min characters.',
            'preset.details.max' => 'The details may not be greater than :max characters.',

            'preset.toneOfVoice.max' => 'The tone of voice may not be greater than :max characters.',

            'preset.customInstructions.max' => 'The custom instructions may not be greater than :max characters.',

            'preset.language.max' => 'The language may not be greater than :max characters.',

            'preset.creativity.integer' => 'The creativity must be an integer.',
            'preset.creativity.min' => 'The creativity must be at least :min.',
            'preset.creativity.max' => 'The creativity may not be greater than :max.',

            'preset.pointOfView.max' => 'The point of view may not be greater than :max characters.',

            'preset.callToAction.url' => 'The call to action must be a valid URL.',
            'preset.callToAction.max' => 'The call to action may not be greater than :max characters.',

            'preset.sitemapUrl.url' => 'The sitemap URL must be a valid URL.',
            'preset.sitemapUrl.max' => 'The sitemap URL may not be greater than :max characters.',

            'preset.sitemapFilter.max' => 'The sitemap filter may not be greater than :max characters.',

            'preset.youtubeVideos.string' => 'The YouTube videos must be a string.',
            'preset.youtubeVideos.max' => 'The YouTube videos may not be greater than :max characters.',

            'preset.extraLinks.*.url.url' => 'One or more URLs in the extra links field are not valid.',
            'preset.extraLinks.*.url.max' => 'One or more URLs in the extra links field may not be greater than :max characters.',

            'preset.extraLinks.*.anchor.max' => 'One or more anchors in the extra links field may not be greater than :max characters.',
        ]);
    }

    public function save()
    {
        if ($this->preset['automateYoutubeVideosEnabled']) {
            $this->preset['youtubeVideos'] = null;
        }

        $this->validatePreset();

        $details = [
            'name' => $this->preset['name'],
            'mode' => $this->preset['generationMode'],
            'details' => $this->preset['details'],
            'language' => $this->preset['language'],
            'creativity' => $this->preset['creativity'],
            'tone_of_voice' => $this->preset['toneOfVoice'],
            'custom_instructions' => $this->preset['customInstructions'],
            'point_of_view' => $this->preset['pointOfView'],
            'call_to_action' => $this->preset['callToAction'],
            'sitemap_url' => $this->preset['sitemapUrl'],
            'sitemap_filter' => $this->preset['sitemapFilter'],
            'automatic_external_link' => $this->preset['externalLinksEnabled'],
            'extra_links' => json_encode($this->preset['extraLinks']),
            'featured_image_enabled' => $this->preset['featureImageEnabled'],
            'in_article_images' => $this->preset['inArticleImageEnabled'],
            'automatic_youtube_videos' => $this->preset['automateYoutubeVideosEnabled'],
            'youtube_videos' => $this->preset['youtubeVideos'],
        ];

        if ($this->action == 'create') {
            Preset::create(array_merge([
                'id' => Str::uuid(),
                'user_id' => auth()->user()->id,
            ], $details));
        } else if ($this->action == 'edit') {
            Preset::updateOrCreate(['id' => $this->presetId], $details);

            // SHOW TOAST
        }

        return redirect()->route('presets');
    }

    public function render()
    {
        return view('livewire.preset-view')->extends('layouts.dashboard')->section('dashboard-content');
    }
}
