<template>
  <div class="card">
    <ChatWindow :messages="messages" />
    <ChatInput @send-message="addMessage" />
  </div>
</template>

<script>
import ChatWindow from './ChatWindow.vue';
import ChatInput from './ChatInput.vue';

export default {
  components: {
    ChatWindow,
    ChatInput,
  },
  data() {
    return {
      messages: [
        { text: 'Hello!', sender: 'User' },
        { text: 'Hi, how can I help you?', sender: 'Vendor' },
      ],
    };
  },
  methods: {
    addMessage(newMessage) {
      this.messages.push(newMessage);
    },
  },
};
</script>

<style scoped>
.card {
  margin: 20px 0;
}
</style>
