<template>
  <q-card-section>
    <div class="text-h6 text-center">Guild Members Who Haven't Responded</div>
    <div class="unresponded-grid">
      <div
        v-for="member in unrespondedMembers"
        :key="member.id"
        class="unresponded-member"
      >
        <q-avatar :icon="roleToIconMap[member.character_role] || 'person'" :text-color="roleToColorMap[member.character_role]" size="20px" />
        <span>{{ member.name }}</span>
      </div>
    </div>
  </q-card-section>
</template>

<script setup>
const props = defineProps({
  unrespondedMembers: Array,
});

const roleToIconMap = {
  Tank: 'security',
  Healer: 'favorite',
  DPS: 'flash_on',
};

const roleToColorMap = {
  Tank: 'blue',
  Healer: 'green',
  DPS: 'red',
};
</script>

<style scoped>
.unresponded-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
  gap: 10px;
  padding: 1rem 10px 0 10px;
  max-width: 100%;
}

.unresponded-member {
  background-color: var(--custom-gray);
  border-radius: 4px;
  text-align: center;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: start;
}
</style>