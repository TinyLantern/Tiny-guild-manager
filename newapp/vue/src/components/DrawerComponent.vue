<template>
  <q-drawer
    v-model="drawerOpen"
    show-if-above
    :mini="miniState"
    :width="200"
    :breakpoint="500"
    bordered
    :class="$q.dark.isActive ? 'bg-grey-9' : 'bg-grey-3'"
    class="flex-column"
  >
    <q-list class="no-scroll">
      <q-item clickable class="justify-center" v-ripple @click="toggleDrawer">
        <q-item-section avatar>
          <q-icon :name="miniState ? 'chevron_right' : 'chevron_left'" />
        </q-item-section>
      </q-item>
      <q-separator />

      <template v-for="(route, index) in menuRoutes" :key="index">
        <q-item clickable @click="navigateTo(route.path)" v-ripple>
          <q-item-section avatar>
            <q-icon :name="route.meta.icon" />
          </q-item-section>
          <q-item-section v-show="!miniState">
            {{ route.meta.label }}
          </q-item-section>
        </q-item>
        <q-separator />
      </template>
    </q-list>

    <div class="user-info-container flex-column">
      <q-item v-if="isLoggedIn" class="user-info">
        <q-item-section avatar class="q-pl-none">
          <q-avatar size="32px">
            <img :src="userAvatarUrl" alt="User Avatar" />
          </q-avatar>
        </q-item-section>
        <q-item-section v-show="!miniState" class="user-info-text">
          <div>{{ userName }}</div>
        </q-item-section>
      </q-item>

      <q-separator />

      <q-item v-if="isLoggedIn" clickable class="character-button" @click="openCharacterModal">
        <q-item-section avatar>
          <q-icon name="person" />
        </q-item-section>
        <q-item-section v-show="!miniState">Select character</q-item-section>
      </q-item>

      <q-separator />

      <q-item v-if="isLoggedIn" clickable class="logout-button" @click="logout">
        <q-item-section avatar>
          <q-icon name="logout" />
        </q-item-section>
        <q-item-section v-show="!miniState">Log out</q-item-section>
      </q-item>


    </div>
  </q-drawer>

  <q-dialog v-model="characterModalOpen">
    <q-card>
      <q-card-section>
        <div class="text-h6">Select Active Character</div>
      </q-card-section>

      <q-card-section>
        <q-list v-if="characters.length > 0">
          <template v-for="character in characters" :key="character.id">
            <q-item clickable @click="setActiveCharacter(character)">
              <q-item-section>
                {{ character.character_name }}
              </q-item-section>
            </q-item>
          </template>
        </q-list>
        <div v-else class="text-caption">Loading characters...</div>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn flat label="Close" @click="closeCharacterModal" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import routes from 'src/router/routes';
import { useAuthStore } from 'src/stores/auth';
import axios from 'axios';

const router = useRouter();
const authStore = useAuthStore();
authStore.rehydrateAuth();

const drawerOpen = ref(true);
const miniState = ref(false);

const characters = ref([]);
const characterModalOpen = ref(false);

const toggleDrawer = () => {
  miniState.value = !miniState.value;
};

const menuRoutes = computed(() => {
  const mainRoute = routes.find(route => route.path === '/');
  if (!mainRoute || !mainRoute.children) return [];

  const isLoggedIn = authStore.isLoggedIn;
  const hasActiveCharacter = authStore.activeCharacter !== null;
  const isInGuild = authStore.guild !== null;
  const isGuildRankValid = ['Guild Master', 'Advisor', 'Guardian'].includes(authStore.guildRank);

  const publicRoutes = ['login'];
  const protectedRoutes = ['createcharacter'];
  const onlyWithCharacterNoGuildRoutes = ['guildapplication', 'createguild'];
  const guildOnlyRoutes = ['dashboard', 'members', 'parties', 'loot', 'activities'];
  const guildRankOnlyRoutes = ['applications'];

  return mainRoute.children.filter(route => {
    if (route.meta && route.meta.icon) {
      if (publicRoutes.includes(route.path)) return true;

      if (isLoggedIn && protectedRoutes.includes(route.path)) return true;

      if (hasActiveCharacter && !isInGuild && onlyWithCharacterNoGuildRoutes.includes(route.path)) return true;

      if (isInGuild && guildOnlyRoutes.includes(route.path)) return true;

      if (isGuildRankValid && guildRankOnlyRoutes.includes(route.path)) return true;

      return false;
    }
    return false;
  });
});

const navigateTo = (link) => {
  if (!link.startsWith('/')) {
    link = `/${link}`;
  }
  router.push(link);
};

const fetchCharacters = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/mycharacters', {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
      withCredentials: true,
    });
    characters.value = response.data;
  } catch (error) {
    console.error('Failed to fetch characters:', error);
    characters.value = [];
  }
};

const openCharacterModal = async () => {
  characterModalOpen.value = true;
  await fetchCharacters();
};

const closeCharacterModal = () => {
  characterModalOpen.value = false;
};

const setActiveCharacter = async (character) => {
  try {
    const response = await axios.post('http://newapp.test/api/setactivecharacter', {
      character_id: character.id,
    }, {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
      withCredentials: true,
    });

    if (response.data && response.data.message) {
      const activeCharacter = response.data.character;
      authStore.activeCharacter = {
        id: activeCharacter.id,
        name: activeCharacter.name,
      };
      authStore.guild = activeCharacter.guild || null;
      authStore.guildRank = activeCharacter.guild_rank || null;

      authStore.persistAuthData();
      closeCharacterModal();
      window.location.reload();
    }
  } catch (error) {
    console.error('Failed to set active character:', error);
  }
};

const isLoggedIn = computed(() => authStore.isLoggedIn);
const user = computed(() => authStore.user);
const userAvatarUrl = computed(() => {
  if (!user.value || !user.value.discord_id || !user.value.discord_avatar) return '';
  return `https://cdn.discordapp.com/avatars/${user.value.discord_id}/${user.value.discord_avatar}.png`;
});
const userName = computed(() => user.value ? user.value.discord_global_name || user.value.discord_username : '');

const logout = () => {
  authStore.logout();
  router.push('/');
};
</script>

<style lang="scss">
.q-drawer {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.q-list {
  flex-grow: 1;
  overflow-y: auto;
  max-height: calc(100vh - 50px);
  padding-bottom: 80px;
}

.user-info-container {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: inherit;
}

.user-info,
.logout-button,
.character-button {
  padding: 8px 16px;
  margin: 0;
}

.user-info .q-item-section,
.logout-button .q-item-section,
.character-button .q-item-section {
  padding: 0;
  margin: 0;
}

.user-info .q-item-section.user-info-text {
  flex-grow: 1;
}

.logout-button:hover,
.character-button:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.q-avatar {
  width: 32px;
  height: 32px;
}
</style>