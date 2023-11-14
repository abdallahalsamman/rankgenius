<div>
    <x-header size="text-xl font-[700] mb-5" title="Articles" subtitle="Browse through all articles that you've generated so far." />

    <div class="grid grid-cols-[_1fr_4fr_1fr] gap-3">
        <div class="text-gray-600 tracking-wider py-2 font-bold text-xs">DATE</div>
        <div class="text-gray-600 tracking-wider py-2 font-bold text-xs text-center">TITLE</div>
        <div class="text-gray-600 tracking-wider py-2 font-bold text-xs text-right">ACTIONS</div>

        @foreach($articles as $article)
        <div>{{ Str::limit($article->name, 80, ' ...') }}</div>
        @endforeach
    </div>

    @if ($articles->isEmpty())
        <div class="text-center py-2 rounded-b-md text-[#a0aec0] bg-[#edf2f7]">Click below to create your first article.
        </div>
    @endif

    <x-button label="New article" link="{{ route('preset-view') }}" icon="o-plus"
        class="btn-primary text-base-100 mt-8 w-full" />
</div>
