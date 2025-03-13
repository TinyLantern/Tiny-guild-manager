<template>
    <div id="notify-container">
      <transition name="fade">
        <q-banner v-if="show" class="custom-notify" :class="type">
          {{ message }}
        </q-banner>
      </transition>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const show = ref(false);
  const message = ref('');
  const type = ref('');
  
  const showCustomNotify = (msg, notifyType) => {
    message.value = msg;
    type.value = notifyType;
    show.value = true;
  
    setTimeout(() => {
      show.value = false;
    }, 2000);
  };
  
  defineExpose({ showCustomNotify });
  </script>
  
  <style lang="scss">
  #notify-container {
    position: absolute;
    top: 15px;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    display: flex;
    justify-content: center;
    z-index: 1000;
  }
  

  .custom-notify {
    font-weight: bold;
    text-align: center;
    max-width: 400px;
    padding: 12px;
    border-radius: 6px;
    transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
  }
  

  .custom-notify.success {
    background-color: #4caf50;
    color: white;
  }
  

  .custom-notify.error {
    background-color: #f44336;
    color: white;
  }
  

  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
  }
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
  }
  </style>