<template>
    <div class="card-body chat-window" style="height: 300px; overflow-y: auto;">
      <div v-for="(message, index) in messages" :key="index" :class="['mb-3', message.sender === 'User' ? 'text-end' : 'text-start']">
        <div class="d-inline-block p-2 rounded" :class="message.sender === 'User' ? 'bg-primary text-white' : 'bg-light text-dark'">
          {{ message.text }}
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: ['messages'],
  };
  </script>
  
  <style>
  .chat-window {
    background: #ffffff;
  }
  </style>
  