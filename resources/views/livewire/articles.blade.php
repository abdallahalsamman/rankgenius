<div>
    <x-header size="text-xl font-[700] mb-5" title="Articles"
        subtitle="Browse through all articles that you've generated so far." />

    <div class="grid grid-cols-[_1fr_6fr_1fr] gap-3 border-b-[1px]">
        <div class="text-gray-600 tracking-wider py-2 font-bold text-xs">DATE</div>
        <div class="text-gray-600 tracking-wider py-2 font-bold text-xs text-center">TITLE</div>
        <div class="text-gray-600 tracking-wider py-2 font-bold text-xs text-right">ACTIONS</div>
    </div>
    <div class="grid grid-cols-[_1fr_6fr_1fr] gap-3 mt-2">
        @foreach ($articles as $article)
            <div>{{ date('d/m', strtotime($article->created_at)) }}</div>
            <div>{{ Str::limit($article->title, 80, ' ...') }}</div>
            <div class="flex justify-end">
                <x-button label="View" wire:loading.attr="disabled" class="btn-sm"
                    wire:click="viewArticle('{{ $article->id }}')" />
            </div>
        @endforeach
    </div>

    <div>
        <x-modal class="popUpMessage" wire:model="articleModal">
            <div class="flex flex-col relative h-full">
                <div class="bg-white">
                    <div class="flex items-center gap-5 py-4 px-6">
                        {{-- <x-button icon="o-arrow-small-left" class="btn-sm btn-outline btn-primary" label="Previous" />
                        <div class="w-full">
                            <x-select class="btn-sm" />
                        </div>
                        <x-button icon-right="o-arrow-small-right" label="Next"
                            class="btn-sm btn-outline btn-primary" /> --}}
                        <x-button class="btn-sm px-[6px] bg-transparent border-0" icon="bi.x-lg"
                            @click="$wire.articleModal = false" />
                    </div>
                </div>
                <x-article-view :$selectedArticle />
            </div>
        </x-modal>
    </div>

    @if ($articles->isEmpty())
        <div class="text-center py-2 rounded-b-md text-[#a0aec0] bg-[#edf2f7]">Click below to create your first article.
        </div>
    @endif
</div>
