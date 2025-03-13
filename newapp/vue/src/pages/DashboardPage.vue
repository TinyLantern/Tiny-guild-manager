<template>
  <div class="dashboardview">
    <CustomNotify ref="notify" />
      <div class="top-section">

      <div class="guild-logo-section">
        <img
          :src="guildInfo.logo || 'http://newapp.test/storage/guild_logos/placeholder.jpg'"
          alt="Guild Logo"
          class="guild-logo"
        />
      </div>

      <div class="guild-info-section">
        <div class="text-h4">{{ guildInfo.name }}</div>
        <div class="text-subtitle1">Server: {{ guildInfo.server }}</div>
      </div>

      <div class="tiles-section">
        <TileSection :tiles="tilesData" />
      </div>
    </div>

    <TableSection
      :columns="activityColumns"
      :data="upcomingActivities.length ? upcomingActivities : [{ Activity: 'No upcoming activities', Description: '', Date: '', Time: '' }]"
    />

    <div v-if="isGuildMaster" class="upload-container">
      <GuildLogoUpload
        label="Upload Guild Logo"
        @file-uploaded="handleFileUpload"
      />
      <q-btn
        v-if="uploadedFile"
        label="Finish Upload"
        @click="handleLogoUpload"
        class="upload-button"
      />
    </div>
  </div>
</template>

<script setup>
import TileSection from 'src/components/Dashboard/subcomponents/TileSection.vue';
import TableSection from 'src/components/Dashboard/subcomponents/TableSection.vue';
import GuildLogoUpload from 'src/components/Forms/FileUpload.vue';
import CustomNotify from 'src/components/CustomNotification.vue';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useAuthStore } from 'src/stores/auth';

const authStore = useAuthStore();
const loggedInUser = authStore.user;
authStore.initializeSanctum();

const tilesData = ref([]);
const guildInfo = ref({
  name: '',
  server: '',
  logo: '',
});

const activityColumns = ref(['Activity', 'Description', 'Date', 'Time']);
const upcomingActivities = ref([]);
const uploadedFile = ref(null);
const isGuildMaster = computed(() => authStore.guildRank === 'Guild Master');
const notify = ref(null);

const fetchGuildInfo = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/guild-info', {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
      withCredentials: true,
    });
    guildInfo.value = response.data;
  } catch (error) {
    console.error('Failed to fetch guild info:', error);
  }
};

const fetchUpcomingActivities = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/upcomming-activities', {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
      withCredentials: true,
    });

    upcomingActivities.value = response.data.map((activity) => ({
      id: activity.id,
      Activity: activity.name,
      Description: activity.description || 'Other',
      Date: new Date(activity.start_time).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
      }),
      Time: new Date(activity.start_time).toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
      }),
    }));
  } catch (error) {
    console.error('Failed to fetch upcoming activities:', error);
  }
};

const fetchTileData = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/guild-character-counts', {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
      withCredentials: true,
    });
    tilesData.value = [
      { title: 'Active Members', value: response.data.active_members },
      { title: 'Tanks', value: response.data.tanks },
      { title: 'DPS', value: response.data.dps },
      { title: 'Healers', value: response.data.healers },
    ];
  } catch (error) {
    console.error('Failed to fetch tile data:', error);
  }
};

const handleFileUpload = (file) => {
  uploadedFile.value = file;
};

const handleLogoUpload = async () => {
  if (uploadedFile.value) {
    try {
      const formData = new FormData();
      formData.append('logo', uploadedFile.value);

      const response = await axios.post('http://newapp.test/api/upload-guild-logo', formData, {
        headers: {
          Authorization: `Bearer ${authStore.token}`,
          'Content-Type': 'multipart/form-data',
        },
        withCredentials: true,
      });

      guildInfo.value.logo = response.data.logoUrl;
      console.log('Logo uploaded successfully:', response.data);
      uploadedFile.value = null;

      notify.value.showCustomNotify('Logo uploaded successfully!', 'success');
    } catch (error) {
      console.error('Failed to upload logo:', error);

      notify.value.showCustomNotify('Failed to upload logo. Please try again.', 'error');
    }
  }
};

onMounted(() => {
  fetchGuildInfo();
  fetchTileData();
  fetchUpcomingActivities();
});
</script>

<style scoped>
.dashboardview {
  padding: 1rem;
  max-width: 1400px;
  margin: auto;
  position: relative;
}

.top-section {
  display: flex;
  align-items: center;
  gap: 1rem;
  background-color: var(--custom-dark);
  border-radius: 8px;
  margin: 1rem;
  margin-bottom: 2rem;
  margin-top: 2rem;
  padding: 1rem;
}

.guild-logo-section {
  flex: 0 0 auto;
}

.guild-logo {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
}

.guild-info-section {
  flex: 1;
  color: white;
  margin-left: 1rem;
}

.tiles-section {
  flex: 1;
  display: flex;
  gap: 0.5rem;
}

.no-activities {
  text-align: center;
  padding: 20px;
  font-size: 1.2em;
  color: #888;
  background-color: #f9f9f9;
  border-radius: 8px;
  margin-top: 20px;
}

.upload-container {
  max-width: 500px;
  margin: 0 auto;
  padding: 1rem;
}

.upload-button {
  background-color: var(--custom-purple);
  margin-top: 1rem;
  width: 100%;
}
</style>
