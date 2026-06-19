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

    document.querySelector('.chat-message-box')
        ?.addEventListener('submit', function () {

            document.getElementById('chatMessage').value =
                editor.getHTML()

        })
}
