<template>
  <q-page class="flex flex-center">
    <div>
      <p v-if="authStore.isLoggedIn">
        Welcome, {{ authStore.user?.discord_global_name || 'User' }}!
      </p>
      <p v-else>
        Please log in through Discord.
      </p>
      <button @click="authStore.logout" v-if="authStore.isLoggedIn">Logout</button>
    </div>
  </q-page>
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from 'src/stores/auth';

const authStore = useAuthStore();

function handleDiscordRedirect() {
  const urlParams = new URLSearchParams(window.location.search);
  const code = urlParams.get('code');

  if (code) {
    console.log('Received code:', code);
    authStore.loginWithDiscord(code);
  } else {
    console.error('No code found in URL parameters');
  }
}

onMounted(() => {
  handleDiscordRedirect();
});
</script>
