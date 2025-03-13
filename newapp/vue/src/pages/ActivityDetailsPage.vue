<template>
  <div class="q-pa-md">
    <q-card v-if="activity" class="activity-card no-shadow">
      <CustomNotify ref="customNotify" />
      <ActivityDetails :activity="activity" @delete-activity="deleteActivity" />

      <q-separator />
      <UnrespondedMembers :unrespondedMembers="unrespondedMembers" />
      <q-separator />

      <ParticipationGrid
        :participatingMembers="participatingMembers"
        :notParticipatingMembers="notParticipatingMembers"
        :tentativeMembers="tentativeMembers"
      />
      <q-separator />

      <ParticipationButtons @set-participation-status="setParticipationStatus" />
      <q-separator />

      <q-card-actions align="between" class="discord-back">
        <BackButton @go-back="goBack" />
        <PostDiscordButton @post-in-discord="postInDiscord" />
      </q-card-actions>
    </q-card>

    <q-spinner color="primary" size="3em" v-else-if="loading" />
    <q-banner v-else class="bg-red text-white">Event not found.</q-banner>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";
import { useAuthStore } from "src/stores/auth";
import { useQuasar } from "quasar";
import ActivityDetails from "src/components/Activity/ActivityDetails.vue";
import ParticipationGrid from "src/components/Activity/ParticipationGrid.vue";
import UnrespondedMembers from "src/components/Activity/UnrespondedMembers.vue";
import ParticipationButtons from "src/components/Activity/ParticipationButtons.vue";
import PostDiscordButton from "src/components/Activity/PostDiscordButton.vue";
import BackButton from "src/components/Activity/BackButton.vue";
import CustomNotify from "src/components/CustomNotification.vue";

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const $q = useQuasar();
const activity = ref(null);
const loading = ref(true);
const guildMembers = ref([]);
const unrespondedMembers = ref([]);
const customNotify = ref(null);

const participatingMembers = computed(() =>
  guildMembers.value.filter((member) => member.status === "participating")
);
const notParticipatingMembers = computed(() =>
  guildMembers.value.filter((member) => member.status === "not_participating")
);
const tentativeMembers = computed(() =>
  guildMembers.value.filter((member) => member.status === "tentative")
);

const fetchActivityDetails = async () => {
  try {
    const response = await axios.get(`http://newapp.test/api/activities/${route.params.id}`, {
      headers: { Authorization: `Bearer ${authStore.token}` },
      withCredentials: true,
    });
    activity.value = response.data;
  } catch (error) {
    console.error("Error fetching activity:", error);
    customNotify.value.showCustomNotify("Failed to fetch activity details", "error");
  } finally {
    loading.value = false;
  }
};

const deleteActivity = async () => {
  $q.dialog({
    title: "Confirm Delete",
    message: "Are you sure you want to delete this activity? This action cannot be undone.",
    cancel: true,
    class: "no-shadow",
    ok: {
      label: "Yes, Delete!",
      color: "red",
      flat: true,
    },
    cancel: {
      color: "white",
      flat: true,
    },
  }).onOk(async () => {
    try {
      await axios.delete(`http://newapp.test/api/activities/${route.params.id}`, {
        headers: { Authorization: `Bearer ${authStore.token}` },
        withCredentials: true,
      });
      customNotify.value.showCustomNotify("Activity deleted successfully!", "success");
      setTimeout(() => {
        router.push("/activities");
      }, 2000);
    } catch (error) {
      console.error("Error deleting activity:", error);
      customNotify.value.showCustomNotify("Failed to delete activity.", "error");
    }
  });
};

const fetchGuildMembers = async () => {
  try {
    const response = await axios.get(
      `http://newapp.test/api/activities/${route.params.id}/participants`,
      {
        headers: { Authorization: `Bearer ${authStore.token}` },
        withCredentials: true,
      }
    );
    guildMembers.value = response.data.map((participant) => ({
      id: participant.id,
      name: participant.character_name,
      status: getStatusLabel(participant.activity_participation_status_id),
      character_role: participant.character_role,
    }));
  } catch (error) {
    console.error("Error fetching guild members:", error);
    customNotify.value.showCustomNotify("Failed to fetch guild members", "error");
  }
};

const fetchUnrespondedMembers = async () => {
  try {
    const response = await axios.get(
      `http://newapp.test/api/activities/${route.params.id}/unresponded`,
      {
        headers: { Authorization: `Bearer ${authStore.token}` },
        withCredentials: true,
      }
    );
    unrespondedMembers.value = response.data.unresponded.map((member) => ({
      id: member.id,
      name: member.character_name,
      character_role: member.character_role,
    }));
  } catch (error) {
    console.error("Error fetching unresponded members:", error);
    customNotify.value.showCustomNotify("Failed to fetch unresponded members", "error");
  }
};

const getStatusLabel = (status_id) => {
  switch (status_id) {
    case 1:
      return "participating";
    case 2:
      return "not_participating";
    case 3:
      return "tentative";
    default:
      return null;
  }
};

const setParticipationStatus = async (status_id) => {
  try {
    await axios.post(
      `http://newapp.test/api/activity-participants`,
      {
        activity_id: activity.value.id,
        character_id: authStore.activeCharacter.id,
        activity_participation_status_id: status_id,
      },
      {
        headers: { Authorization: `Bearer ${authStore.token}` },
        withCredentials: true,
      }
    );
    unrespondedMembers.value = unrespondedMembers.value.filter(
      (member) => member.id !== authStore.activeCharacter.id
    );
    const memberIndex = guildMembers.value.findIndex(
      (member) => member.id === authStore.activeCharacter.id
    );
    if (memberIndex !== -1) {
      guildMembers.value[memberIndex].status = getStatusLabel(status_id);
    } else {
      guildMembers.value.push({
        id: authStore.activeCharacter.id,
        name: authStore.activeCharacter.name,
        status: getStatusLabel(status_id),
      });
    }
    guildMembers.value = guildMembers.value.filter((member) => member.status !== null);
    customNotify.value.showCustomNotify("Participation status updated successfully!", "success");
  } catch (error) {
    console.error("Error updating participation status:", error);
    customNotify.value.showCustomNotify("Failed to update participation status.", "error");
  }
};

const postInDiscord = async () => {
  try {
    await axios.post(
      `http://newapp.test/api/activities/${route.params.id}/post-discord`,
      {},
      {
        headers: { Authorization: `Bearer ${authStore.token}` },
        withCredentials: true,
      }
    );
    customNotify.value.showCustomNotify("Posted in Discord successfully!", "success");
  } catch (error) {
    console.error("Error posting to Discord:", error);
    customNotify.value.showCustomNotify("Failed to post in Discord.", "error");
  }
};

const goBack = () => {
  router.push("/activities");
};

onMounted(async () => {
  await fetchActivityDetails();
  await fetchGuildMembers();
  await fetchUnrespondedMembers();
});
</script>

<style scoped>
.activity-card {
  max-width: 1000px;
  margin: auto;
  background-color: var(--custom-dark);
}
.discord-back{
  padding: 1rem;
}
</style>