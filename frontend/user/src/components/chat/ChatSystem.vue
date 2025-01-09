<template>
  <div class="container-fluid vh-100">
    <div class="row h-100">
      <!-- Sidebar -->
      <div class="col-3 bg-light border-end p-0">
        <Sidebar :contacts="contacts" @selectContact="setActiveContact" />
      </div>

      <!-- Chat Interface -->
      <div class="col-9 p-0">
        <ChatInterface :contact="activeContact" />
      </div>
    </div>
  </div>
</template>

<script>
import Sidebar from './SideBar.vue';
import ChatInterface from './ChatInterface.vue';

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
          name: 'John Doe',
          avatar: 'https://i.pravatar.cc/300',
          messages: [
            { text: 'Hello!', isSender: false },
            { text: 'Hi, how are you?', isSender: true },
          ],
        },
        {
          id: 2,
          name: 'Jane Smith',
          avatar: 'https://i.pravatar.cc/300',
          messages: [
            { text: 'See you tomorrow!', isSender: false },
            { text: 'Sure, looking forward to it!', isSender: true },
          ],
        },
        // Add more contacts here
      ],
      activeContact: null, // No contact selected initially
    };
  },
  methods: {
    setActiveContact(contact) {
      this.activeContact = contact; // Update the active contact
    },
  },
};
</script>

<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
}
</style>
