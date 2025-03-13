<template>
  <div class="page-container">
    <PageHeader title="Applications" />

    <div class="table-container">
      <DynamicTable
        :rows="applications"
        :columns="applicationsColumns"
        :pagination="applicationsPagination"
      >
        <template #actions="{ row }">
          <ActionButtons
            :show-accept="row.status === 'Pending'"
            :show-decline="row.status === 'Pending'"
            :on-accept="() => handleAccept(row)"
            :on-decline="() => handleDecline(row)"
          />
        </template>
      </DynamicTable>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from 'src/stores/auth';
import { applicationsColumns, applicationsPagination } from 'src/pages/configs/GuildApplicationsConfig';
import DynamicTable from 'src/components/Tables/ApplicationsTable.vue';
import PageHeader from 'src/components/PageHeader.vue';
import ActionButtons from 'src/components/Tables/TableButtons.vue';

const authStore = useAuthStore();
const applications = ref([]);

const fetchApplications = async () => {
  try {
    const response = await axios.get('http://newapp.test/api/guild-applications', {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
      withCredentials: true,
    });

    applications.value = response.data.sort((a, b) => {
      return new Date(b.appliedOn) - new Date(a.appliedOn);
    });
  } catch (error) {
    console.error('Error fetching applications:', error);
  }
};

const handleAccept = async (row) => {
  try {
    await axios.post(
      `http://newapp.test/api/accept-application/${row.application_id}`,
      {},
      {
        headers: {
          Authorization: `Bearer ${authStore.token}`,
        },
        withCredentials: true,
      }
    );
    fetchApplications();
  } catch (error) {
    console.error('Error accepting application:', error);
  }
};

const handleDecline = async (row) => {
  try {
    await axios.post(
      `http://newapp.test/api/decline-application/${row.application_id}`,
      {},
      {
        headers: {
          Authorization: `Bearer ${authStore.token}`,
        },
        withCredentials: true,
      }
    );
    fetchApplications();
  } catch (error) {
    console.error('Error declining application:', error);
  }
};

onMounted(() => {
  fetchApplications();
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
  max-width: 950px;
  width: 100%;
  margin: 0 auto;
}
</style>