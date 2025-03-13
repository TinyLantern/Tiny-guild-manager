<template>
  <div class="row justify-center items-center calendar-buttons-container">
    <q-btn
      flat
      dense
      label="Prev"
      @click="emit('prev')"
      class="navigation-button"
    />
    <q-separator vertical />

    <q-btn
      flat
      dense
      label="Next"
      @click="emit('next')"
      class="navigation-button"
    />
    <q-separator vertical />

    <q-btn
      flat
      dense
      label="New Activity"
      @click="emit('new-activity')"
      class="new-activity-button"
    />
  </div>
</template>

<script setup>
const emit = defineEmits(['prev', 'next', 'new-activity']);
</script>

<style scoped lang="scss">
.calendar-buttons-container {
  gap: 8px;
  padding-bottom: 16px;
}

.navigation-button {
  background-color: var(--custom-gray);
  border-radius: 4px;
  padding: 6px 12px;
}

.new-activity-button {
  background-color: var(--custom-purple);
  border-radius: 4px;
  padding: 6px 12px;
  color: white;
}
</style>