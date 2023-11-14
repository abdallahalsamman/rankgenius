<div>
    <x-header size="text-xl font-[700] mb-10" class="" title="Presets / Create"
        subtitle="Create a new Preset that instructs Journalist AI the type of articles you want to generate." />

    <x-form wire:submit="save">
        <div class="mb-4">
            <x-input label="Name" placeholder="My Special Preset" wire:model="name" />
        </div>
        <div>
            {{-- Number 1 --}}
            <div tabindex="0" class="collapse collapse-arrow border border-base-300 rounded-md rounded-b-none">
                <input type="checkbox" class="peer min-h-[10px]" checked />
                <div class="collapse-title font-medium min-h-[10px] bg-[#f7fafc] py-2">
                    <x-instruction-step number="1" instruction="Base" class="text-base"
                        number-class="py-[2px] px-[8px]" />
                </div>

                <div class="collapse-content">
                    <div>
                        @php
                            $generationModes = [
                                [
                                    'id' => \App\Enums\BatchModeEnum::KEYWORD->value,
                                    'name' => 'Keyword-based',
                                ],
                                [
                                    'id' => \App\Enums\BatchModeEnum::TITLE->value,
                                    'name' => 'Specific Titles',
                                ],
                                [
                                    'id' => \App\Enums\BatchModeEnum::CONTEXT->value,
                                    'name' => 'Business Description',
                                ],
                            ];
                        @endphp
                        <div class="mb-2 mr-3 pt-5 font-medium">Generation Mode</div>
                        <x-select class="text-base" hint="Each option provides a different way to generate content."
                            :options="$generationModes" wire:model.change="generationMode" />
                        <div wire:loading wire:target="generationMode" class="w-full text-center py-2">
                            <span class="loading loading-dots loading-lg"></span>
                        </div>

                        @php
                            $generationOptions = [
                                \App\Enums\BatchModeEnum::CONTEXT->value => [
                                    'label' => 'Context Description',
                                    'placeholder' => 'SpaceX is a company that produces rockets.',
                                ],
                                \App\Enums\BatchModeEnum::TITLE->value => [
                                    'label' => 'Titles (1 per line)',
                                    'placeholder' => 'How to bake bread?',
                                ],
                                \App\Enums\BatchModeEnum::KEYWORD->value => [
                                    'label' => 'Keywords (1 per line)',
                                    'placeholder' => 'How to bake bread?',
                                ],
                            ];
                        @endphp

                        <div wire:loading.remove wire:target="generationMode" class="pt-5">
                            <div class="mb-2 mr-3 font-medium">{{ $generationOptions[$generationMode]['label'] }}</div>
                            <x-textarea wire:model="details"
                                placeholder="{{ $generationOptions[$generationMode]['placeholder'] }}"
                                class="text-[16px] min-h-[80px] max-h-[400px]" maxlength="1024" />
                        </div>
                    </div>
                </div>

            </div>

            {{-- Number 2 --}}
            <div tabindex="0" class="collapse collapse-arrow border border-base-300 border-t-0 rounded-none">
                <input type="checkbox" class="peer min-h-[10px]" checked />
                <div class="collapse-title font-medium min-h-[10px] bg-[#f7fafc] py-2">
                    <x-instruction-step number="2" instruction="Content" class="text-base"
                        number-class="py-[2px] px-[8px]" />
                </div>

                <div class="collapse-content">

                    <div>
                        @php
                            $languages = json_decode(
                                '[{"id":"","name":"Afar"},{"id":"","name":"Abkhaz"},{"id":"","name":"Avestan"},{"id":"","name":"Afrikaans"},{"id":"","name":"Akan"},{"id":"","name":"Amharic"},{"id":"","name":"Aragonese"},{"id":"","name":"Arabic"},{"id":"","name":"Assamese"},{"id":"","name":"Avaric"},{"id":"","name":"Aymara"},{"id":"","name":"Azerbaijani"},{"id":"","name":"Bashkir"},{"id":"","name":"Belarusian"},{"id":"","name":"Bulgarian"},{"id":"","name":"Bislama"},{"id":"","name":"Bambara"},{"id":"","name":"Bengali"},{"id":"","name":"Tibetan"},{"id":"","name":"Breton"},{"id":"","name":"Bosnian"},{"id":"","name":"Catalan"},{"id":"","name":"Chechen"},{"id":"","name":"Chamorro"},{"id":"","name":"Corsican"},{"id":"","name":"Cree"},{"id":"","name":"Czech"},{"id":"","name":"Old Church Slavonic"},{"id":"","name":"Chuvash"},{"id":"","name":"Welsh"},{"id":"","name":"Danish"},{"id":"","name":"German"},{"id":"","name":"Divehi"},{"id":"","name":"Dzongkha"},{"id":"","name":"Ewe"},{"id":"","name":"Greek"},{"id":"","name":"English"},{"id":"-gb","name":"English (UK)"},{"id":"-us","name":"English (American)"},{"id":"","name":"Esperanto"},{"id":"","name":"Spanish"},{"id":"","name":"Estonian"},{"id":"","name":"Basque"},{"id":"","name":"Persian"},{"id":"","name":"Fula"},{"id":"","name":"Finnish"},{"id":"","name":"Fijian"},{"id":"","name":"Faroese"},{"id":"","name":"French"},{"id":"","name":"Western Frisian"},{"id":"","name":"Irish"},{"id":"","name":"Scottish Gaelic"},{"id":"","name":"Galician"},{"id":"","name":"Gujarati"},{"id":"","name":"Manx"},{"id":"","name":"Hausa"},{"id":"","name":"Hebrew"},{"id":"","name":"Hindi"},{"id":"","name":"Hiri Motu"},{"id":"","name":"Croatian"},{"id":"","name":"Haitian"},{"id":"","name":"Hungarian"},{"id":"","name":"Armenian"},{"id":"","name":"Herero"},{"id":"","name":"Interlingua"},{"id":"","name":"Indonesian"},{"id":"","name":"Interlingue"},{"id":"","name":"Igbo"},{"id":"","name":"Nuosu"},{"id":"","name":"Inupiaq"},{"id":"","name":"Ido"},{"id":"","name":"Icelandic"},{"id":"","name":"Italian"},{"id":"","name":"Inuktitut"},{"id":"","name":"Japanese"},{"id":"","name":"Javanese"},{"id":"","name":"Georgian"},{"id":"","name":"Kongo"},{"id":"","name":"Kikuyu"},{"id":"","name":"Kwanyama"},{"id":"","name":"Kazakh"},{"id":"","name":"Kalaallisut"},{"id":"","name":"Khmer"},{"id":"","name":"Kannada"},{"id":"","name":"Korean"},{"id":"","name":"Kanuri"},{"id":"","name":"Kashmiri"},{"id":"","name":"Kurdish"},{"id":"","name":"Komi"},{"id":"","name":"Cornish"},{"id":"","name":"Kyrgyz"},{"id":"","name":"Latin"},{"id":"","name":"Luxembourgish"},{"id":"","name":"Ganda"},{"id":"","name":"Limburgish"},{"id":"","name":"Lingala"},{"id":"","name":"Lao"},{"id":"","name":"Lithuanian"},{"id":"","name":"Luba-Katanga"},{"id":"","name":"Latvian"},{"id":"","name":"Malagasy"},{"id":"","name":"Marshallese"},{"id":"","name":"Macedonian"},{"id":"","name":"Malayalam"},{"id":"","name":"Mongolian"},{"id":"","name":"Marathi"},{"id":"","name":"Malay"},{"id":"","name":"Maltese"},{"id":"","name":"Burmese"},{"id":"","name":"Nauru"},{"id":"","name":"Northern Ndebele"},{"id":"","name":"Nepali"},{"id":"","name":"Ndonga"},{"id":"","name":"Dutch"},{"id":"","name":"Norwegian Nynorsk"},{"id":"","name":"Norwegian"},{"id":"","name":"Southern Ndebele"},{"id":"","name":"Navajo"},{"id":"","name":"Chichewa"},{"id":"","name":"Occitan"},{"id":"","name":"Ojibwe"},{"id":"","name":"Oromo"},{"id":"","name":"Oriya"},{"id":"","name":"Ossetian"},{"id":"","name":"Panjabi"},{"id":"","name":"Polish"},{"id":"","name":"Pashto"},{"id":"","name":"Portuguese"},{"id":"-pt","name":"Portuguese (Portugal)"},{"id":"","name":"Quechua"},{"id":"","name":"Romansh"},{"id":"","name":"Kirundi"},{"id":"","name":"Romanian"},{"id":"","name":"Russian"},{"id":"","name":"Kinyarwanda"},{"id":"","name":"Sanskrit"},{"id":"","name":"Sardinian"},{"id":"","name":"Sindhi"},{"id":"","name":"Northern Sami"},{"id":"","name":"Sango"},{"id":"","name":"Sinhala"},{"id":"","name":"Slovak"},{"id":"","name":"Slovenian"},{"id":"","name":"Samoan"},{"id":"","name":"Shona"},{"id":"","name":"Somali"},{"id":"","name":"Albanian"},{"id":"","name":"Serbian"},{"id":"","name":"Swati"},{"id":"","name":"Southern Sotho"},{"id":"","name":"Sundanese"},{"id":"","name":"Swedish"},{"id":"","name":"Swahili"},{"id":"","name":"Tamil"},{"id":"","name":"Telugu"},{"id":"","name":"Tajik"},{"id":"","name":"Thai"},{"id":"","name":"Tigrinya"},{"id":"","name":"Turkmen"},{"id":"","name":"Tagalog"},{"id":"","name":"Tswana"},{"id":"","name":"Tonga"},{"id":"","name":"Turkish"},{"id":"","name":"Tsonga"},{"id":"","name":"Tatar"},{"id":"","name":"Twi"},{"id":"","name":"Tahitian"},{"id":"","name":"Uyghur"},{"id":"","name":"Ukrainian"},{"id":"","name":"Urdu"},{"id":"","name":"Uzbek"},{"id":"","name":"Venda"},{"id":"","name":"Vietnamese"},{"id":"","name":"Walloon"},{"id":"","name":"Wolof"},{"id":"","name":"Xhosa"},{"id":"","name":"Yiddish"},{"id":"","name":"Yoruba"},{"id":"","name":"Zhuang"},{"id":"","name":"Chinese"},{"id":"","name":"Zulu"}]',
                                true,
                            );
                        @endphp
                        <div class="mb-2 mr-3 pt-5 font-medium">Language</div>
                        <x-select class="text-base" option_value="name" :options="$languages" wire:model="language" />
                    </div>

                    <div class="mb-2 mr-3 pt-5 font-medium">Creativity</div>
                    <input type="range" min="0" max="20" value="10" wire:model="creativity"
                        class="range range-xs range-primary" />
                    <div class="flex justify-between text-xs">
                        <span>Correct/Factual</span><span>Creative/Original</span>
                    </div>

                    <div class="mb-2 mr-3 pt-5 font-medium">Tone of Voice</div>
                    <div class="max-w-sm">
                        <x-input placeholder="Neutral" maxlength="80" wire:model="toneOfVoice" />
                    </div>
                    <div class="text-sm mt-2">Examples: <span class="bg-[#e2e8f0] text-xs">funny</span> <span
                            class="bg-[#e2e8f0] text-xs">informal</span> <span
                            class="bg-[#e2e8f0] text-xs">academic</span>
                    </div>
                    <div class="mb-2 mr-3 pt-5 font-medium">Point of View</div>
                    @php
                        $pointOfViewOptions = [
                            [
                                'id' => 1,
                                'name' => 'Automatic',
                            ],
                            [
                                'id' => 2,
                                'name' => 'First Person Plural (we, us, our, ours)',
                            ],
                            [
                                'id' => 2,
                                'name' => 'First Person Singular (I, me, my, mine)',
                            ],
                            [
                                'id' => 2,
                                'name' => 'Second Person (you, your, yours)',
                            ],
                            [
                                'id' => 2,
                                'name' => 'Third Person (he, she, it, they)',
                            ],
                        ];
                    @endphp
                    <x-select :options="$pointOfViewOptions" wire:model="pointOfView" class="text-base" />
                    <div class="font-medium mt-4 max-w-md">
                        <div class="flex justify-between mb-2 items-center">
                            <div>Custom Instructions</div>
                            <div class="bg-[#feebc8] text-[#7b341e] text-sm rounded-md px-2 py-1">Advanced</div>
                        </div>
                        <x-textarea wire:model="customInstructions" placeholder="Short and punchy phrases."
                            class="text-[16px] min-h-[80px] max-h-[400px]" maxlength="250" />
                    </div>
                    <div class="text-sm text-gray-600">We'll use these instructions to generate each paragraph.</div>
                    <div class="text-sm text-gray-600">intructions <span class="font-bold">do not affect the
                            headings.</span>
                    </div>
                </div>
            </div>

            {{-- Number 3 --}}
            <div tabindex="0" class="collapse collapse-arrow border border-base-300 border-t-0 rounded-none">
                <input type="checkbox" class="peer min-h-[10px]" checked />
                <div class="collapse-title font-medium min-h-[10px] bg-[#f7fafc] py-2">
                    <x-instruction-step number="3" instruction="Structure" class="text-base"
                        number-class="py-[2px] px-[8px]" />
                </div>
                <div class="collapse-content">
                    <div class="mb-2 mr-3 font-medium mt-5">Call-To-Action</div>
                    <x-input type="text" maxlength="255" placeholder="https://mywebsite.com" wire:model="callToAction"
                        class="mb-2" />
                    <div class="text-sm">
                        We'll add an extra <span class="bg-[#e2e8f0] text-xs px-0.5">h3</span> to your articles with a
                        call-to-action to this URL.
                        <br>
                        Leave blank to opt-out.
                    </div>
                    {{-- <div class="mb-2 mr-3 font-medium mt-5">Automate Headings</div>
                    <div class="w-fit">
                        <x-toggle label="Auto-generated" wire:change="item1" />
                    </div> --}}
                </div>
            </div>

            {{-- Number 4 --}}
            <div tabindex="0" class="collapse collapse-arrow border border-base-300 border-t-0 rounded-none">
                <input type="checkbox" class="peer min-h-[10px]" checked />
                <div class="collapse-title font-medium min-h-[10px] bg-[#f7fafc] py-2">
                    <x-instruction-step number="4" instruction="Internal Linking" class="text-base"
                        number-class="py-[2px] px-[8px]" />
                </div>
                <div class="collapse-content">
                    <div class="mb-2 mr-3 font-medium mt-5">Sitemap URL</div>
                    <x-input type="text" maxlength="255" wire:model="sitemapUrl" placeholder="https://mywebsite.com/sitemap.xml"
                        class="mb-2" />
                    <div class="text-sm">A website can have multiple sitemaps. Provide the sitemap of your blog posts.
                        <br>
                        Example: <a class="text-blue-500 hover:underline"
                            href="https://www.wpbeginner.com/post-sitemap.xml">https://www.wpbeginner.com/post-sitemap.xml</a>.
                    </div>

                    <div class="mb-2 mr-3 font-medium mt-5">Filter Sitemap</div>
                    <x-input type="text" maxlength="255" wire:model="sitemapFilter" placeholder="/example/" class="mb-2" />
                    <div class="text-sm">
                        We will <strong>only</strong> use URLs from the sitemap that contain this pattern.
                        <br>
                        Examples: <span class="bg-[#e2e8f0] text-xs px-0.5">/my-category/</span>, <span
                            class="bg-[#e2e8f0] text-xs px-0.5">/blog/</span>.
                    </div>
                </div>
            </div>

            {{-- Number 5 --}}
            <div tabindex="0" class="collapse collapse-arrow border border-base-300 border-t-0 rounded-none">
                <input type="checkbox" class="peer min-h-[10px]" checked />
                <div class="collapse-title font-medium min-h-[10px] bg-[#f7fafc] py-2">
                    <x-instruction-step number="5" instruction="External Linking" class="text-base"
                        number-class="py-[2px] px-[8px]" />
                </div>
                <div class="collapse-content">
                    <div class="mb-2 mr-3 font-medium mt-5 w-fit">
                        <label>
                            Automatic External Links
                            <div class="mt-3 w-fit">
                                <x-toggle :label="$externalLinksEnabled ? 'Enabled' : 'Disabled'" wire:model.change="externalLinksEnabled" />
                            </div>
                        </label>
                    </div>
                    <div class="text-sm mt-2">We'll scrape the internet for relevant articles in your niche & language.
                    </div>

                    <div class="mb-2 mr-3 font-medium mt-5">Extra Links</div>
                    <div class="grid grid-cols-[1fr,1fr,1fr] w-full my-3 text-center">
                        <span class="text-sm font-semibold">URL</span>
                        <span class="text-sm font-semibold">Anchor</span>
                    </div>
                    @foreach ($extraLinks as $id => $extraLink)
                        <div wire:key="{{ $id }}"
                            class="grid grid-cols-[1fr,1fr,1fr] gap-5 w-full my-3 text-center items-center">
                            <x-input class="btn-sm text-sm" maxlength="255" wire:model="extraLinks.{{ $id }}.url"
                                placeholder="{{ url('/') }}">URL</x-input>
                            <x-input class="btn-sm text-sm" maxlength="255" wire:model="extraLinks.{{ $id }}.anchor"
                                placeholder="Leave blank to auto generate">Anchor</x-input>
                            <div class="flex items-center gap-3">
                                <x-button icon="m-minus-small"
                                    class="btn-xs border-transparent bg-transparent w-fit p-0 px-0.5"
                                    wire:click="removeLink('{{ $id }}')" />
                                <span wire:loading wire:target="removeLink('{{ $id }}')"
                                    class="loading loading-spinner loading-xs"></span>
                            </div>
                        </div>
                    @endforeach
                    <div wire:loading wire:target="incrementLinkCount" class="w-full text-center py-2">
                        <span class="loading loading-dots loading-lg"></span>
                    </div>
                    <x-button icon="bi.plus" label="Add Link" class="btn-outline btn-xs btn-primary"
                        wire:click="incrementLinkCount" />
                    <div class="text-sm mt-2">We'll randomly select up to 1 link per paragraph.</div>

                </div>
            </div>

            {{-- Number 6 --}}
            <div tabindex="0" class="collapse collapse-arrow border border-base-300 border-t-0 rounded-none">
                <input type="checkbox" class="peer min-h-[10px]" checked />
                <div class="collapse-title font-medium min-h-[10px] bg-[#f7fafc] py-2">
                    <x-instruction-step number="6" instruction="Images" class="text-base"
                        number-class="py-[2px] px-[8px]" />
                </div>
                <div class="collapse-content">
                    {{-- <div class="mb-2 mr-3 font-medium mt-5">Image Provider</div>
                    @php
                    $users = [
                        [
                            'id' => 1,
                            'name' => 'AI Images (+1 credit)',
                            'class' => 'font-bold text-red',
                        ],
                        [
                            'id' => 2,
                            'name' => 'Google License-Free Photos',
                        ],
                        [
                            'id' => 2,
                            'name' => 'Pexels Stock Images',
                        ],
                        [
                            'id' => 2,
                            'name' => 'Unsplash Stock Images',
                        ],
                    ];
                @endphp
                <x-select :options="$users" wire:model="selectedUser3" class="text-base" /> --}}
                    <div class="mb-2 mr-3 font-medium mt-5 w-fit">
                        <label>
                            Featured Image
                            <div class="mt-3 w-fit">
                                <x-toggle :label="$featureImageEnabled ? 'Enabled' : 'Disabled'" wire:model.change="featureImageEnabled" />
                            </div>
                        </label>
                    </div>

                    <div class="mb-2 mr-3 font-medium mt-5 w-fit">
                        <label>
                            In-Article Images
                            <div class="mt-3 w-fit">
                                <x-toggle :label="$inArticleImageEnabled ? 'Enabled' : 'Disabled'" wire:model.change="inArticleImageEnabled" />
                            </div>
                        </label>
                    </div>

                </div>
            </div>

            {{-- Number 7 --}}
            <div tabindex="0"
                class="collapse collapse-arrow border border-base-300 border-t-0 rounded-md rounded-t-none">
                <input type="checkbox" class="peer min-h-[10px]" checked />
                <div class="collapse-title font-medium min-h-[10px] bg-[#f7fafc] py-2">
                    <x-instruction-step number="7" instruction="Videos" class="text-base"
                        number-class="py-[2px] px-[8px]" />
                </div>
                <div class="collapse-content">
                    <div class="mb-2 mr-3 font-medium mt-5 w-fit">
                        <label>
                            Automate Youtube Videos
                            <div class="mt-3 w-fit">
                                <x-toggle :label="$automateYoutubeVideosEnabled ? 'Enabled' : 'Disabled'" wire:model.change="automateYoutubeVideosEnabled" />
                            </div>
                        </label>
                    </div>
                    @if (!$automateYoutubeVideosEnabled)
                        <div class="font-medium mt-4">
                            <div class="mb-2 mr-3 pt-5 font-medium">Youtube Videos (1 link per line)</div>
                            <x-textarea wire:model="youtubeVideos" maxlength="1000"
                                placeholder="https://www.youtube.com/watch?v=P56_I4s9L9Q"
                                class="text-[16px] min-h-[80px] max-h-[400px]" maxlength="1024" />
                        </div>
                        <div class="text-sm text-gray-600">We'll insert at least one youtube video and place it in your
                            article.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-5 w-full mt-5">
            <x-button label="Cancel" link="{{ route('presets') }}"
                class="btn-primary btn-outline text-base text-base-100 w-full" />
            <x-button type="submit" label="Create New Preset" class="btn-primary text-base text-base-100 w-full" />
        </div>
    </x-form>
</div>
