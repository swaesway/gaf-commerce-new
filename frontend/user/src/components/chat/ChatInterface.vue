<template>
  <div class="d-flex flex-column h-100 p-3">
    <!-- Header -->
    <div
      v-if="contact"
      class="p-3 border-bottom bg-success text-white d-flex align-items-center"
    >
      <img
        :src="contact.avatar"
        class="rounded-circle me-3"
        alt="Avatar"
        width="50"
        height="50"
      />
      <h6 class="mb-0 fw-bold">{{ contact.name }}</h6>
    </div>

    <!-- Messages -->
    <div
      v-if="contact"
      class="flex-grow-1 overflow-auto p-3 bg-white border"
      id="messageContainer"
      style="border-radius: 5px"
    >
      <div v-for="(message, index) in contact.messages" :key="index">
        <div
          :class="[
            message.isSender ? 'justify-content-end' : 'justify-content-start',
          ]"
          class="d-flex mb-3"
        >
          <div
            :class="[
              'p-3 rounded-3',
              message.isSender
                ? 'bg-white text-black border'
                : 'bg-light text-dark',
            ]"
            style="max-width: 70%; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1)"
          >
            {{ message.text }}
          </div>
        </div>
      </div>
    </div>

    <!-- Input Area -->
    <div
      v-if="contact"
      class="p-3 bg-light"
      style="position: sticky; bottom: 0"
    >
      <div class="input-group">
        <input
          type="text"
          class="form-control rounded-start"
          placeholder="Type a message"
          v-model="newMessage"
          @keyup.enter="sendMessage"
        />
        <button class="btn btn-primary rounded-end" @click="sendMessage">
          Send
        </button>
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
      newMessage: "",
    };
  },
  methods: {
    sendMessage() {
      if (this.newMessage.trim() && this.contact) {
        this.contact.messages.push({ text: this.newMessage, isSender: true });
        this.newMessage = "";
        this.scrollToBottom();
      }
    },
    scrollToBottom() {
      const container = document.getElementById("messageContainer");
      if (container) {
        container.scrollTop = container.scrollHeight;
      }
    },
  },
};
</script>
