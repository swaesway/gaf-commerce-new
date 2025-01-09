<template>
  <div class="d-flex flex-column h-100">
    <!-- Header -->
    <div
      v-if="contact"
      class="p-3 border-bottom bg-white d-flex align-items-center"
    >
      <img
        :src="contact.avatar"
        class="rounded-circle me-3"
        alt="Avatar"
        width="40"
        height="40"
      />
      <h6 class="mb-0">{{ contact.name }}</h6>
    </div>

    <!-- Messages -->
    <div v-if="contact" class="flex-grow-1 overflow-auto p-3" id="messageContainer">
      <div v-for="(message, index) in contact.messages" :key="index">
        <div
          :class="[
            'd-flex my-2',
            message.isSender ? 'justify-content-end' : 'justify-content-start',
          ]"
        >
          <div
            :class="[
              'p-2 rounded',
              message.isSender ? 'bg-primary text-white' : 'bg-light text-dark',
            ]"
            style="max-width: 70%;"
          >
            {{ message.text }}
          </div>
        </div>
      </div>
    </div>

    <!-- Input Area -->
    <div v-if="contact" class="p-3 border-top bg-white">
      <div class="input-group">
        <input
          type="text"
          class="form-control"
          placeholder="Type a message"
          v-model="newMessage"
          @keyup.enter="sendMessage"
        />
        <button class="btn btn-primary" @click="sendMessage">Send</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    contact: Object,
  },
  data() {
    return {
      newMessage: '',
    };
  },
  methods: {
    sendMessage() {
      if (this.newMessage.trim() && this.contact) {
        this.contact.messages.push({ text: this.newMessage, isSender: true });
        this.newMessage = '';
        this.scrollToBottom();
      }
    },
    scrollToBottom() {
      const container = document.getElementById('messageContainer');
      if (container) {
        container.scrollTop = container.scrollHeight;
      }
    },
  },
};
</script>
