<?php

// namespace App\Livewire;

// use App\Models\Preset;
// use Livewire\Component;

// class PresetEdit extends Component
// {
//     public $preset;
//     public $presetInstance;

//     private function validatePreset()
//     {
//         $this->validate([
//             'preset.name' => 'required|max:50',
//             'preset.details' => 'required|min:50|max:1024',
//             'preset.toneOfVoice' => 'nullable|max:80',
//             'preset.customInstructions' => 'nullable|max:250',
//             'preset.language' => 'nullable|max:20',
//             'preset.creativity' => 'nullable|integer|min:0|max:20',
//             'preset.pointOfView' => 'nullable|max:50',
//             'preset.callToAction' => 'nullable|url:http,https|max:255',
//             'preset.sitemapUrl' => 'nullable|url:http,https|max:255',
//             'preset.sitemapFilter' => 'nullable|max:255',
//             'preset.youtubeVideos' => [
//                 'nullable',
//                 'string',
//                 function ($attribute, $value, $fail) {
//                     $urls = explode("\n", $value);

//                     foreach ($urls as $url) {
//                         $url = trim($url);

//                         if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
//                             $fail('One or more URLs in the ' . $attribute . ' field are not valid: ' . $url);
//                         }
//                     }
//                 },
//                 'max:1000',
//             ],
//             'preset.extraLinks.*.url' => 'nullable|url:http,https|max:255',
//             'preset.extraLinks.*.anchor' => 'nullable|max:255',
//         ], [
//             'preset.details.required' => 'Please fill this field.',
//         ]);
//     }

//     public function save()
//     {
//         $this->validatePreset();

//         Preset::updateOrCreate(['id' => $this->presetInstance], [
//             'name' => $this->preset['name'],
//             'mode' => $this->preset['generationMode'],
//             'details' => $this->preset['details'],
//             'language' => $this->preset['language'],
//             'creativity' => $this->preset['creativity'],
//             'tone_of_voice' => $this->preset['toneOfVoice'],
//             'custom_instructions' => $this->preset['customInstructions'],
//             'point_of_view' => $this->preset['pointOfView'],
//             'call_to_action' => $this->preset['callToAction'],
//             'sitemap_url' => $this->preset['sitemapUrl'],
//             'sitemap_filter' => $this->preset['sitemapFilter'],
//             'automatic_external_link' => $this->preset['externalLinksEnabled'],
//             'extra_links' => json_encode($this->preset['extraLinks']),
//             'featured_image_enabled' => $this->preset['featureImageEnabled'],
//             'in_article_images' => $this->preset['inArticleImageEnabled'],
//             'automatic_youtube_videos' => $this->preset['automateYoutubeVideosEnabled'],
//             'youtube_videos' => $this->preset['automateYoutubeVideosEnabled'] ? null : $this->preset['youtubeVideos'],
//         ]);

//         return redirect()->route('presets');
//     }

//     public function mount()
//     {
//         $this->id = \Route::current()->parameter('id');
//         $preset = Preset::where('id', $this->id)->first()->toArray();
//         $this->presetInstance = $preset;
//         $this->preset = [
//             'name' => $preset['name'],
//             'generationMode' => $preset['mode'],
//             'details' => $preset['details'],
//             'language' => $preset['language'],
//             'creativity' => $preset['creativity'],
//             'toneOfVoice' => $preset['tone_of_voice'],
//             'customInstructions' => $preset['custom_instructions'],
//             'pointOfView' => $preset['point_of_view'],
//             'callToAction' => $preset['call_to_action'],
//             'sitemapUrl' => $preset['sitemap_url'],
//             'sitemapFilter' => $preset['sitemap_filter'],
//             'externalLinksEnabled' => $preset['automatic_external_link'],
//             'extraLinks' => json_decode($preset['extra_links']),
//             'featureImageEnabled' => $preset['featured_image_enabled'],
//             'inArticleImageEnabled' => $preset['in_article_images'],
//             'automateYoutubeVideosEnabled' => $preset['automatic_youtube_videos'],
//             'youtubeVideos' => $preset['youtube_videos'],
//         ];
//     }

//     public function render()
//     {
//         return view('livewire.preset-view')->layout('layouts.dashboard');
//     }
// }
