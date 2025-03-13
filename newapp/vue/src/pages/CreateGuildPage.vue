<template>
  <q-page class="create-guild-page">
    <CustomNotification ref="customNotify" />

    <q-card bordered class="create-guild-card no-shadow">
      <FormHeader title="Create New Guild" />

      <q-card-section>
        <q-form @submit.prevent="handleSubmit">
          <component
            v-for="(field, index) in formFields"
            :key="index"
            :is="field.component"
            v-model="form[field.model]"
            v-bind="field.props"
          />

          <FileUploader label="Upload Guild Logo" @file-uploaded="handleFileUpload" />

          <FormButton label="Create Guild" color="purple" />
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useAuthStore } from 'src/stores/auth';
import axios from 'axios';
import { createGuildFormFields } from 'src/pages/configs/CreateGuildConfig.js';
import FileUploader from 'src/components/Forms/FileUpload.vue';
import FormHeader from 'src/components/Forms/FormHeader.vue';
import FormButton from 'src/components/Forms/FormSubmitButton.vue';
import CustomNotification from 'src/components/CustomNotification.vue';

const authStore = useAuthStore();
const loggedInUser = authStore.user;
authStore.initializeSanctum();

const form = ref({
  guildName: '',
  server: '',
  character: '',
  logo: null,
});

const serverOptions = ref([]);
const characterOptions = ref([]);

const formFields = computed(() => createGuildFormFields(serverOptions.value, characterOptions.value));

const customNotify = ref(null);

const fetchCharacterOptions = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/characters', {
      headers: { Authorization: `Bearer ${authStore.token}` },
      withCredentials: true,
    });
    characterOptions.value = response.data.map((character) => ({
      label: character.username,
      value: character.id,
    }));
  } catch (error) {
    console.error('Error fetching characters:', error);
    customNotify.value.showCustomNotify('Failed to load characters.', 'error');
  }
};

const fetchServerOptions = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/servers', {
      headers: { Authorization: `Bearer ${authStore.token}` },
      withCredentials: true,
    });
    serverOptions.value = response.data.map((server) => ({
      label: server.name,
      value: server.id,
    }));
  } catch (error) {
    console.error('Error fetching server options:', error);
    customNotify.value.showCustomNotify('Failed to load server options.', 'error');
  }
};

const handleFileUpload = (file) => {
  form.value.logo = file;
};

const handleSubmit = async () => {
  try {
    if (!loggedInUser || !loggedInUser.id) {
      customNotify.value.showCustomNotify('You must be logged in to create a guild.', 'error');
      return;
    }

    const formData = new FormData();
    formData.append('guild_name', form.value.guildName);
    formData.append('server_id', form.value.server);
    formData.append('character_id', form.value.character);

    if (form.value.logo) {
      formData.append('guild_logo', form.value.logo);
    }

    await axios.post('http://newapp.test/api/createguild', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Authorization: `Bearer ${authStore.token}`,
      },
      withCredentials: true,
    });

    customNotify.value.showCustomNotify('Guild created successfully!', 'success');

    await authStore.fetchLastActiveCharacter();
    authStore.persistAuthData();

    // Reset form
    Object.keys(form.value).forEach((key) => {
      form.value[key] = key === 'logo' ? null : '';
    });
  } catch (error) {
    console.error('Error:', error);
    customNotify.value.showCustomNotify('An error occurred while submitting the form.', 'error');
  }
};

onMounted(() => {
  fetchServerOptions();
  fetchCharacterOptions();
});
</script>

<style scoped>
.create-guild-page {
  color: #fff;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  height: 100%;
  position: relative;
  padding: 1rem;
}

.create-guild-card {
  width: 400px;
  background-color: var(--custom-dark);
  border-radius: 4px;
  padding: 1rem;
  color: #fff;
  margin-top: 70px;
}

.q-btn {
  background-color: var(--custom-purple) !important;
  margin-top: 2.5px;
}
</style>