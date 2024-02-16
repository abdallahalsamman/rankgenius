import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import Table from '@editorjs/table'
import _ from 'lodash';

var last_article_id = null;
let debouncedCheckEditorjs = _.debounce(checkEditorjs, 200);
Livewire.hook('morph.added', debouncedCheckEditorjs);
Livewire.hook('morph.updated', debouncedCheckEditorjs);
Livewire.hook('morph.removed', debouncedCheckEditorjs);
setTimeout(() => checkEditorjs(), 500); // for initial page load

function checkEditorjs() {
    const editorjs_div =  document.getElementById('editorjs');
    if (!editorjs_div) {
        return;
    }

    if (editorjs_div.dataset.articleId === last_article_id && editorjs_div.children.length > 0) {
        console.log('Article ID same as last article ID');
        return;
    }

    last_article_id = editorjs_div.dataset.articleId;
    initEditor(editorjs_div);
}

function initEditor(editorjs_div) {
    const editor = new EditorJS({
        holder: 'editorjs',
        data: JSON.parse(editorjs_div.dataset.editorjsData),
        tools: {
            header: {
                class: Header,
                inlineToolbar: true
            },
            list: {
                class: List,
                inlineToolbar: true
            },
            table: Table,
        },
        onChange: _.debounce(mySaveFunction, 200)
    });

    function mySaveFunction() {
        editor.save().then((outputData) => {
            fetch(editorjs_div.dataset.postUrl, {
                method: 'POST',
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-Token": document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({data: outputData})
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        });
    }
}