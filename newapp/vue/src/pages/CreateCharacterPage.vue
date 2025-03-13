<template>
  <q-page class="register-page">
    <CustomNotification ref="customNotify" />

    <q-card bordered class="register-card no-shadow">
      <FormHeader title="Create a new Character" />

      <q-card-section>
        <q-form @submit.prevent="handleSubmit">
          <component
            v-for="(field, index) in formFields"
            :key="index"
            :is="field.component"
            v-model="form[field.model]"
            v-bind="field.props"
          />
          <FileUploader label="Upload Gear Picture" @file-uploaded="handleFileUpload" />
          <FormButton label="Register" color="purple" />
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useAuthStore } from 'src/stores/auth';
import axios from 'axios';
import { useQuasar } from 'quasar';
import { createCharacterFormFields } from 'src/pages/configs/CreateCharacterConfig.js';
import FileUploader from 'src/components/Forms/FileUpload.vue';
import FormHeader from 'src/components/Forms/FormHeader.vue';
import FormButton from 'src/components/Forms/FormSubmitButton.vue';
import CustomNotification from 'src/components/CustomNotification.vue';

const authStore = useAuthStore();
const loggedInUser = authStore.user;
authStore.initializeSanctum();

const form = ref({
  characterName: '',
  role: '',
  primaryWeapon: '',
  secondaryWeapon: '',
  gearScore: '',
  gearPicture: null,
});

const weaponOptions = ref([]);
const characterTypeOptions = ref([]);

const formFields = computed(() => createCharacterFormFields(weaponOptions.value, characterTypeOptions.value));

const getWeaponOpts = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/weapons', { withCredentials: true });
    weaponOptions.value = response.data;
  } catch (err) {
    console.error('Error loading weapon data:', err);
  }
};

const getTypeOpts = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/character-type');
    characterTypeOptions.value = response.data;
  } catch (err) {
    console.error('Error loading character type data:', err);
  }
};

const handleFileUpload = (file) => {
  form.value.gearPicture = file;
};

const customNotify = ref(null);

const handleSubmit = async () => {
  try {
    if (!loggedInUser || !loggedInUser.id) {
      customNotify.value.showCustomNotify('You must be logged in to create a character.', 'error');
      return;
    }

    const formData = new FormData();
    formData.append('character_name', form.value.characterName);
    formData.append('role', form.value.role);
    formData.append('primary_weapon', form.value.primaryWeapon);
    formData.append('secondary_weapon', form.value.secondaryWeapon);
    formData.append('user_id', loggedInUser.id);

    if (form.value.gearScore !== '') {
      formData.append('gear_score', form.value.gearScore);
    }

    if (form.value.gearPicture) {
      formData.append('gear_picture', form.value.gearPicture);
    }

    await axios.post('http://newapp.test/api/createcharacter', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Authorization: `Bearer ${authStore.token}`,
      },
      withCredentials: true,
    });

    customNotify.value.showCustomNotify('Character registered successfully!', 'success');

    if (!loggedInUser.characterName) {
      await authStore.fetchLastActiveCharacter();
      authStore.persistAuthData();
    }

    Object.keys(form.value).forEach((key) => {
      form.value[key] = key === 'gearPicture' ? null : '';
    });
  } catch (error) {
    console.error('Error:', error);
    customNotify.value.showCustomNotify('An error occurred while submitting the form.', 'error');
  }
};

onMounted(() => {
  getWeaponOpts();
  getTypeOpts();
});
</script>

<style scoped>
.register-page {
  color: #fff;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  height: 100%;
  position: relative;
  padding: 1rem;
}

.register-card {
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