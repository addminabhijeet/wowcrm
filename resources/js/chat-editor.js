import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'

const editorElement = document.querySelector('#editor')

if (editorElement) {

    const editor = new Editor({
        element: editorElement,
        extensions: [
            StarterKit,
        ],
        content: '',
        onUpdate({ editor }) {
            document.getElementById('chatMessage').value =
                editor.getHTML();
        }
    })

    document.getElementById('boldBtn')?.addEventListener('click', () => {
        editor.chain().focus().toggleBold().run()
    })

    document.getElementById('italicBtn')?.addEventListener('click', () => {
        editor.chain().focus().toggleItalic().run()
    })

    document.getElementById('strikeBtn')?.addEventListener('click', () => {
        editor.chain().focus().toggleStrike().run()
    })

    document.getElementById('bulletBtn')?.addEventListener('click', () => {
        editor.chain().focus().toggleBulletList().run()
    })

    // Attach editor content before submitting
    document.querySelector('.chat-message-box')
        ?.addEventListener('submit', function (e) {

            const html = editor.getHTML();

            // remove empty paragraph
            if (html === '<p></p>') {
                document.getElementById('chatMessage').value = '';
            } else {
                document.getElementById('chatMessage').value = html;
            }
        });

    // Attach text when selecting files/images also
    document.getElementById('fileInput')
        ?.addEventListener('change', function () {

            document.getElementById('chatMessage').value =
                editor.getHTML();

        });

    document.getElementById('imageInput')
        ?.addEventListener('change', function () {

            document.getElementById('chatMessage').value =
                editor.getHTML();

        });

}