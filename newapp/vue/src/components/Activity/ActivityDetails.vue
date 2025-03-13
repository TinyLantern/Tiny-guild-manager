<template>
  <q-card-section>
    <div class="row justify-between items-center q-mb-md">
      <div>
        <div class="text-h5">{{ activity.name }}</div>
        <div class="text-caption text-white">{{ formattedDate(activity.start_time) }}</div>
      </div>
      <q-btn
        label="Delete Activity"
        color="negative"
        @click="emit('delete-activity')"
      />
    </div>

    <q-separator />

    <div class="details-grid q-mt-md">
      <div class="details-item">
        <div><strong>Start Time:</strong> {{ formatTime(activity.start_time) }}</div>
      </div>
      <div class="details-item description">
        <div><strong>Description:</strong> {{ activity.description }}</div>
      </div>
      <div class="details-item dkp-points">
        <div><strong>DKP Points:</strong> {{ activity.dkp }}</div>
      </div>

      <div class="details-item">
        <div><strong>End Time:</strong> {{ formatTime(activity.end_time) }}</div>
      </div>
      <div class="details-item description">
      </div>
      <div class="details-item dkp-points">
      </div>
    </div>
  </q-card-section>
</template>

<script setup>
const props = defineProps({
  activity: Object,
});

const emit = defineEmits(['delete-activity']);

const formatTime = (dateTime) => {
  return new Date(dateTime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const formattedDate = (dateTime) => {
  return new Date(dateTime).toLocaleDateString();
};
</script>

<style scoped>
.details-grid {
  display: grid;
  grid-template-columns: auto 1fr auto; 
  gap: 8px 16px; 
  align-items: start;
}

.details-item {
  display: flex;
  align-items: center;
}

.start-time {
  grid-column: 1;
  grid-row: 1;
}

.end-time {
  grid-column: 1;
  grid-row: 2;
  margin-top: 4px;
}

.description {
  grid-column: 2;
  grid-row: 1 / span 2;
  white-space: normal;
  word-wrap: break-word;
  max-width: 100%;
  margin-left: 8px; 
}

.dkp-points {
  grid-column: 3;
  grid-row: 1 / span 2;
  text-align: right;
  margin-left: 16px;
}

</style>