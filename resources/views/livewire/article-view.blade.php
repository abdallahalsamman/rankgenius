<div class="py-2 px-6 overflow-y-scroll">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div
    class="editorjs max-w-full"
    id="editorjs"
    data-editorjs-data="{{ $selectedArticle->content }}"
    data-article-id="{{ $selectedArticle->id }}"
    data-assistant-url="{{ route('article.assistant', $selectedArticle->id) }}"
    data-post-url="{{ route('article.save', $selectedArticle->id) }}"></div>

    <x-drawer wire:model="showDrawer" class="w-11/12 lg:w-1/3" right>
        <div id="ai-drawer">
            <div class="flex min-h-screen justify-center items-center"><span class="loading loading-spinner loading-lg"></span></div>
        </div>
    </x-drawer>
</div>

@script
<script>
Livewire.on('showDrawer', () => $wire.showDrawer = true)
Livewire.on('hideDrawer', () => $wire.showDrawer = false)
</script>
@endscript