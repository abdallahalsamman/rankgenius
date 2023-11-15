<div class="py-2 px-6 overflow-y-scroll">
    <div class="flex justify-center items-center">
        <img src="https://contenu.nyc3.digitaloceanspaces.com/journalist/98b246fa-2c8f-4b14-b61c-d5f86038a8a8/thumbnail.jpeg"
        class="rounded-md h-[14rem]" />
    </div>
    <div class="markdown">
        {!! Str::markdown($selectedArticle?->content) !!}
    </div>
</div>
