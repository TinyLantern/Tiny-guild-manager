<template>
  <q-card-actions align="right" class="back-button">
    <q-btn label="Back" @click="emit('go-back')" />
  </q-card-actions>
</template>

<script setup>
const emit = defineEmits(['go-back']);
</script>

<style lang="scss">
.back-button{
  padding: 0;
  border-radius: 4px;
  background-color: var(--custom-purple);
}
</style>