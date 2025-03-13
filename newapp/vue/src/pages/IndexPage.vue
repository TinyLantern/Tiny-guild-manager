<template>
  <div class="q-pa-md flex flex-center">

    <q-btn color="primary" label="Login with Discord" @click="discordRedirect" />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

function discordRedirect() {
  const discordAuthUrl = 'https://discordapp.com/api/oauth2/authorize';
  const clientId = '1311747628689657978';
  const redirectUri = encodeURIComponent('http://localhost:8101/login');
  const scope = 'identify guilds';
  const responseType = 'code';
  window.location.href = `${discordAuthUrl}?client_id=${clientId}&redirect_uri=${redirectUri}&response_type=${responseType}&scope=${scope}`;
}

</script>

<style scoped>

</style>