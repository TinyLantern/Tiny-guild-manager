<template>
  <q-page class="page-container">
    <CustomNotify ref="customNotify" />

    <div v-if="loading" class="loading-state">Loading...</div>
    <div v-if="error" class="error-state">{{ error }}</div>

    <div v-else-if="memberDetails">
      <q-card bordered class="member-details-card no-shadow">
        <q-card-section class="card-content">
          <div class="left-half">
            <FormHeader class="button-container">
              <component
                v-for="(button, index) in formButtons.filter(button => button.props.condition)"
                :key="index"
                :is="button.component"
                v-bind="button.props"
              />
            </FormHeader>

            <q-list dense class="form-left">
              <q-item v-for="(field, index) in formFields" :key="index" class="form-item">
                <component
                  :is="field.component"
                  v-model="memberDetails[field.model]"
                  v-bind="field.props"
                  :readonly="!isEditMode"
                />
              </q-item>
            </q-list>

            <div class="file-upload-section">
              <FileUploader label="Upload Gear Picture" @file-uploaded="handleFileUpload" />
              <q-btn
                label="Upload"
                color="primary"
                @click="saveGearPicture"
                :disabled="!gearPictureFile"
                class="upload-button"
              />
            </div>
          </div>

          <div class="right-half">
            <q-img 
              v-if="memberDetails.gear_picture" 
              :src="`http://newapp.test/storage/gear_pictures/${memberDetails.gear_picture}`" 
              alt="Profile Image" 
              class="profile-image" 
              spinner-color="white" 
            />
            <q-img 
              v-else 
              src="http://newapp.test/storage/gear_pictures/placeholder.png" 
              alt="Default Image" 
              class="profile-image" 
              spinner-color="white" 
            />
          </div>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { useAuthStore } from 'src/stores/auth';
import { useQuasar } from 'quasar';
import { createMemberFormFields, createMemberFormButtons } from 'src/pages/configs/MemberFormConfig.js';
import FormHeader from 'src/components/Forms/MemberHeader.vue';
import FileUploader from 'src/components/Forms/FileUpload.vue';
import CustomNotify from 'src/components/CustomNotification.vue';

const authStore = useAuthStore();
const loggedInUser = authStore.user;
authStore.initializeSanctum();
const $q = useQuasar();

const route = useRoute();
const router = useRouter();
const memberId = ref(route.params.id);

const memberDetails = ref(null);
const roleOptions = ref([]);
const weaponOptions = ref([]);
const guildRankOptions = ref([]);
const loading = ref(true);
const error = ref(null);
const isEditMode = ref(false);
const gearPictureFile = ref(null);

const customNotify = ref(null);

const isActiveCharacter = computed(() => memberDetails.value?.id === authStore.activeCharacter?.id);
const isGuildMasterOrAdvisor = computed(() => authStore.guildRank === 'Guild Master' || authStore.guildRank === 'Advisor');
const formFields = computed(() => createMemberFormFields(roleOptions.value, weaponOptions.value, guildRankOptions.value));
const formButtons = computed(() =>
  createMemberFormButtons(
    isEditMode.value,
    isActiveCharacter.value,
    isGuildMasterOrAdvisor.value,
    handleEditSave,
    deleteCharacter,
    removeFromGuild
  ).filter((button) => !button.props.condition || button.props.condition)
);

const fetchDropdownOptions = async () => {
  try {
    const [rolesResponse, weaponsResponse, ranksResponse] = await Promise.all([
      axios.get('http://newapp.test/api/character-type'),
      axios.get('http://newapp.test/api/weapons'),
      axios.get('http://newapp.test/api/guild-ranks'),
    ]);
    roleOptions.value = rolesResponse.data;
    weaponOptions.value = weaponsResponse.data;
    guildRankOptions.value = ranksResponse.data;
  } catch (err) {
    console.error('Error fetching dropdown options:', err);
    customNotify.value.showCustomNotify('Failed to load dropdown options', 'error');
  }
};

const fetchMemberData = async () => {
  try {
    loading.value = true;
    const response = await axios.get(`http://newapp.test/api/characters/${memberId.value}`, {
      headers: { Authorization: `Bearer ${authStore.token}` },
      withCredentials: true,
    });
    memberDetails.value = { ...response.data, rank: response.data.rank };
    loading.value = false;
  } catch (err) {
    console.error('Error fetching member data:', err);
    customNotify.value.showCustomNotify('Failed to load member data', 'error');
    loading.value = false;
  }
};

const handleFileUpload = (file) => {
  gearPictureFile.value = file;
};

const saveGearPicture = async () => {
  if (!gearPictureFile.value) {
    customNotify.value.showCustomNotify('No file selected', 'warning');
    return;
  }

  try {
    const formData = new FormData();
    formData.append('gear_picture', gearPictureFile.value);

    await axios.post(
      `http://newapp.test/api/characters/${memberId.value}/upload-gear-picture`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
          Authorization: `Bearer ${authStore.token}`,
        },
        withCredentials: true,
      }
    );

    customNotify.value.showCustomNotify('Gear picture uploaded successfully', 'success');
    fetchMemberData();
  } catch (error) {
    console.error('Error uploading gear picture:', error);
    customNotify.value.showCustomNotify('Failed to upload gear picture', 'error');
  }
};

const deleteCharacter = async () => {
  $q.dialog({
    title: 'Confirm Delete',
    message: 'Are you sure you want to delete this character? This action cannot be undone.',
    cancel: true,
    class: 'no-shadow',
    ok: {
      label: 'Yes, Delete!',
      color: 'red',
      flat: true,
    },
    cancel: {
      color: 'white',
      flat: true,
    },
  }).onOk(async () => {
    try {
      await axios.delete(`http://newapp.test/api/characters/${memberId.value}`, {
        headers: { Authorization: `Bearer ${authStore.token}` },
        withCredentials: true,
      });
      customNotify.value.showCustomNotify('Character deleted successfully', 'success');
      setTimeout(() => {
        router.push('/members');
      }, 2000);
    } catch (error) {
      console.error('Error deleting character:', error);
      customNotify.value.showCustomNotify('Failed to delete character', 'error');
    }
  });
};

const removeFromGuild = async () => {
  $q.dialog({
    title: 'Confirm Remove',
    message: 'Are you sure you want to remove this character from the guild?',
    cancel: true,
    class: 'no-shadow',
    ok: {
      label: 'Yes, Remove!',
      color: 'red',
      flat: true,
    },
    cancel: {
      color: 'white',
      flat: true,
    },
  }).onOk(async () => {
    try {
      await axios.put(
        `http://newapp.test/api/characters/${memberId.value}/remove-from-guild`,
        {},
        { headers: { Authorization: `Bearer ${authStore.token}` }, withCredentials: true }
      );
      customNotify.value.showCustomNotify('Character removed from guild successfully', 'success');
      setTimeout(() => {
        router.push('/members');
      }, 2000);
    } catch (error) {
      console.error('Error removing character from guild:', error);
      customNotify.value.showCustomNotify('Failed to remove character from guild', 'error');
    }
  });
};

const getGuildRankId = (rank) => {
  if (typeof rank === 'number') {
    return rank;
  }
  const rankObj = guildRankOptions.value.find((r) => r.rank === rank);
  return rankObj ? rankObj.id : null;
};

const updateMemberData = async () => {
  try {
    const payload = {
      character_name: memberDetails.value.character_name,
      role: memberDetails.value.role,
      primary_weapon: memberDetails.value.primary_weapon,
      secondary_weapon: memberDetails.value.secondary_weapon,
      gear_score: memberDetails.value.gear_score,
      guild_rank_id: getGuildRankId(memberDetails.value.rank),
    };
    await axios.put(`http://newapp.test/api/characters/${memberId.value}`, payload, {
      headers: { Authorization: `Bearer ${authStore.token}` },
      withCredentials: true,
    });
    fetchMemberData();
    customNotify.value.showCustomNotify('Member details saved successfuly!', 'success');
  } catch (error) {
    console.error('Error saving member details:', error);
    customNotify.value.showCustomNotify('Failed to save member details', 'error');
  }
};

const handleEditSave = async () => {
  if (isEditMode.value) await updateMemberData();
  isEditMode.value = !isEditMode.value;
};

// Lifecycle Hooks
onMounted(() => {
  fetchMemberData();
  fetchDropdownOptions();
});

watch(() => route.params.id, (newId) => {
  memberId.value = newId;
  fetchMemberData();
});
</script>

<style scoped lang="scss">
.page-container {
  color: #fff;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.q-card__section {
  padding: 2rem;
}

.member-details-card {
  max-width: 1200px;
  margin: 0 auto;
  background-color: var(--custom-dark);
  color: white;
  border-radius: 4px;
}

.loading-state,
.error-state {
  color: #f44336;
  font-size: 1.2rem;
  text-align: center;
}

.card-content {
  display: flex;
  justify-content: space-between;
  gap: 20px;
}

.left-half {
  width: 40%;
  display: flex;
  flex-direction: column;
}

.right-half {
  width: 60%;
  display: flex;
  justify-content: center;
  align-items: start;
}

.profile-image {
  width: 100%;
  height: auto;
  max-height: 550px;
  object-fit: contain;
  border-radius: 8px;
}

.form-item {
  display: flex;
  align-items: center;
  padding-bottom: 1.5rem;
}

.input-field, .q-select {
  width: 100%;
  padding: 0px;
  border-radius: 4px;
}

.q-select {
  border: 1px solid #ccc;
  border-radius: 4px;
}

.upload-section {
  padding-bottom: 1rem;
}

.file-upload-section {
  padding: 0 1rem 0 1rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

.upload-button {
  background-color: var(--custom-purple) !important;
  width: 100%;
  max-width: 200px;
}

.button-container {
  display: flex;
  justify-content: center;
  gap: 10px;
  padding-bottom: 1.5rem;
}

.button-container .q-btn{
  padding: 8px 16px;
}

</style>