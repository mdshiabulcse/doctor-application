<template>
 <div class="container mx-auto ">
   <section v-if="editor" class="buttons flex items-center flex-wrap gap-x-4 border border-t border-l border-r border-gray-400 p-4">Buttons Go Here
     <button @click="editor.chain().focus().toggleBold().run()" :disabled="!editor.can().chain().focus().toggleBold().run()" :class="{ 'is-active': editor.isActive('bold') }">
       bold
     </button>
   </section>
   <EditorContent :editor="editor" />
 </div>
</template>

<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'

const editor = useEditor({
  editorProps: {
    attributes: {
      class: 'border border-gray-400 p-4 min-h-[12rem] max-h-[12rem] overflow-y-auto',
    },
  },
  content: '<p>I’m running Tiptap with Vue.js. 🎉</p>',
  extensions: [
    StarterKit,
  ],
})
</script>
