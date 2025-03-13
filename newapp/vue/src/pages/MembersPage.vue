<template>
  <div class="page-container">
    <PageHeader title="Members" />

    <div class="table-container">
      <MembersTable
        :rows="members"
        :columns="membersColumns"
        :pagination="membersPagination"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from 'src/stores/auth';
import { membersColumns, membersPagination } from 'src/pages/configs/MembersConfig';
import MembersTable from 'src/components/Tables/MembersTable.vue';
import PageHeader from 'src/components/PageHeader.vue';

const authStore = useAuthStore();
const members = ref([]);

const fetchMembers = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/guildcharacters', {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
      withCredentials: true,
    });
    members.value = response.data;
  } catch (error) {
    console.error('Error fetching members:', error);
  }
};

onMounted(() => {
  fetchMembers();
});
</script>

<style scoped>
.page-container {
  color: white;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.table-container {
  max-width: 750px;
  width: 100%;
  margin: 0 auto;
}
</style>