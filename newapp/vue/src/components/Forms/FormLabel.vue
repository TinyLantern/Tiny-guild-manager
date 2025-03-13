<template>
    <div class="label-section">
      <span>{{ label }}</span>
    </div>
  </template>
  
  <script setup>
  defineProps({
    label: {
      type: String,
      required: true,
    },
  });
  </script>
  
  <style scoped>
  .label-section {
    flex: 1;
    display: flex;
    align-items: center;
    font-size: 1rem;
    color: #a1a1a1;
  }
  </style>