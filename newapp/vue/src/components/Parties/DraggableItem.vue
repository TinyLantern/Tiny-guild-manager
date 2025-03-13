<template>
  <div
    class="draggable-item"
    draggable="true"
    @dragstart="handleDragStart"
  >
    <q-avatar icon="person" />
    <span>{{ member.name }}</span>
  </div>
</template>

<script setup>
const props = defineProps({
  member: Object,
  category: String,
});

const emit = defineEmits(['drag-start']);

const handleDragStart = (event) => {
  emit('drag-start', event, props.member, props.category);
};
</script>

<style scoped>

</style>