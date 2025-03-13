<template>
    <q-card class="q-ma-md">
      <q-card-section>
        <div class="row items-center">
  
          <div class="col">
            <div class="text-h6">{{ name }}</div>
            <div class="text-subtitle1 text-grey">{{ data }}</div>
          </div>
        </div>
      </q-card-section>
    </q-card>
  </template>
  
  <script setup>

  const props = defineProps({
    name: { type: String, required: true },
    icon: { type: String, required: true },
    data: { type: Number, required: true },
    link: { type: String, required: true }
  })
  </script>
