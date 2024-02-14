import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import _ from 'lodash';

const editorjs_div =  document.getElementById('editorjs');
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
        }
    },
    onChange: _.debounce(mySaveFunction, 200)
});

function mySaveFunction() {
    editor.save().then((outputData) => {
        fetch(window.location.href, {
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