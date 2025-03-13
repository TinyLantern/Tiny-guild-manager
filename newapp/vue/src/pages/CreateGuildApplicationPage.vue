<template>
  <q-page class="q-pa-md">
    <CustomNotification ref="customNotify" />

    <q-card bordered class="guild-application-card no-shadow">
      <q-card-section>
        <q-form @submit.prevent="handleSubmit">
          <FormHeader title="Apply to join a guild" />

          <component
            v-for="(field, index) in visibleFormFields"
            :key="index"
            :is="field.component"
            v-model="form[field.model]"
            v-bind="field.props"
            @update:model="form[field.model] = $event"
          />

          <FormButton v-if="isFormComplete" label="Apply" color="primary" />
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useAuthStore } from 'src/stores/auth';
import axios from 'axios';
import { createGuildApplicationFormFields } from 'src/pages/configs/CreateGuildApplicationConfig.js';
import FormButton from 'src/components/Forms/FormSubmitButton.vue';
import FormHeader from 'src/components/Forms/FormHeader.vue';
import CustomNotification from 'src/components/CustomNotification.vue';

const authStore = useAuthStore();
const loggedInUser = authStore.user;
authStore.initializeSanctum();

const form = ref({
  selectedServer: null,
  selectedGuild: null,
  selectedCharacter: null,
});

const serverOptions = ref([]);
const guildOptions = ref([]);
const characterOptions = ref([]);

const customNotify = ref(null);

const formFields = computed(() =>
  createGuildApplicationFormFields(serverOptions.value, guildOptions.value, characterOptions.value)
);


const visibleFormFields = computed(() =>
  formFields.value.filter((field) => field.condition(form.value))
);


const isFormComplete = computed(() =>
  form.value.selectedServer && form.value.selectedGuild && form.value.selectedCharacter
);

const fetchServerOptions = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/servers', {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
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

const fetchGuildsForServer = async (serverId) => {
  try {
    const response = await axios.get(`http://newapp.test/api/servers/${serverId}/guilds`, {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
      withCredentials: true,
    });
    guildOptions.value = response.data.map((guild) => ({
      label: guild.name,
      value: guild.id,
    }));
  } catch (error) {
    console.error('Error fetching guild options:', error);
    customNotify.value.showCustomNotify('Failed to load guild options.', 'error');
  }
};

const fetchCharacterOptions = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/characterapplication', {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
      withCredentials: true,
    });
    characterOptions.value = response.data.map((character) => ({
      label: character.character_name,
      value: character.id,
    }));
  } catch (error) {
    console.error('Error fetching character options:', error);
    customNotify.value.showCustomNotify('Failed to load characters.', 'error');
  }
};

watch(
  () => form.value.selectedServer,
  (newVal) => {
    if (newVal) {
      form.value.selectedGuild = null;
      form.value.selectedCharacter = null;
      fetchGuildsForServer(newVal);
    }
  }
);

watch(
  () => form.value.selectedGuild,
  (newVal) => {
    if (newVal) {
      form.value.selectedCharacter = null;
    }
  }
);

const handleSubmit = async () => {
  try {
    if (!loggedInUser || !loggedInUser.id) {
      customNotify.value.showCustomNotify('You must be logged in to apply to a guild.', 'error');
      return;
    }

    if (!isFormComplete.value) {
      customNotify.value.showCustomNotify('Please fill out all required fields.', 'error');
      return;
    }

    const formData = new FormData();
    formData.append('guild_id', form.value.selectedGuild);
    formData.append('character_id', form.value.selectedCharacter);

    await axios.post('http://newapp.test/api/createguildapplication', formData, {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
      withCredentials: true,
    });

    customNotify.value.showCustomNotify('Application to the guild has been sent!', 'success');

    await authStore.fetchLastActiveCharacter();
    authStore.persistAuthData();
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
.guild-application-card {
  max-width: 400px;
  margin: 0 auto;
  margin-top: 70px;
  background-color: var(--custom-dark);
  border-radius: 4px;
  padding: 1rem;
  color: #fff;
}

.q-btn {
  background-color: var(--custom-purple) !important;
  margin-top: 2.5px;
}
</style>
