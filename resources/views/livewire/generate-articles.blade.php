<div>
    <x-header class="text-[40px]" title="Generate Articles" subtitle="Fill the information below to start generating articles for your business." />

    <x-tabs wire:model="selectedTab" selected="simple-mode-tab">

        <x-tab name="simple-mode-tab" label="Simple mode">
            <x-form>
                <x-instruction-step number="1" instruction="Tell us your business" />
                <x-input wire:model="businessUrl" placeholder="Your business URL" suffix="Optional" />
                <x-textarea wire:model="businessDescription" placeholder="Description of your business" rows="3" class="resize-none text-[16px]" />

                <x-instruction-step number="2" instruction="Generate Articles" />

                <div class="grid grid-cols-[1fr_1fr_100px] gap-5">
                    @php
                    $article_counts = [];
                    foreach ([1,3,5,10,20] as $i)
                    {
                    $article_counts[] = ["id" => $i, "name" => $i . " article" . ($i === 1 ? "" : "s")];
                    }
                    @endphp
                    <x-select class="text-base" :options="$article_counts" wire:model="articleCount" />

                    @php
                    $languages = json_decode('[{"id":"aa","name":"Afar"},{"id":"ab","name":"Abkhaz"},{"id":"ae","name":"Avestan"},{"id":"af","name":"Afrikaans"},{"id":"ak","name":"Akan"},{"id":"am","name":"Amharic"},{"id":"an","name":"Aragonese"},{"id":"ar","name":"Arabic"},{"id":"as","name":"Assamese"},{"id":"av","name":"Avaric"},{"id":"ay","name":"Aymara"},{"id":"az","name":"Azerbaijani"},{"id":"ba","name":"Bashkir"},{"id":"be","name":"Belarusian"},{"id":"bg","name":"Bulgarian"},{"id":"bi","name":"Bislama"},{"id":"bm","name":"Bambara"},{"id":"bn","name":"Bengali"},{"id":"bo","name":"Tibetan"},{"id":"br","name":"Breton"},{"id":"bs","name":"Bosnian"},{"id":"ca","name":"Catalan"},{"id":"ce","name":"Chechen"},{"id":"ch","name":"Chamorro"},{"id":"co","name":"Corsican"},{"id":"cr","name":"Cree"},{"id":"cs","name":"Czech"},{"id":"cu","name":"Old Church Slavonic"},{"id":"cv","name":"Chuvash"},{"id":"cy","name":"Welsh"},{"id":"da","name":"Danish"},{"id":"de","name":"German"},{"id":"dv","name":"Divehi"},{"id":"dz","name":"Dzongkha"},{"id":"ee","name":"Ewe"},{"id":"el","name":"Greek"},{"id":"en","name":"English"},{"id":"en-gb","name":"English (UK)"},{"id":"en-us","name":"English (American)"},{"id":"eo","name":"Esperanto"},{"id":"es","name":"Spanish"},{"id":"et","name":"Estonian"},{"id":"eu","name":"Basque"},{"id":"fa","name":"Persian"},{"id":"ff","name":"Fula"},{"id":"fi","name":"Finnish"},{"id":"fj","name":"Fijian"},{"id":"fo","name":"Faroese"},{"id":"fr","name":"French"},{"id":"fy","name":"Western Frisian"},{"id":"ga","name":"Irish"},{"id":"gd","name":"Scottish Gaelic"},{"id":"gl","name":"Galician"},{"id":"gu","name":"Gujarati"},{"id":"gv","name":"Manx"},{"id":"ha","name":"Hausa"},{"id":"he","name":"Hebrew"},{"id":"hi","name":"Hindi"},{"id":"ho","name":"Hiri Motu"},{"id":"hr","name":"Croatian"},{"id":"ht","name":"Haitian"},{"id":"hu","name":"Hungarian"},{"id":"hy","name":"Armenian"},{"id":"hz","name":"Herero"},{"id":"ia","name":"Interlingua"},{"id":"id","name":"Indonesian"},{"id":"ie","name":"Interlingue"},{"id":"ig","name":"Igbo"},{"id":"ii","name":"Nuosu"},{"id":"ik","name":"Inupiaq"},{"id":"io","name":"Ido"},{"id":"is","name":"Icelandic"},{"id":"it","name":"Italian"},{"id":"iu","name":"Inuktitut"},{"id":"ja","name":"Japanese"},{"id":"jv","name":"Javanese"},{"id":"ka","name":"Georgian"},{"id":"kg","name":"Kongo"},{"id":"ki","name":"Kikuyu"},{"id":"kj","name":"Kwanyama"},{"id":"kk","name":"Kazakh"},{"id":"kl","name":"Kalaallisut"},{"id":"km","name":"Khmer"},{"id":"kn","name":"Kannada"},{"id":"ko","name":"Korean"},{"id":"kr","name":"Kanuri"},{"id":"ks","name":"Kashmiri"},{"id":"ku","name":"Kurdish"},{"id":"kv","name":"Komi"},{"id":"kw","name":"Cornish"},{"id":"ky","name":"Kyrgyz"},{"id":"la","name":"Latin"},{"id":"lb","name":"Luxembourgish"},{"id":"lg","name":"Ganda"},{"id":"li","name":"Limburgish"},{"id":"ln","name":"Lingala"},{"id":"lo","name":"Lao"},{"id":"lt","name":"Lithuanian"},{"id":"lu","name":"Luba-Katanga"},{"id":"lv","name":"Latvian"},{"id":"mg","name":"Malagasy"},{"id":"mh","name":"Marshallese"},{"id":"mk","name":"Macedonian"},{"id":"ml","name":"Malayalam"},{"id":"mn","name":"Mongolian"},{"id":"mr","name":"Marathi"},{"id":"ms","name":"Malay"},{"id":"mt","name":"Maltese"},{"id":"my","name":"Burmese"},{"id":"na","name":"Nauru"},{"id":"nd","name":"Northern Ndebele"},{"id":"ne","name":"Nepali"},{"id":"ng","name":"Ndonga"},{"id":"nl","name":"Dutch"},{"id":"nn","name":"Norwegian Nynorsk"},{"id":"no","name":"Norwegian"},{"id":"nr","name":"Southern Ndebele"},{"id":"nv","name":"Navajo"},{"id":"ny","name":"Chichewa"},{"id":"oc","name":"Occitan"},{"id":"oj","name":"Ojibwe"},{"id":"om","name":"Oromo"},{"id":"or","name":"Oriya"},{"id":"os","name":"Ossetian"},{"id":"pa","name":"Panjabi"},{"id":"pl","name":"Polish"},{"id":"ps","name":"Pashto"},{"id":"pt","name":"Portuguese"},{"id":"pt-pt","name":"Portuguese (Portugal)"},{"id":"qu","name":"Quechua"},{"id":"rm","name":"Romansh"},{"id":"rn","name":"Kirundi"},{"id":"ro","name":"Romanian"},{"id":"ru","name":"Russian"},{"id":"rw","name":"Kinyarwanda"},{"id":"sa","name":"Sanskrit"},{"id":"sc","name":"Sardinian"},{"id":"sd","name":"Sindhi"},{"id":"se","name":"Northern Sami"},{"id":"sg","name":"Sango"},{"id":"si","name":"Sinhala"},{"id":"sk","name":"Slovak"},{"id":"sl","name":"Slovenian"},{"id":"sm","name":"Samoan"},{"id":"sn","name":"Shona"},{"id":"so","name":"Somali"},{"id":"sq","name":"Albanian"},{"id":"sr","name":"Serbian"},{"id":"ss","name":"Swati"},{"id":"st","name":"Southern Sotho"},{"id":"su","name":"Sundanese"},{"id":"sv","name":"Swedish"},{"id":"sw","name":"Swahili"},{"id":"ta","name":"Tamil"},{"id":"te","name":"Telugu"},{"id":"tg","name":"Tajik"},{"id":"th","name":"Thai"},{"id":"ti","name":"Tigrinya"},{"id":"tk","name":"Turkmen"},{"id":"tl","name":"Tagalog"},{"id":"tn","name":"Tswana"},{"id":"to","name":"Tonga"},{"id":"tr","name":"Turkish"},{"id":"ts","name":"Tsonga"},{"id":"tt","name":"Tatar"},{"id":"tw","name":"Twi"},{"id":"ty","name":"Tahitian"},{"id":"ug","name":"Uyghur"},{"id":"uk","name":"Ukrainian"},{"id":"ur","name":"Urdu"},{"id":"uz","name":"Uzbek"},{"id":"ve","name":"Venda"},{"id":"vi","name":"Vietnamese"},{"id":"wa","name":"Walloon"},{"id":"wo","name":"Wolof"},{"id":"xh","name":"Xhosa"},{"id":"yi","name":"Yiddish"},{"id":"yo","name":"Yoruba"},{"id":"za","name":"Zhuang"},{"id":"zh","name":"Chinese"},{"id":"zu","name":"Zulu"}]', true);
                    @endphp
                    <x-select class="text-base" :options="$languages" wire:model="outputLanguage" />

                    <x-button label="Generate" type="submit" class="btn-primary text-white text-base" wire:click.prevent="generateArticles" spinner="save2" />
                </div>

            </x-form>
        </x-tab>
        <x-tab name="titles-mode-tab" label="Titles mode">
            <div>
                <x-form>
                    <x-instruction-step number="1" instruction="Write your titles (1 per line)" />
                    <x-textarea wire:model="businessDescription" placeholder="How to make bread at home?
Best recipes for making bread
What are the different types of bread?" rows="7" class="resize-none text-[16px] leading-6" />
                    <x-instruction-step number="2" instruction="Generate Articles" />
                    <div class="grid grid-cols-[1fr_100px] gap-5">
                        <x-select class="text-base" :options="$languages" wire:model="outputLanguage" />
                        <x-button label="Generate" type="submit" class="btn-primary text-white text-base" spinner="save2" />
                    </div>
                </x-form>
            </div>
        </x-tab>

        <x-tab name="keywords-mode-tab" label="Keywords mode">
            <div>
                <x-form>
                    <x-instruction-step number="1" instruction="Write your keywords (1 per line)" />
                    <x-textarea wire:model="businessDescription" placeholder="How to make bread at home?
Best recipes for making bread
What are the different types of bread?" rows="7" class="resize-none text-[16px] leading-6" />
                    <x-instruction-step number="2" instruction="Generate Articles" />
                    <div class="grid grid-cols-[1fr_100px] gap-5">
                        <x-select class="text-base" :options="$languages" wire:model="outputLanguage" />
                        <x-button label="Generate" type="submit" class="btn-primary text-white text-base" spinner="save2" />
                    </div>
                </x-form>
            </div>
        </x-tab>

        <x-tab name="advanced-mode-tab" label="Advanced mode">
            <div>
                <x-form>
                    <div class="flex justify-between items-end">
                        <x-instruction-step number="1" instruction="Choose your preset" />
                        <x-button label="+ Create Preset" class="btn-outline btn-xs btn-primary" tooltip="Mary" />
                    </div>
                    @php
                    $presets = [['id' => 0,'name' => 'Please select a preset.', 'disabled' => true]];
                    @endphp
                    <x-select class="text-base" :options="$presets" wire:model="preset" />
                    <x-instruction-step number="2" instruction="Generate Articles" />
                    <div class="grid grid-cols-[1fr_100px] gap-5">
                        @php
                        $article_counts = [];
                        foreach ([1,3,5,10,20,40,60,80,100,150,300] as $i)
                        {
                        $article_counts[] = ["id" => $i, "name" => $i . " article" . ($i === 1 ? "" : "s")];
                        }
                        @endphp
                        <x-select class="text-base" :options="$article_counts" wire:model="articleCount" />
                        <x-button label="Generate" type="submit" class="btn-primary text-white text-base" spinner="save2" />
                    </div>
                </x-form>
            </div>
        </x-tab>
    </x-tabs>
</div>
