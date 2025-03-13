<template>
  <q-card-section>
    <div class="row q-col-gutter-md">
      <div class="col-12 col-md-4">
        <q-card class="participation-card">
          <q-card-section class="participating-cards">
            <div class="text-h6 text-center">Participating</div>
            <div class="participation-grid">
              <div
                v-for="member in participatingMembers"
                :key="member.id"
                class="participation-member"
              >
                <q-avatar :icon="roleToIconMap[member.character_role] || 'person'" :text-color="roleToColorMap[member.character_role]" size="20px" />
                <span>{{ member.name }}</span>
              </div>
              <div v-if="participatingMembers.length === 0" class="empty-placeholder">
                No participants yet
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-md-4">
        <q-card class="participation-card">
          <q-card-section class="participating-cards">
            <div class="text-h6 text-center">Not Participating</div>
            <div class="participation-grid">
              <div
                v-for="member in notParticipatingMembers"
                :key="member.id"
                class="participation-member"
              >
                <q-avatar :icon="roleToIconMap[member.character_role] || 'person'" :text-color="roleToColorMap[member.character_role]" size="20px" />
                <span>{{ member.name }}</span>
              </div>
              <div v-if="notParticipatingMembers.length === 0" class="empty-placeholder">
                No responses yet
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-md-4">
        <q-card class="participation-card">
          <q-card-section class="participating-cards">
            <div class="text-h6 text-center">Tentative</div>
            <div class="participation-grid">
              <div
                v-for="member in tentativeMembers"
                :key="member.id"
                class="participation-member"
              >
                <q-avatar :icon="roleToIconMap[member.character_role] || 'person'" :text-color="roleToColorMap[member.character_role]" size="20px"/>
                <span>{{ member.name }}</span>
              </div>
              <div v-if="tentativeMembers.length === 0" class="empty-placeholder">
                No tentative responses
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-card-section>
</template>

<script setup>
const props = defineProps({
  participatingMembers: Array,
  notParticipatingMembers: Array,
  tentativeMembers: Array,
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
.participation-card {
  border: 2px solid #ddd;
  border-radius: 8px;
  background: #222;
  min-height: 100px;
}

.participation-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
  padding: 10px 0 0 0 ;
  max-width: 100%;
}

.participation-member {
  background-color: var(--custom-gray);
  border-radius: 4px;
  text-align: center;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: start;
}

.empty-placeholder {
  text-align: center;
  color: gray;
  font-style: italic;
  grid-column: span 2;
}

.participating-cards{
  padding: 0.5rem;
}

</style>