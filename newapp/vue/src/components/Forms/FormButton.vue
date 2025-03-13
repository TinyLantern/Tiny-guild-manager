<template>
  <q-btn
    :label="label"
    :color="color"
    @click="onClick"
    class="form-button"
  />
</template>

<script setup>
defineProps({
  label: {
    type: String,
    required: true,
  },
  color: {
    type: String,
    default: 'primary',
  },
  onClick: {
    type: Function,
    required: true,
  },
  condition:{
    type: Boolean,
    default: true,
  }
});
</script>

<style lang="scss">
.form-button {
  border-radius: 4px;
  font-weight: bold;
  text-transform: uppercase;
  transition: background-color 0.3s ease;
}

.form-button:hover {
  opacity: 0.85;
}
</style>