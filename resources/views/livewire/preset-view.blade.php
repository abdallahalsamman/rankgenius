<div>
    @php
    @endphp
    <x-header class="" size="text-xl font-[700] mb-10"
        subtitle="Create a new Preset that instructs ContentAIO the type of articles you want to generate."
        title="Presets / Create" />

    <x-form wire:submit="save">
        <div class="mb-4">
            <x-input label="Name" maxlength="100" placeholder="My Special Preset"
                wire:model="preset.name" />
        </div>
        <div>
            {{-- Number 1 --}}
            <div class="collapse-arrow collapse rounded-md rounded-b-none border border-base-300"
                tabindex="0">
                <input checked class="peer min-h-[10px]" type="checkbox" />
                <div
                    class="collapse-title min-h-[10px] bg-[#f7fafc] py-2 font-medium">
                    <x-instruction-step class="text-base" instruction="Base"
                        number-class="py-[2px] px-[8px]" number="1" />
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
                        <div class="mb-2 mr-3 pt-5 font-medium">Generation Mode
                        </div>
                        <x-select :options="$generationModes" class="text-base"
                            hint="Each option provides a different way to generate content."
                            wire:model.change="preset.generationMode" />
                        <div class="w-full py-2 text-center" wire:loading
                            wire:target="preset.generationMode">
                            <span
                                class="loading loading-dots loading-lg"></span>
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

                        <div class="pt-5" wire:loading.remove
                            wire:target="preset.generationMode">
                            <div class="mb-2 mr-3 font-medium">
                                {{ $generationOptions[$preset['generationMode']]['label'] }}
                            </div>
                            <x-textarea
                                class="max-h-[400px] min-h-[80px] text-[16px]"
                                maxlength="1024"
                                placeholder="{{ $generationOptions[$preset['generationMode']]['placeholder'] }}"
                                wire:model="preset.details" />
                        </div>
                    </div>
                </div>

            </div>

            {{-- Number 2 --}}
            <div class="collapse-arrow collapse rounded-none border border-t-0 border-base-300"
                tabindex="0">
                <input checked class="peer min-h-[10px]" type="checkbox" />
                <div
                    class="collapse-title min-h-[10px] bg-[#f7fafc] py-2 font-medium">
                    <x-instruction-step class="text-base" instruction="Content"
                        number-class="py-[2px] px-[8px]" number="2" />
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
                        <x-select :options="$languages" class="text-base"
                            option_value="name" wire:model="preset.language" />
                    </div>

                    <div class="mb-2 mr-3 pt-5 font-medium">Creativity</div>
                    <input class="range range-primary range-xs" max="20"
                        min="0" type="range" value="10"
                        wire:model="preset.creativity" />
                    <div class="flex justify-between text-xs">
                        <span>Correct/Factual</span><span>Creative/Original</span>
                    </div>

                    <div class="mb-2 mr-3 pt-5 font-medium">Tone of Voice</div>
                    <div class="max-w-sm">
                        <x-input maxlength="80" placeholder="Neutral"
                            wire:model="preset.toneOfVoice" />
                    </div>
                    <div class="mt-2 text-sm">Examples: <span
                            class="bg-[#e2e8f0] text-xs">funny</span> <span
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
                    <x-select :options="$pointOfViewOptions" class="text-base"
                        wire:model="preset.pointOfView" />
                    <div class="mt-4 max-w-md font-medium">
                        <div class="mb-2 flex items-center justify-between">
                            <div>Custom Instructions</div>
                            <div
                                class="rounded-md bg-[#feebc8] px-2 py-1 text-sm text-[#7b341e]">
                                Advanced</div>
                        </div>
                        <x-textarea
                            class="max-h-[400px] min-h-[80px] text-[16px]"
                            maxlength="250"
                            placeholder="Short and punchy phrases."
                            wire:model="preset.customInstructions" />
                    </div>
                    <div class="text-sm text-gray-600">We'll use these
                        instructions to generate each paragraph.</div>
                    <div class="text-sm text-gray-600">intructions <span
                            class="font-bold">do not affect the
                            headings.</span>
                    </div>
                </div>
            </div>

            {{-- Number 3 --}}
            <div class="collapse-arrow collapse rounded-none border border-t-0 border-base-300"
                tabindex="0">
                <input checked class="peer min-h-[10px]" type="checkbox" />
                <div
                    class="collapse-title min-h-[10px] bg-[#f7fafc] py-2 font-medium">
                    <x-instruction-step class="text-base"
                        instruction="Structure" number-class="py-[2px] px-[8px]"
                        number="3" />
                </div>
                <div class="collapse-content">
                    <div class="mb-2 mr-3 mt-5 font-medium">Call-To-Action</div>
                    <x-input class="mb-2" maxlength="255"
                        placeholder="https://mywebsite.com" type="text"
                        wire:model="preset.callToAction" />
                    <div class="text-sm">
                        We'll add an extra <span
                            class="bg-[#e2e8f0] px-0.5 text-xs">h3</span> to
                        your articles with a
                        call-to-action to this URL.
                        <br>
                        Leave blank to opt-out.
                    </div>
                    {{-- <div class="mb-2 mr-3 font-medium mt-5">Automate Headings</div>
                    <div class="w-fit">
                        <x-custom-toggle :enabled="0" label="Auto-generated" wire:model.change="item1" />
                    </div> --}}
                </div>
            </div>

            {{-- Number 4 --}}
            <div class="collapse-arrow collapse rounded-none border border-t-0 border-base-300"
                tabindex="0">
                <input checked class="peer min-h-[10px]" type="checkbox" />
                <div
                    class="collapse-title min-h-[10px] bg-[#f7fafc] py-2 font-medium">
                    <x-instruction-step class="text-base"
                        instruction="Internal Linking"
                        number-class="py-[2px] px-[8px]" number="4" />
                </div>
                <div class="collapse-content">
                    <div class="mb-2 mr-3 mt-5 font-medium">Sitemap URL</div>
                    <x-input class="mb-2" maxlength="255"
                        placeholder="https://mywebsite.com/sitemap.xml"
                        type="text" wire:model="preset.sitemapUrl" />
                    <div class="text-sm">A website can have multiple sitemaps.
                        Provide the sitemap of your blog posts.
                        <br>
                        Example: <a class="text-blue-500 hover:underline"
                            href="https://www.wpbeginner.com/post-sitemap.xml">https://www.wpbeginner.com/post-sitemap.xml</a>.
                    </div>

                    <div class="mb-2 mr-3 mt-5 font-medium">Filter Sitemap
                    </div>
                    <x-input class="mb-2" maxlength="255"
                        placeholder="/example/" type="text"
                        wire:model="preset.sitemapFilter" />
                    <div class="text-sm">
                        We will <strong>only</strong> use URLs from the sitemap
                        that contain this pattern.
                        <br>
                        Examples: <span
                            class="bg-[#e2e8f0] px-0.5 text-xs">/my-category/</span>,
                        <span
                            class="bg-[#e2e8f0] px-0.5 text-xs">/blog/</span>.
                    </div>
                </div>
            </div>

            {{-- Number 5 --}}
            <div class="collapse-arrow collapse rounded-none border border-t-0 border-base-300"
                tabindex="0">
                <input checked class="peer min-h-[10px]" type="checkbox" />
                <div
                    class="collapse-title min-h-[10px] bg-[#f7fafc] py-2 font-medium">
                    <x-instruction-step class="text-base"
                        instruction="External Linking"
                        number-class="py-[2px] px-[8px]" number="5" />
                </div>
                <div class="collapse-content">
                    <div class="mb-2 mr-3 mt-5 w-fit font-medium">
                        <label>
                            Automatic External Links
                            <div class="mt-3 w-fit">
                                <x-custom-toggle :enabled="$preset['externalLinksEnabled']"
                                    :label="$preset['externalLinksEnabled']
                                        ? 'Enabled'
                                        : 'Disabled'"
                                    wire:model.change="preset.externalLinksEnabled" />
                            </div>
                        </label>
                    </div>
                    <div class="mt-2 text-sm">We'll scrape the internet for
                        relevant articles in your niche & language.
                    </div>

                    <div class="mb-2 mr-3 mt-5 font-medium">Extra Links</div>
                    <div
                        class="my-3 grid w-full grid-cols-[1fr,1fr,1fr] text-center">
                        <span class="text-sm font-semibold">URL</span>
                        <span class="text-sm font-semibold">Anchor</span>
                    </div>
                    @foreach ($preset['extraLinks'] as $id => $extraLink)
                        <div class="my-3 grid w-full grid-cols-[1fr,1fr,1fr] items-center gap-5 text-center"
                            wire:key="{{ $id }}">
                            <x-input class="btn-sm text-sm" maxlength="255"
                                placeholder="{{ url('/') }}"
                                wire:model="preset.extraLinks.{{ $id }}.url">URL</x-input>
                            <x-input class="btn-sm text-sm" maxlength="255"
                                placeholder="Leave blank to auto generate"
                                wire:model="preset.extraLinks.{{ $id }}.anchor">Anchor</x-input>
                            <div class="flex items-center gap-3">
                                <x-button
                                    class="btn-xs w-fit border-transparent bg-transparent p-0 px-0.5"
                                    icon="m-minus-small"
                                    wire:click="removeLink('{{ $id }}')" />
                                <span
                                    class="loading loading-spinner loading-xs"
                                    wire:loading
                                    wire:target="preset.removeLink('{{ $id }}')"></span>
                            </div>
                        </div>
                    @endforeach
                    <div class="w-full py-2 text-center" wire:loading
                        wire:target="preset.incrementLinkCount">
                        <span class="loading loading-dots loading-lg"></span>
                    </div>
                    <x-button class="btn-primary btn-outline btn-xs"
                        icon="bi.plus" label="Add Link"
                        wire:click="incrementLinkCount" />
                    <div class="mt-2 text-sm">We'll randomly select up to 1
                        link per paragraph.</div>

                </div>
            </div>

            {{-- Number 6 --}}
            <div class="collapse-arrow collapse rounded-none border border-t-0 border-base-300"
                tabindex="0">
                <input checked class="peer min-h-[10px]" type="checkbox" />
                <div
                    class="collapse-title min-h-[10px] bg-[#f7fafc] py-2 font-medium">
                    <x-instruction-step class="text-base" instruction="Images"
                        number-class="py-[2px] px-[8px]" number="6" />
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
                <x-select :options="$users" wire:model="preset.selectedUser3" class="text-base" /> --}}
                    <div class="mb-2 mr-3 mt-5 w-fit font-medium">
                        <label>
                            Featured Image
                            <div class="mt-3 w-fit">
                                <x-custom-toggle :label="$preset['featureImageEnabled']
                                    ? 'Enabled'
                                    : 'Disabled'" :enabled="$preset['featureImageEnabled']"
                                    wire:model.change="preset.featureImageEnabled" />
                            </div>
                        </label>
                    </div>

                    <div class="mb-2 mr-3 mt-5 w-fit font-medium">
                        <label>
                            In-Article Images
                            <div class="mt-3 w-fit">
                                <x-custom-toggle :label="$preset['inArticleImageEnabled']
                                    ? 'Enabled'
                                    : 'Disabled'" :enabled="$preset['inArticleImageEnabled']"
                                    wire:model.change="preset.inArticleImageEnabled" />
                            </div>
                        </label>
                    </div>

                </div>
            </div>

            {{-- Number 7 --}}
            <div class="collapse-arrow collapse rounded-md rounded-t-none border border-t-0 border-base-300"
                tabindex="0">
                <input checked class="peer min-h-[10px]" type="checkbox" />
                <div
                    class="collapse-title min-h-[10px] bg-[#f7fafc] py-2 font-medium">
                    <x-instruction-step class="text-base" instruction="Videos"
                        number-class="py-[2px] px-[8px]" number="7" />
                </div>
                <div class="collapse-content">
                    <div class="mb-2 mr-3 mt-5 w-fit font-medium">
                        <label>
                            Automate Youtube Videos
                            <div class="mt-3 w-fit">
                                <x-custom-toggle :enabled="$preset[
                                    'automateYoutubeVideosEnabled'
                                ]"
                                    :label="$preset[
                                        'automateYoutubeVideosEnabled'
                                    ]
                                        ? 'Enabled'
                                        : 'Disabled'"
                                    wire:model.change="preset.automateYoutubeVideosEnabled" />
                            </div>
                        </label>
                    </div>
                    @if (!$preset['automateYoutubeVideosEnabled'])
                        <div class="mt-4 font-medium">
                            <div class="mb-2 mr-3 pt-5 font-medium">Youtube
                                Videos (1 link per line)</div>
                            <x-textarea
                                class="max-h-[400px] min-h-[80px] text-[16px]"
                                maxlength="1000" maxlength="1024"
                                placeholder="https://www.youtube.com/watch?v=P56_I4s9L9Q"
                                wire:model="preset.youtubeVideos" />
                        </div>
                        <div class="text-sm text-gray-600">We'll insert at
                            least one youtube video and place it in your
                            article.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="mt-5 grid w-full grid-cols-2 gap-5">
            <x-button
                class="btn-primary btn-outline w-full text-base text-base-100"
                label="Cancel" link="{{ route('presets') }}" />
            <x-button :label="$action === 'create' ? 'Create New Preset' : 'Save'"
                class="btn-primary w-full text-base text-base-100"
                type="submit" wire:loading.attr="disabled" />
        </div>
    </x-form>
</div>
