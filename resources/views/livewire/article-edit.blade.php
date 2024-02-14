<div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div
    class="editorjs max-w-full"
    id="editorjs"
    data-editorjs-data="{{ $article->content }}"
    data-article-id="{{ $article->id }}"
    data-post-url="{{ route('article.save', $article->id) }}"></div>
</div>

@vite(['resources/js/editor.js'])