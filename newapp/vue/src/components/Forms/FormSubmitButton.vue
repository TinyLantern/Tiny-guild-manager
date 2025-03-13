<template>
    <q-btn
      type="submit"
      :label="label"
      :color="color"
      class="full-width"
    />
  </template>
  
  <script setup>
  defineProps({
    label: {
      type: String,
      default: 'Submit',
    },
    color: {
      type: String,
      default: 'primary',
    },
  });
  </script>
  
  <style scoped>
  .full-width {
    width: 100%;
    margin-top: 1rem;
  }
  </style>