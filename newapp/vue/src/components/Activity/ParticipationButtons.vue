<template>
    <q-card-section class="q-pt-none q-mt-md">
      <div class="row q-col-gutter-md">
        <div class="col">
          <q-btn
            label="Participating"
            color="positive"
            @click="emit('set-participation-status', 1)"
            class="full-width"
          />
        </div>
        <div class="col">
          <q-btn
            label="Not Participating"
            color="negative"
            @click="emit('set-participation-status', 2)"
            class="full-width"
          />
        </div>
        <div class="col">
          <q-btn
            label="Tentative"
            color="warning"
            @click="emit('set-participation-status', 3)"
            class="full-width"
          />
        </div>
      </div>
    </q-card-section>
  </template>
  
  <script setup>
  const emit = defineEmits(['set-participation-status']);
  </script>