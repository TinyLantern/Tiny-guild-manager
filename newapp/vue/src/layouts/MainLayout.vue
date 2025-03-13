<template>
  <q-layout>
    <DrawerComponent/>
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import DrawerComponent from 'src/components/DrawerComponent.vue';
import { ref } from 'vue';

// Dialog visibility state
const showDialog = ref(false);

</script>

<style scoped>
/* Add any styles here if needed */
</style>
