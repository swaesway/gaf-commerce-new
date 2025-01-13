<template>
    <div class="d-flex flex-column h-100">
      <!-- Header -->
      <div class="p-3 border-bottom bg-white">
        <h5 class="mb-0">Chats</h5>
      </div>
  
      <!-- Chat List -->
      <div class="flex-grow-1 overflow-auto">
        <ul class="list-group list-group-flush">
          <li
            class="list-group-item d-flex align-items-center"
            v-for="contact in contacts"
            :key="contact.id"
            @click="handleClick(contact)"
            style="cursor: pointer;"
          >
            <img
              :src="contact.avatar"
              class="rounded-circle me-3"
              alt="Avatar"
              width="40"
              height="40"
            />
            <div>
              <h6 class="mb-0">{{ contact.name }}</h6>
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
        this.$emit('selectContact', contact); // Emit the selected contact to the parent
      },
    },
  };
  </script>
  