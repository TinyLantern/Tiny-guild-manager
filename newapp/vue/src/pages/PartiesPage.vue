<template>
  <div class="page-container">
    <CustomNotification ref="customNotify" />

    <div class="columns-container">
      <div class="left-column">
        <div class="role-cards">
          <RoleCard
            v-for="role in roleConfig"
            :key="role.category"
            :title="role.title"
            :icon="role.icon"
            :color="role.color"
            :members="getMembersByCategory(role.category)"
            :maxMembers="getMaxMembersByCategory(role.category)"
            :category="role.category"
            @drag-start="handleDragStart"
          />
        </div>
      </div>

      <div class="right-column">
        <TeamManagement
          :teams="teams"
          @add-team="addTeam"
          @remove-team="removeTeam"
          @drop="handleDrop"
          @drag-start="handleDragStart"
          @remove-from-team="removeFromTeam"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from 'src/stores/auth';
import { roleConfig } from 'src/pages/configs/PartiesConfig.js';
import RoleCard from 'src/components/Parties/RoleCard.vue';
import TeamManagement from 'src/components/Parties/TeamManagement.vue';
import CustomNotification from 'src/components/CustomNotification.vue';

const authStore = useAuthStore();

const tanks = ref([]);
const healers = ref([]);
const dps = ref([]);
const maxTanks = ref(0);
const maxHealers = ref(0);
const maxDPS = ref(0);
const teams = ref([]);

const customNotify = ref(null);

const roleToCategoryMap = {
  'Tank': 'tanks',
  'Healer': 'healers',
  'DPS': 'dps',
};

const getMembersByCategory = (category) => {
  switch (category) {
    case 'tanks': return tanks.value;
    case 'healers': return healers.value;
    case 'dps': return dps.value;
    default: return [];
  }
};

const getMaxMembersByCategory = (category) => {
  switch (category) {
    case 'tanks': return maxTanks.value;
    case 'healers': return maxHealers.value;
    case 'dps': return maxDPS.value;
    default: return 0;
  }
};

const fetchTeams = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/teams', {
      headers: { Authorization: `Bearer ${authStore.token}` },
      withCredentials: true,
    });

    if (Array.isArray(response.data)) {
      teams.value = response.data.map(team => ({
        ...team,
        members: (team.members || []).map(member => ({
          ...member,
          icon: roleConfig.find(role => role.category === roleToCategoryMap[member.role])?.icon || 'person',
          color: roleConfig.find(role => role.category === roleToCategoryMap[member.role])?.color || 'white',
        })),
      }));
    }
  } catch (error) {
    console.error('Error fetching teams:', error);
    customNotify.value.showCustomNotify('Error fetching teams. Please try again.', 'error');
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

    teams.value.push({
      id: response.data.id,
      team_name: newTeamName,
      members: [],
    });

  } catch (error) {
    console.error('Error adding team:', error);
    customNotify.value.showCustomNotify('Error adding team. Please try again.', 'error');
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
    customNotify.value.showCustomNotify('Error removing team. Please try again.', 'error');
  }
};

const fetchGuildMembers = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/members-for-parties', {
      headers: { Authorization: `Bearer ${authStore.token}` },
      withCredentials: true,
    });

    console.log('Fetched members:', response.data);

    tanks.value = [];
    healers.value = [];
    dps.value = [];

    response.data.forEach((member) => {
      if (!member.id) {
        console.error('Missing character_id:', member);
      }

      const category = roleToCategoryMap[member.role];

      const roleIcon = roleConfig.find((role) => role.category === category)?.icon || 'person';
      const roleColor = roleConfig.find(role => role.category === roleToCategoryMap[member.role])?.color || 'white';

      const memberData = { 
        id: member.id,
        name: member.username, 
        role: member.role,
        icon: roleIcon,
        color: roleColor,
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
    customNotify.value.showCustomNotify('Error fetching guild members. Please try again.', 'error');
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
    customNotify.value.showCustomNotify('Error fetching role limits. Please try again.', 'error');
  }
}; 

const handleDragStart = (event, member, category) => {
  console.log('Dragging:', member);
  const dragData = JSON.stringify({ 
    member: { 
      id: member.id, 
      name: member.name, 
      role: member.role,
      icon: member.icon,
      color: member.color,
    }, 
    category 
  });
  console.log('Drag Data:', dragData);
  event.dataTransfer.setData('application/json', dragData);
  event.dataTransfer.effectAllowed = 'move';
};

const handleDrop = async (event, teamId) => {
  event.preventDefault();
  event.stopPropagation();
  try {
    const dragData = event.dataTransfer.getData('application/json');
    console.log('Drop Data:', dragData);

    if (!dragData) {
      console.error('No drag data found.');
      customNotify.value.showCustomNotify('No drag data found.', 'error');
      return;
    }

    const { member, category } = JSON.parse(dragData);

    const team = teams.value.find(t => t.id === teamId);
    if (!team) return;

    const maxTeamMembers = 6;

    if (team.members.length >= maxTeamMembers) {
      customNotify.value.showCustomNotify('This team is already full. Cannot add more members.', 'error');
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
    customNotify.value.showCustomNotify('Error processing drop. Please try again.', 'error');
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


    if (member.role === 'Tank') {
      tanks.value.push(member);
    } else if (member.role === 'Healer') { 
      healers.value.push(member);
    } else if (member.role === 'DPS') {
      dps.value.push(member);
    }


    teams.value = [...teams.value];
    tanks.value = [...tanks.value];
    healers.value = [...healers.value];
    dps.value = [...dps.value];

  } catch (error) {
    console.error('Error removing member from team:', error);
    customNotify.value.showCustomNotify('Error removing member from team. Please try again.', 'error');
  }
};

onMounted(async () => {
  await fetchRoleLimits();
  await fetchGuildMembers();
  await fetchTeams();
});
</script>

<style scoped>
.page-container {
  color: white;
  padding: 2rem;
  min-height: 100vh;
}

.columns-container {
  padding: 1rem;
  display: flex;
}

.left-column {
  padding-left: 0.5rem;
  flex: 1;
  max-width: 35%;
}

.right-column {
  flex: 2;
  max-width: 65%;
}

.role-cards {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.team-management {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.team-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}
</style>