<template>
  <div class="team-management">
    <div class="team-grid">
      <TeamCard
        v-for="team in teams"
        :key="team.id"
        :team="team"
        @remove-team="removeTeam"
        @drop="handleDrop"
        @remove-from-team="removeFromTeam"
      />

      <div class="add-team-button" @click="addTeam">
        <q-icon name="add" size="20px" />
        <span>Add Team</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import TeamCard from './TeamCard.vue';

const props = defineProps({
  teams: Array,
});

const emit = defineEmits(['add-team', 'remove-team', 'drop', 'remove-from-team']);

const addTeam = () => {
  emit('add-team');
};

const removeTeam = (teamId) => {
  emit('remove-team', teamId);
};

const handleDrop = (event, teamId) => {
  const clonedEvent = new DragEvent('drop', { dataTransfer: event.dataTransfer });
  emit('drop', clonedEvent, teamId);
};

const removeFromTeam = (member, teamId) => {
  emit('remove-from-team', member, teamId);
};
</script>

<style scoped>
.team-management {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.team-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
}

.add-team-button {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-color: #686868;
  color: #fff;
  border-radius: 8px;
  height: 256px;
  cursor: pointer;
  transition: background-color 0.2s;
  padding: 1rem;
  text-align: center;
  max-width: 232px;
}

.add-team-button:hover {
  background-color: #9370db;
}

.add-team-button span {
  margin-top: 0.5rem;
  font-size: 0.875rem;
}
</style>