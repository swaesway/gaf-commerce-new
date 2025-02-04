<!-- <template>
  <div>
    <editor-content :editor="editor" class="editor" />
    <div class="toolbar">
      <button @click="editor.chain().focus().toggleBold().run()">Bold</button>
      <button @click="editor.chain().focus().toggleItalic().run()">Italic</button>
      <button @click="editor.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()">Insert Table</button>
      <button @click="editor.chain().focus().deleteTable().run()">Delete Table</button>
    </div>
  </div>
</template>

<script>
import { defineComponent, onBeforeUnmount, ref } from "vue";
import { Editor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import Table from "@tiptap/extension-table";
import TableRow from "@tiptap/extension-table-row";
import TableCell from "@tiptap/extension-table-cell";
import TextStyle from "@tiptap/extension-text-style";
import Paragraph from "@tiptap/extension-paragraph";
import Bold from "@tiptap/extension-bold";
import Italic from "@tiptap/extension-italic";

export default defineComponent({
  components: { EditorContent },
  setup() {
    const editor = ref(
      new Editor({
        extensions: [
          StarterKit,
          Bold,
          Italic,
          TextStyle,
          Paragraph,
          Table.configure({ resizable: true }),
          TableRow,
          TableCell
        ],
        content: "<p>Hello, TipTap with Table Support!</p>",
      })
    );

    onBeforeUnmount(() => {
      editor.value.destroy();
    });

    return { editor };
  },
});
</script>

<style>
.editor {
  border: 1px solid #ddd;
  padding: 10px;
  min-height: 200px;
}
.toolbar {
  margin-top: 10px;
}
button {
  margin-right: 5px;
  padding: 5px;
  cursor: pointer;
}
</style> -->
