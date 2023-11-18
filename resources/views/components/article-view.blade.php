<div class="py-2 px-6 overflow-y-scroll">
    @if($selectedArticle->image_url)
    <div class="flex justify-center items-center">
        <img src="{{ $selectedArticle->image_url }}"
        class="rounded-md h-[14rem]" />
    </div>
    @endif

    <div class="markdown">
        {!! Str::markdown($selectedArticle->content) !!}
    </div>
</div>
