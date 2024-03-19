import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import Table from '@editorjs/table'
import _ from 'lodash';
import Undo from 'editorjs-undo';
import Assistant from './Assistant';

var last_article_id = null;
let debouncedCheckEditorjs = _.debounce(checkEditorjs, 200);
Livewire.hook('morph.added', debouncedCheckEditorjs);
Livewire.hook('morph.updated', debouncedCheckEditorjs);
Livewire.hook('morph.removed', debouncedCheckEditorjs);
setTimeout(() => checkEditorjs(), 500); // for initial page load

function checkEditorjs() {
    const editorjs_div = document.getElementById('editorjs');
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
    const initialData = JSON.parse(editorjs_div.dataset.editorjsData);
    const editor = new EditorJS({
        holder: 'editorjs',
        data: initialData,
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
            assistant: {
                class: Assistant,
                config: {
                    assistantUrl: editorjs_div.dataset.assistantUrl,
                    csrfToken: document.querySelector('input[name="_token"]').value
                },
                shortcut: 'CTRL+M'
            }
        },
        onReady: () => {
            const undo = new Undo({
                editor,
                config: {
                    shortcuts: {
                        undo: 'CTRL+Z',
                        redo: 'CTRL+SHIFT+Z'
                    }
                }
            });
            undo.initialize(initialData);
        },
        onChange: _.debounce(() => mySaveFunction(editor, editorjs_div), 200)
    });
}

function mySaveFunction(editor, editorjs_div) {
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
            body: JSON.stringify({ data: outputData })
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