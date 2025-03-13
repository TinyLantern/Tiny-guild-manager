<template>
    <div class="header">
      <h2>{{ title }}</h2>
    </div>
  </template>
  
  <script setup>
  defineProps({
    title: {
      type: String,
      required: true,
    },
  });
  </script>
  
  <style scoped>
  .header {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  h2 {
    font-size: 1.5rem;
    color: #fff;
    font-weight: bold;
  }
  </style>