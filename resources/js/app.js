import './bootstrap';
import {Alpine, Livewire} from '../../vendor/livewire/livewire/dist/livewire.esm';
import ToastComponent from '../../vendor/usernotnull/tall-toasts/resources/js/tall-toasts'
import EditorJS from '@editorjs/editorjs'

Alpine.plugin(ToastComponent)

Livewire.start()

window.EditorJS = EditorJS