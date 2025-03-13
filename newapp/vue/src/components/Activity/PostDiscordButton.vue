<template>
  <q-card-section class="discord-button">
    <q-btn
      label="Post in Discord"
      @click="emit('post-in-discord')"
      class="full-width"
    />
  </q-card-section>
</template>
  
<script setup>
const emit = defineEmits(['post-in-discord']);
</script>
  
<style lang="scss">
.discord-button{
  padding: 0;
  background-color: var(--custom-purple);
  border-radius: 4px;
}
</style>