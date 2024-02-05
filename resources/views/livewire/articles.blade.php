<div>
    <x-header size="text-xl font-[700]"
        subtitle="Browse through all articles that you've generated so far."
        title="Articles" />

    <div class="grid grid-cols-[_1fr_6fr_1fr] gap-3 border-b-[1px] px-3">
        <div class="py-2 text-xs font-bold tracking-wider text-gray-600">DATE
        </div>
        <div
            class="py-2 text-center text-xs font-bold tracking-wider text-gray-600">
            TITLE</div>
        <div
            class="py-2 text-right text-xs font-bold tracking-wider text-gray-600">
            ACTIONS</div>
    </div>
    @foreach ($articles as $article)
        <div class="grid grid-cols-[_1fr_6fr_1fr] gap-3 px-3 py-2 items-center hover:bg-base-200/50">
            <div>{{ date('d/m', strtotime($article->created_at)) }}</div>
            <div>{{ Str::limit($article->title, 80, ' ...') }}</div>
            <div class="flex justify-end gap-3">
                <x-button class="btn-sm" label="View"
                    wire:click="viewArticle('{{ $article->id }}')"
                    wire:loading.attr="disabled" />
                <x-button class="btn-sm" label="Edit"
                    link="{{ route('article.edit', ['id' => $article->id]) }}"
                    wire:loading.attr="disabled" />      
            </div>
        </div>
    @endforeach

    <div>
        <x-modal class="popUpMessage" wire:model="articleModal">
            <div class="relative flex h-full flex-col">
                <div class="bg-white">
                    <div class="flex items-center justify-end gap-5 px-6 py-4">
                        <x-button @click="$wire.articleModal = false"
                            class="btn-sm border-0 bg-transparent px-[6px]"
                            icon="bi.x-lg" />
                    </div>
                </div>
                @if ($selectedArticle)
                    <x-article-view :$selectedArticle />
                @endif
            </div>
        </x-modal>
    </div>

    @if ($articles->isEmpty())
        <div class="rounded-b-md bg-[#edf2f7] py-2 text-center text-[#a0aec0]">
            Click below to create your first article.
        </div>
    @endif
</div>
