<div class="py-2 px-6 overflow-y-scroll">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div
    class="editorjs max-w-full"
    id="editorjs"
    data-editorjs-data="{{ $selectedArticle->content }}"
    data-article-id="{{ $selectedArticle->id }}"
    data-post-url="{{ route('article.save', $selectedArticle->id) }}"></div>
</div>

@section('other-scripts')
<script>
    console.log('test')
</script>
@endsection
{{-- <div class="">
    @if($selectedArticle->image_url)
    <div class="flex justify-center items-center">
        <img src="{{ $selectedArticle->image_url }}"
        class="rounded-md h-[14rem]" />
    </div>
    @endif

    <div class="markdown">
        {!! Str::markdown($selectedArticle->content) !!}
    </div>
</div> --}}
