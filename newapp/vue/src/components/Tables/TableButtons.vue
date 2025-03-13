<template>
  <div class="action-buttons">
    <q-btn
      v-if="showAccept"
      label="Accept"
      class="accept-button"
      @click="onAccept"
    />
    <q-btn
      v-if="showDecline"
      label="Decline"
      class="decline-button"
      @click="onDecline"
    />
  </div>
</template>

<script setup>
defineProps({
  showAccept: {
    type: Boolean,
    default: true,
  },
  showDecline: {
    type: Boolean,
    default: true,
  },
  onAccept: {
    type: Function,
    required: true,
  },
  onDecline: {
    type: Function,
    required: true,
  },
});
</script>

<style lang="scss">
.action-buttons {
  display: flex;
  gap: 0.5rem;
}

.accept-button {
  background-color: #4caf50;
  color: white;
  padding: 4px 4px;
  border-radius: 4px;
  font-weight: bold;
  text-transform: uppercase;
  transition: background-color 0.3s ease;
}

.accept-button:hover {
  background-color: #26db2f;
  color: black;
}

.decline-button {
  background-color: #c44138;
  color: white;
  padding: 4px 4px;
  border-radius: 4px;
  font-weight: bold;
  text-transform: uppercase;
  transition: background-color 0.3s ease;
}

.decline-button:hover {
  background-color: #ff0000;
  color: black;
}
</style>