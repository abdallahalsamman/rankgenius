<div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div class="editorjs max-w-[1024px]" id="editorjs" data-editorjs-data="{{ $data }}"></div>
</div>

@vite(['resources/js/editor.js'])