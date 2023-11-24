<div>
    <x-header size="text-xl font-[700]" class="" title="Generate Articles"
        subtitle="Fill the information below to start generating articles for your business." />
    <x-tabs selected="simple-mode-tab">
        <x-tab name="simple-mode-tab" label="Simple mode">
            <x-form wire:submit="simpleMode">
                <x-instruction-step number-class="px-[10px] py-[4px]" number="1" class="font-bold mt-5"
                    instruction="Tell us your business" />
                <x-input wire:model="businessUrl" placeholder="Your business URL" suffix="Optional" />
                <x-textarea wire:model="businessDescription" placeholder="Description of your business" rows="3"
                    class="resize-none text-[16px]" maxlength="1024" />

                <x-instruction-step number-class="px-[10px] py-[4px]" number="2" class="font-bold mt-5"
                    instruction="Generate Articles" />

                <div class="grid grid-cols-[1fr_1fr_100px] gap-5">
                    @php
                        $article_counts = [];
                        foreach ($simple_mode_allowed_article_quantity as $i) {
                            $article_counts[] = ['id' => $i, 'name' => $i . ' ' . Str::plural('article', $i)];
                        }
                    @endphp
                    <x-select class="text-base" :options="$article_counts" wire:model="quantity" />

                    @php
                        $languages = json_decode(
                            '[{"id":"","name":"Afar"},{"id":"","name":"Abkhaz"},{"id":"","name":"Avestan"},{"id":"","name":"Afrikaans"},{"id":"","name":"Akan"},{"id":"","name":"Amharic"},{"id":"","name":"Aragonese"},{"id":"","name":"Arabic"},{"id":"","name":"Assamese"},{"id":"","name":"Avaric"},{"id":"","name":"Aymara"},{"id":"","name":"Azerbaijani"},{"id":"","name":"Bashkir"},{"id":"","name":"Belarusian"},{"id":"","name":"Bulgarian"},{"id":"","name":"Bislama"},{"id":"","name":"Bambara"},{"id":"","name":"Bengali"},{"id":"","name":"Tibetan"},{"id":"","name":"Breton"},{"id":"","name":"Bosnian"},{"id":"","name":"Catalan"},{"id":"","name":"Chechen"},{"id":"","name":"Chamorro"},{"id":"","name":"Corsican"},{"id":"","name":"Cree"},{"id":"","name":"Czech"},{"id":"","name":"Old Church Slavonic"},{"id":"","name":"Chuvash"},{"id":"","name":"Welsh"},{"id":"","name":"Danish"},{"id":"","name":"German"},{"id":"","name":"Divehi"},{"id":"","name":"Dzongkha"},{"id":"","name":"Ewe"},{"id":"","name":"Greek"},{"id":"","name":"English"},{"id":"-gb","name":"English (UK)"},{"id":"-us","name":"English (American)"},{"id":"","name":"Esperanto"},{"id":"","name":"Spanish"},{"id":"","name":"Estonian"},{"id":"","name":"Basque"},{"id":"","name":"Persian"},{"id":"","name":"Fula"},{"id":"","name":"Finnish"},{"id":"","name":"Fijian"},{"id":"","name":"Faroese"},{"id":"","name":"French"},{"id":"","name":"Western Frisian"},{"id":"","name":"Irish"},{"id":"","name":"Scottish Gaelic"},{"id":"","name":"Galician"},{"id":"","name":"Gujarati"},{"id":"","name":"Manx"},{"id":"","name":"Hausa"},{"id":"","name":"Hebrew"},{"id":"","name":"Hindi"},{"id":"","name":"Hiri Motu"},{"id":"","name":"Croatian"},{"id":"","name":"Haitian"},{"id":"","name":"Hungarian"},{"id":"","name":"Armenian"},{"id":"","name":"Herero"},{"id":"","name":"Interlingua"},{"id":"","name":"Indonesian"},{"id":"","name":"Interlingue"},{"id":"","name":"Igbo"},{"id":"","name":"Nuosu"},{"id":"","name":"Inupiaq"},{"id":"","name":"Ido"},{"id":"","name":"Icelandic"},{"id":"","name":"Italian"},{"id":"","name":"Inuktitut"},{"id":"","name":"Japanese"},{"id":"","name":"Javanese"},{"id":"","name":"Georgian"},{"id":"","name":"Kongo"},{"id":"","name":"Kikuyu"},{"id":"","name":"Kwanyama"},{"id":"","name":"Kazakh"},{"id":"","name":"Kalaallisut"},{"id":"","name":"Khmer"},{"id":"","name":"Kannada"},{"id":"","name":"Korean"},{"id":"","name":"Kanuri"},{"id":"","name":"Kashmiri"},{"id":"","name":"Kurdish"},{"id":"","name":"Komi"},{"id":"","name":"Cornish"},{"id":"","name":"Kyrgyz"},{"id":"","name":"Latin"},{"id":"","name":"Luxembourgish"},{"id":"","name":"Ganda"},{"id":"","name":"Limburgish"},{"id":"","name":"Lingala"},{"id":"","name":"Lao"},{"id":"","name":"Lithuanian"},{"id":"","name":"Luba-Katanga"},{"id":"","name":"Latvian"},{"id":"","name":"Malagasy"},{"id":"","name":"Marshallese"},{"id":"","name":"Macedonian"},{"id":"","name":"Malayalam"},{"id":"","name":"Mongolian"},{"id":"","name":"Marathi"},{"id":"","name":"Malay"},{"id":"","name":"Maltese"},{"id":"","name":"Burmese"},{"id":"","name":"Nauru"},{"id":"","name":"Northern Ndebele"},{"id":"","name":"Nepali"},{"id":"","name":"Ndonga"},{"id":"","name":"Dutch"},{"id":"","name":"Norwegian Nynorsk"},{"id":"","name":"Norwegian"},{"id":"","name":"Southern Ndebele"},{"id":"","name":"Navajo"},{"id":"","name":"Chichewa"},{"id":"","name":"Occitan"},{"id":"","name":"Ojibwe"},{"id":"","name":"Oromo"},{"id":"","name":"Oriya"},{"id":"","name":"Ossetian"},{"id":"","name":"Panjabi"},{"id":"","name":"Polish"},{"id":"","name":"Pashto"},{"id":"","name":"Portuguese"},{"id":"-pt","name":"Portuguese (Portugal)"},{"id":"","name":"Quechua"},{"id":"","name":"Romansh"},{"id":"","name":"Kirundi"},{"id":"","name":"Romanian"},{"id":"","name":"Russian"},{"id":"","name":"Kinyarwanda"},{"id":"","name":"Sanskrit"},{"id":"","name":"Sardinian"},{"id":"","name":"Sindhi"},{"id":"","name":"Northern Sami"},{"id":"","name":"Sango"},{"id":"","name":"Sinhala"},{"id":"","name":"Slovak"},{"id":"","name":"Slovenian"},{"id":"","name":"Samoan"},{"id":"","name":"Shona"},{"id":"","name":"Somali"},{"id":"","name":"Albanian"},{"id":"","name":"Serbian"},{"id":"","name":"Swati"},{"id":"","name":"Southern Sotho"},{"id":"","name":"Sundanese"},{"id":"","name":"Swedish"},{"id":"","name":"Swahili"},{"id":"","name":"Tamil"},{"id":"","name":"Telugu"},{"id":"","name":"Tajik"},{"id":"","name":"Thai"},{"id":"","name":"Tigrinya"},{"id":"","name":"Turkmen"},{"id":"","name":"Tagalog"},{"id":"","name":"Tswana"},{"id":"","name":"Tonga"},{"id":"","name":"Turkish"},{"id":"","name":"Tsonga"},{"id":"","name":"Tatar"},{"id":"","name":"Twi"},{"id":"","name":"Tahitian"},{"id":"","name":"Uyghur"},{"id":"","name":"Ukrainian"},{"id":"","name":"Urdu"},{"id":"","name":"Uzbek"},{"id":"","name":"Venda"},{"id":"","name":"Vietnamese"},{"id":"","name":"Walloon"},{"id":"","name":"Wolof"},{"id":"","name":"Xhosa"},{"id":"","name":"Yiddish"},{"id":"","name":"Yoruba"},{"id":"","name":"Zhuang"},{"id":"","name":"Chinese"},{"id":"","name":"Zulu"}]',
                            true,
                        );
                    @endphp
                    <x-select class="text-base" option_value="name" :options="$languages" wire:model="language" />

                    <x-button label="Generate" type="submit" class="bg-neutral-900 font-semibold hover:bg-gray-700 text-white text-base"
                        spinner="save2" />
                </div>

            </x-form>
        </x-tab>
        <x-tab name="titles-mode-tab" label="Titles mode">
            <div>
                <x-form wire:submit="titleMode">
                    <x-instruction-step number-class="px-[10px] py-[4px]" number="1" class="font-bold mt-5"
                        instruction="Write your titles (1 per line)" />
                    <x-textarea wire:model="titles"
                        placeholder="How to make bread at home?
Best recipes for making bread
What are the different types of bread?"
                        rows="7" class="resize-none text-[16px] leading-6" />
                    <x-instruction-step number-class="px-[10px] py-[4px]" number="2" class="font-bold mt-5"
                        instruction="Generate Articles" />
                    <div class="grid grid-cols-[1fr_100px] gap-5">
                        <x-select class="text-base" option_value="name" :options="$languages" wire:model="language" />
                        <x-button label="Generate" type="submit" class="bg-neutral-900 font-semibold text-white hover:bg-gray-700 text-base"
                            spinner="save2" />
                    </div>
                </x-form>
            </div>
        </x-tab>

        <x-tab name="keywords-mode-tab" label="Keywords mode">
            <div>
                <x-form wire:submit="keywordMode">
                    <x-instruction-step number-class="px-[10px] py-[4px]" number="1" class="font-bold mt-5"
                        instruction="Write your keywords (1 per line)" />
                    <x-textarea wire:model="keywords"
                        placeholder="How to make bread at home?
Best recipes for making bread
What are the different types of bread?"
                        rows="7" class="resize-none text-[16px] leading-6" />
                    <x-instruction-step number-class="px-[10px] py-[4px]" number="2" class="font-bold mt-5"
                        instruction="Generate Articles" />
                    <div class="grid grid-cols-[1fr_100px] gap-5">
                        <x-select class="text-base" option_value="name" :options="$languages" wire:model="language" />
                        <x-button label="Generate" type="submit" class="bg-neutral-900 font-semibold  hover:bg-gray-700 text-white text-base"
                            spinner="save2" />
                    </div>
                </x-form>
            </div>
        </x-tab>
        {{-- presets --}}
        <x-tab name="advanced-mode-tab" label="Advanced mode">
            <div>
                <x-form wire:submit="presetMode">
                    <div class="flex justify-between items-end">
                        <x-instruction-step number-class="px-[10px] py-[4px]" number="1" class="font-bold mt-5"
                            instruction="Choose your preset" />
                        <x-button icon="bi.plus" label="Create Preset" link="{{ route('preset.create') }}"
                            class="btn-xs border-black hover:bg-neutral-900 hover:text-white bg-white text-neutral-900" />
                    </div>
                    @php
                        $presets = array_merge([
                            ['id' => 0, 'name' => 'Please select a preset.', 'disabled' => true],
                        ], $presetOptions);
                    @endphp
                    <x-select class="text-base" :options="$presets" wire:model="preset" />
                    <x-instruction-step number-class="px-[10px] py-[4px]" number="2" class="font-bold mt-5"
                        instruction="Generate Articles" />
                    <div class="grid grid-cols-[1fr_100px] gap-5">
                        @php
                            $article_counts = [];
                            foreach ($preset_mode_allowed_article_quantity as $i) {
                                $article_counts[] = ['id' => $i, 'name' => $i . ' ' . Str::plural('article', $i)];
                            }
                        @endphp
                        <x-select class="text-base" :options="$article_counts" wire:model="quantity" />
                        <x-button label="Generate" type="submit" class="bg-neutral-900 font-semibold hover:bg-gray-700 text-white text-base"
                            spinner="save2" />
                    </div>
                </x-form>
            </div>
        </x-tab>
    </x-tabs>
</div>
