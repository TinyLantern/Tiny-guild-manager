<template>
  <div class="team-card">
    <div class="team-header">
      <h3>{{ team.team_name }}</h3>
      <q-btn flat dense icon="close" @click="removeTeam(team.id)" size="12px" />
    </div>
    <div
      class="team-content"
      :class="{ 'empty-state': !team.members.length }"
      @dragover.prevent
      @drop="handleDrop"
    >
      <div
        v-for="(member, index) in team.members"
        :key="index"
        class="team-member"
      >
        <q-avatar :icon="member.icon" :text-color="member.color" size="26px"/>
        <span>{{ member.name }}</span>
        <q-btn
          flat
          dense
          icon="close"
          size="10px"
          @click="removeMember(member)"
          class="remove-button"
        />
      </div>
      <div v-if="!team.members.length" class="empty">Drag members here</div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  team: Object,
});

const emit = defineEmits(['remove-team', 'drop', 'remove-from-team']);

const removeTeam = (teamId) => {
  emit('remove-team', teamId);
};

const handleDrop = (event) => {
  console.log('TeamCard Drop Event:', event);
  const clonedEvent = new DragEvent('drop', { dataTransfer: event.dataTransfer });
  emit('drop', clonedEvent, props.team.id);
};

const removeMember = (member) => {
  emit('remove-from-team', member, props.team.id);
};
</script>

<style scoped>
.team-card {
  color: #fff;
  border-radius: 8px;
  flex: 1;
  max-width: 232px;
  height: 256px;
  display: flex;
  flex-direction: column;
  margin-bottom: 1rem;
}

.team-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.5rem;
  height: 40px;
  background-color: var(--custom-purple);
  padding: 0 1rem;
  border-radius: 8px 8px 0 0;
}

.team-header h3 {
  font-size: 1rem;
  margin: 0;
  font-weight: 500;
}

.team-header .q-btn{
  transition: color 0.2s ease;
}

.team-header .q-btn:hover{
  color: #ff0000;
}

.team-content {
  background-color: var(--custom-dark);
  border-radius: 0 0 8px 8px;
  padding: 1rem;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  overflow-y: auto;
}

.team-content.empty-state {
  display: flex; 
  align-items: center; 
  justify-content: center; 
}

.team-member {
  display: flex;
  align-items: center;
  background-color: var(--custom-gray);
  border-radius: 4px;
  cursor: grab;
  max-width: 200px; 
  height: 24px;
  box-sizing: border-box; 
}

.team-member span {
  white-space: nowrap; 
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: calc(100% - 4rem);
}

.team-member .q-btn {
  color: #fff;
  transition: color 0.2s ease;
  margin-left: auto;
}


.team-member .q-btn:hover {
  color: #ff0000;
}

.team-member:hover {
  background-color: var(--custom-purple);
}

.empty {
  text-align: center;
  color: #fff;
}
</style>