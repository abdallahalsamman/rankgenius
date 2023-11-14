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
            <div class="">{{ Str::limit($article->title, 80, ' ...') }}</div>
            <div>
                <x-button label="View" wire:loading.attr="disabled" class="btn-sm" wire:click="viewArticle('{{ $article->id }}')" />
            </div>
        @endforeach
    </div>
    @if ($selectedArticle)
        <x-modal class="popUpMessage" wire:model="myModal">
            {{ $selectedArticle->id }}
            {{ $selectedArticle->title }}
            {{ $selectedArticle->content }}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.myModal = false" />
                <x-button label="Confirm" class="btn-primary" />
            </x-slot:actions>
        </x-modal>
    @endif

    @if ($articles->isEmpty())
        <div class="text-center py-2 rounded-b-md text-[#a0aec0] bg-[#edf2f7]">Click below to create your first article.
        </div>
    @endif
</div>
