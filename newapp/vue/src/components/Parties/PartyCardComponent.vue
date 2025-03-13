<template>
    <div class="container">
      <div class="role-cards">
        <div class="category-card">
          <div class="card-header">
            <q-icon name="shield" color="purple" />
            <h3>Tanks</h3>
            <span class="count">{{ tanks.length }}/{{ maxTanks }}</span>
          </div>
          <div class="card-content">
            <div
              v-for="(tank, index) in tanks"
              :key="index"
              class="draggable-item"
              draggable="true"
              @dragstart="(event) => dragStart(event, tank, 'tanks')"
            >
              <q-avatar icon="person" />
              <span>{{ tank.name }}</span>
            </div>
            <div v-if="!tanks.length" class="empty">No tanks found</div>
          </div>
        </div>
  
        <div class="category-card">
          <div class="card-header">
            <q-icon name="favorite" color="green" />
            <h3>Healers</h3>
            <span class="count">{{ healers.length }}/{{ maxHealers }}</span>
          </div>
          <div class="card-content">
            <div
              v-for="(healer, index) in healers"
              :key="index"
              class="draggable-item"
              draggable="true"
              @dragstart="(event) => dragStart(event, healer, 'healers')"
            >
              <q-avatar icon="person" />
              <span>{{ healer.name }}</span>
            </div>
            <div v-if="!healers.length" class="empty">No healers found</div>
          </div>
        </div>
  
        <div class="category-card">
          <div class="card-header">
            <q-icon name="flash" color="red" />
            <h3>DPS</h3>
            <span class="count">{{ dps.length }}/{{ maxDPS }}</span>
          </div>
          <div class="card-content">
            <div
              v-for="(dpsMember, index) in dps"
              :key="index"
              class="draggable-item"
              draggable="true"
              @dragstart="(event) => dragStart(event, dpsMember, 'dps')"
            >
              <q-avatar icon="person" />
              <span>{{ dpsMember.name }}</span>
            </div>
            <div v-if="!dps.length" class="empty">No DPS found</div>
          </div>
        </div>
      </div>
  
      <div class="team-management">
        <q-btn
          label="Add Team"
          color="primary"
          size="sm"
          class="add-team-button"
          @click="addTeam"
        />
      </div>
  
      <div class="team-grid">
      <div
        v-for="(team) in teams"
        :key="team.id"
        class="team-card"
      >
        <div class="team-header">
          <h3>{{ team.team_name }}</h3>
          <q-btn flat dense icon="close" @click="removeTeam(team.id)" />
        </div>
        <div
          class="team-content"
          @dragover.prevent
          @drop="(event) => drop(event, team.id)"
        >
          <div
            v-for="(member, index) in team.members"
            :key="index"
            class="team-member"
          >
            <q-avatar icon="person" />
            <span>{{ member.name }}</span>
            <q-btn flat dense icon="close" @click="removeFromTeam(member, team.id)" />
          </div>
          <div v-if="!team.members.length" class="empty">Drag members here</div>
        </div>
      </div>
    </div>

    </div>
</template>
  
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from 'src/stores/auth';

const authStore = useAuthStore();

const tanks = ref([]);
const healers = ref([]);
const dps = ref([]);

const maxTanks = ref(0);
const maxHealers = ref(0);
const maxDPS = ref(0);

const teams = ref([]);

const fetchTeams = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/teams', {
      headers: { Authorization: `Bearer ${authStore.token}` },
      withCredentials: true,
    });

    console.log('Fetched teams:', response.data);

    if (Array.isArray(response.data)) {
      teams.value = response.data.map(team => ({
        ...team,
        members: team.members || [],
      }));
    }
  } catch (error) {
    console.error('Error fetching teams:', error);
  }
};

const addTeam = async () => {
  try {
    const newTeamName = `Team ${teams.value.length + 1}`;
    const response = await axios.post(
      'http://newapp.test/api/teams',
      { name: newTeamName },
      {
        headers: { Authorization: `Bearer ${authStore.token}` },
        withCredentials: true,
      }
    );

    teams.value.push(response.data);

    await fetchTeams();
    await fetchGuildMembers();
  } catch (error) {
    console.error('Error adding team:', error);
  }
};

const removeTeam = async (teamId) => {
  try {
    await axios.delete(`http://newapp.test/api/teams/${teamId}`, {
      headers: { Authorization: `Bearer ${authStore.token}` },
      withCredentials: true,
    });

    teams.value = teams.value.filter(team => team.id !== teamId);

    await fetchGuildMembers();
  } catch (error) {
    console.error('Error removing team:', error);
  }
};
  
const fetchGuildMembers = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/members-for-parties', {
      headers: { Authorization: `Bearer ${authStore.token}` },
      withCredentials: true,
    });

    // Debug response
    console.log('Fetched members:', response.data);

    tanks.value = [];
    healers.value = [];
    dps.value = [];

    response.data.forEach((member) => {
      if (!member.id) {
        console.error('Missing character_id:', member);
      }

      const memberData = { 
        id: member.id,
        name: member.username, 
        role: member.role 
      };

      if (member.role === 'Tank') {
        tanks.value.push(memberData);
      } else if (member.role === 'Healer') {
        healers.value.push(memberData);
      } else if (member.role === 'DPS') {
        dps.value.push(memberData);
      }
    });
  } catch (error) {
    console.error('Error fetching guild members:', error);
  }
};



const fetchRoleLimits = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/guild-character-counts', {
      headers: { Authorization: `Bearer ${authStore.token}` },
      withCredentials: true,
    });

    maxTanks.value = response.data.tanks || 12;
    maxHealers.value = response.data.healers || 12;
    maxDPS.value = response.data.dps || 28;
  } catch (error) {
    console.error('Failed to fetch role limits:', error);
  }
}; 

const dragStart = (event, member, category) => {
  console.log('Dragging:', member);
  event.dataTransfer.setData(
    'application/json',
    JSON.stringify({ 
      member: { id: member.id, name: member.name, role: member.role },
      category 
    })
  );
  event.dataTransfer.effectAllowed = 'move';
};

  

const drop = async (event, teamId) => {
  event.preventDefault();

  try {
    const { member, category } = JSON.parse(event.dataTransfer.getData('application/json'));

    const team = teams.value.find(t => t.id === teamId);
    if (!team) return;

    const maxTeamMembers = 6;

    if (team.members.length >= maxTeamMembers) {
      alert('This team is already full. Cannot add more members.');
      return;
    }


    if (!team.members.some(m => m.id === member.id)) {
      team.members.push(member);

      if (category === 'tanks') {
        tanks.value = tanks.value.filter(tank => tank.id !== member.id);
      } else if (category === 'healers') {
        healers.value = healers.value.filter(healer => healer.id !== member.id);
      } else if (category === 'dps') {
        dps.value = dps.value.filter(dpsMember => dpsMember.id !== member.id);
      }

      await axios.post('http://newapp.test/api/team-members', {
        team_id: teamId,
        character_id: member.id,
      }, {
        headers: { Authorization: `Bearer ${authStore.token}` },
        withCredentials: true,
      });
    }
  } catch (error) {
    console.error('Error processing drop:', error);
  }
};

const removeFromTeam = async (member, teamId) => {
  const team = teams.value.find(t => t.id === teamId);
  if (!team) return;

  try {
    await axios.delete(`http://newapp.test/api/team-members`, {
      data: { 
        team_id: teamId,
        character_id: member.id 
      },
      headers: { Authorization: `Bearer ${authStore.token}` },
      withCredentials: true,
    });

    team.members = team.members.filter(m => m.id !== member.id);

    if (member.role === 'tanks') {
      tanks.value.push(member);
    } else if (member.role === 'healers') {
      healers.value.push(member);
    } else if (member.role === 'dps') {
      dps.value.push(member);
    }

    teams.value = [...teams.value];
  } catch (error) {
    console.error('Error removing member from team:', error);
  }
};

onMounted(async () => {
  await fetchRoleLimits();
  await fetchGuildMembers();
  await fetchTeams();
});
</script>
  
<style scoped lang="scss">
.container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.role-cards {
  display: flex;
  gap: 1.5rem;
}

.team-management {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.team-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}

.category-card,
.team-card {
  background-color: #1e1e2e;
  color: #fff;
  border-radius: 8px;
  padding: 1rem;
  flex: 1;
  min-width: 250px;
}

.card-header,
.team-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.card-content,
.team-content {
  background-color: #121212;
  border-radius: 8px;
  padding: 1rem;
  min-height: 200px;
}

.draggable-item,
.team-member {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 0.5rem;
  padding: 0.5rem;
  background-color: #2a2a3c;
  border-radius: 6px;
  cursor: grab;
}

.draggable-item:hover,
.team-member:hover {
  background-color: #3a3a4c;
}

.empty {
  text-align: center;
  color: #888;
  margin-top: 2rem;
}

.add-team-button {
  margin-left: 1rem;
}
</style>