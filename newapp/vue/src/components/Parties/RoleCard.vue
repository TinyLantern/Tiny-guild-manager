<template>
  <div class="category-card">
    <div class="card-header">
      <q-icon :name="icon" :color="color" size="20px" />
      <h3>{{ title }}</h3>
      <span class="count">{{ members.length }}/{{ maxMembers }}</span>
    </div>
    <div
      class="card-content"
      :class="{ 'empty-state': !members.length }"
    >
      <div
        v-for="(member, index) in members"
        :key="index"
        class="draggable-item"
        draggable="true"
        @dragstart="(event) => handleDragStart(event, member)"
      >
        <q-avatar :icon="member.icon" size="26px" :text-color="color"/>
        <span>{{ member.name }}</span>
      </div>
      <div v-if="!members.length" class="empty">No {{ title }} found</div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: String,
  icon: String,
  color: String,
  members: Array,
  maxMembers: Number,
  category: String,
});

const emit = defineEmits(['drag-start']);

const handleDragStart = (event, member) => {
  emit('drag-start', event, member, props.category);
};
</script>

<style scoped>
.category-card {
  color: #fff;
  border-radius: 8px;
  flex: 1;
  max-width: 500px;
}

.card-header {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  height: 40px;
  background-color: var(--custom-purple);
  padding: 0 1rem;
  border-radius: 8px 8px 0 0;
}

.card-header h3 {
  font-size: 1rem;
  margin: 0;
  font-weight: bold;
}

.card-header .count {
  font-size: 0.875rem;
  color: #fff;
  font-weight: bold;
}

.card-content {
  background-color: var(--custom-dark);
  border-radius: 0 0 8px 8px;
  padding: 1rem;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 0.5rem;
}

.card-content.empty-state {
  display: flex;
  align-items: center;
  justify-content: center;
}

.empty {
  text-align: center;
  color: #fff;
}

.draggable-item {
  display: flex;
  align-items: center;
  background-color: var(--custom-gray);
  border-radius: 4px;
  cursor: grab;
  max-width: 145px;
  height: 24px;
}

.draggable-item:hover {
  background-color: var(--custom-purple);
}

.draggable-item span {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: calc(100% - 2rem);
}
</style>