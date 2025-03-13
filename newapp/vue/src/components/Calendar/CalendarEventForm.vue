<template>
    <q-dialog :model-value="showForm" @update:model-value="emit('update:showForm', $event)">
      <q-card style="min-width: 300px">
        <q-card-section>
          <div class="text-h6">New Activity</div>
        </q-card-section>
  
        <q-card-section>
          <q-input v-model="newEvent.name" label="Name" />
          <q-input v-model="newEvent.description" label="Description" />
          <q-input v-model="newEvent.start_time" label="Start Time" type="datetime-local" />
          <q-input v-model="newEvent.end_time" label="End Time" type="datetime-local" />
          <q-input v-model="newEvent.dkp_points" label="DKP" type="number" />
        </q-card-section>
  
        <q-card-actions align="right">
          <q-btn flat label="Cancel" @click="emit('update:showForm', false)" />
          <q-btn flat label="Save" color="primary" @click="saveEvent" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const props = defineProps({
    showForm: Boolean,
  });
  
  const emit = defineEmits(['update:showForm', 'save']);
  
  const newEvent = ref({
    name: "",
    description: "",
    start_time: "",
    end_time: "",
    dkp_points: 0,
  });
  
  const saveEvent = () => {
    emit('save', newEvent.value);
    emit('update:showForm', false);
        newEvent.value = {
        name: "",
        description: "",
        start_time: "",
        end_time: "",
        dkp_points: 0,
    };
  };
  </script>