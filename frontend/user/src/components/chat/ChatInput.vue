<template>
  <div class="card-footer">
    <form @submit.prevent="handleSubmit">
      <div class="input-group">
        <input
          type="text"
          class="form-control"
          placeholder="Type your message..."
          v-model="message"
        />
        <button type="submit" class="btn btn-primary">Send</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      message: "",
    };
  },
  methods: {
    handleSubmit() {
      if (this.message.trim() !== "") {
        this.$emit("send-message", { text: this.message, sender: "User" });
        this.message = "";
      }
    },
  },
};
</script>
