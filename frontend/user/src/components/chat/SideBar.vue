<template>
  <div class="d-flex flex-column h-100 bg-white p-3">
    <!-- Header -->
    <div class="p-3 bg-success text-white text-center">
      <h5 class="mb-0 fw-bold">Chats</h5>
    </div>

    <!-- Chat List -->
    <div class="flex-grow-1 overflow-auto">
      <ul class="list-group list-group-flush">
        <li
          class="list-group-item d-flex align-items-center border-bottom"
          v-for="contact in contacts"
          :key="contact.id"
          @click="handleClick(contact)"
          style="cursor: pointer; padding: 12px 16px"
        >
          <img
            :src="contact.avatar"
            class="rounded-circle me-3"
            alt="Avatar"
            width="40"
            height="40"
          />
          <div>
            <h6 class="mb-0 fw-bold">{{ contact.name }}</h6>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    contacts: Array,
  },
  methods: {
    handleClick(contact) {
      this.$emit("selectContact", contact);
    },
  },
};
</script>
