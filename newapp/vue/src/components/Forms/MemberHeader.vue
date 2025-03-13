<template>
  <div class="header">
    <div class="header-actions">
      <slot></slot>
    </div>
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

<style lang="scss">
.header {
  display: flex;
  padding-bottom: 1rem;
  justify-content: center;
}

.header-actions {
  display: flex;
  gap: 8px;
}
</style>