<div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div class="editorjs max-w-full" id="editorjs" data-editorjs-data="{{ $article->content }}" data-article-id="{{ $article->id }}" data-save-article-url="{{ route('article.save', $article->id) }}" data-upload-image-url="{{ route('article.upload-image', $article->id) }}" data-upload-image-by-url-url="{{ route('article.upload-image-by-url', $article->id) }}"></div>
</div>

@vite(['resources/js/editor.js'])