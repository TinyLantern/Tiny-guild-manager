<template>
  <div class="tile-section">
    <div class="tile q-pa-sm" v-for="(tile, index) in tiles" :key="index">
      <h3 class="tile-title">{{ tile.title }}</h3>
      <p class="tile-value">{{ tile.value }}</p>
    </div>
  </div>
</template>

<script setup>

const props = defineProps({
  tiles: Array,
});
</script>

<style lang="scss">
.tile-section {
  display: flex;
  gap: 2rem;
}

.tile {
  background-color: var(--custom-gray);
  color: #fff;
  border-radius: 8px;
  text-align: center;
  flex: 1;
  padding: 1rem;
  min-width: 200px;
  height: 120px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.tile:first-child {
  background-color: var(--custom-purple);
}

.tile-title {
  margin: 0;
  padding-bottom: 1rem;
  font-size: 1.25rem;
  font-weight: bold;
  white-space: nowrap;
}

.tile-value {
  font-size: 1.5rem;
  font-weight: normal;
  margin: 0;
  padding-bottom: 0.5rem;
}
</style>