<template>
    <q-input
      v-model="localModel"
      :readonly="readonly"
      dense
      filled
      class="input-field"
    />
  </template>
  
  <script setup>
  import { computed } from 'vue';
  
  const props = defineProps({
    modelValue: {
      type: [String, Number],
      required: true,
    },
    readonly: {
      type: Boolean,
      default: false,
    },
  });
  
  const emit = defineEmits(['update:modelValue']);
  
  const localModel = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
  });
  </script>
  
  <style scoped>
  .input-field {
    background-color: #f5f5f5;
    border: 1px solid #ccc;
    padding: 8px;
    border-radius: 4px;
    color: #000;
  }
  </style>