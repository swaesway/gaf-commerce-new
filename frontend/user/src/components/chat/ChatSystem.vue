<template>
  <div class="container-fluid vh-100 p-3">
    <div class="row h-100 gx-3">
      <!-- Sidebar -->
      <div class="col-md-4 col-lg-3 bg-light border-end p-0">
        <Sidebar :contacts="contacts" @selectContact="setActiveContact" />
      </div>

      <!-- Chat Interface -->
      <div class="col-md-8 col-lg-9 p-0">
        <ChatInterface :contact="activeContact" />
      </div>
    </div>
  </div>
</template>

<script>
import Sidebar from "./SideBar.vue";
import ChatInterface from "./ChatInterface.vue";

export default {
  components: {
    Sidebar,
    ChatInterface,
  },
  data() {
    return {
      contacts: [
        {
          id: 1,
          name: "John Doe",
          avatar: "https://i.pravatar.cc/300",
          messages: [
            { text: "Hello!", isSender: false },
            { text: "Hi, how are you?", isSender: true },
          ],
        },
        {
          id: 2,
          name: "Jane Smith",
          avatar: "https://i.pravatar.cc/300",
          messages: [
            { text: "See you tomorrow!", isSender: false },
            { text: "Sure, looking forward to it!", isSender: true },
          ],
        },
      ],
      activeContact: null,
    };
  },
  methods: {
    setActiveContact(contact) {
      this.activeContact = contact;
    },
  },
};
</script>
