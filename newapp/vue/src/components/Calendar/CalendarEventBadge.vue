<template>
    <q-badge
      class="event-badge cursor-pointer"
      :class="event.color"
      :style="badgeStyles"
      @click="emit('click', event.id)"
    >
      <q-icon v-if="event.icon" :name="event.icon" class="q-mr-xs"></q-icon>
      <span class="ellipsis">{{ event.title }}</span>
    </q-badge>
  </template>
  
  <script setup>
import {computed } from 'vue';

  const props = defineProps({
    event: Object,
    timeStartPos: Function,
    timeDurationHeight: Function,
  });
  
  const emit = defineEmits(['click']);
  
  const badgeStyles = computed(() => {
    if (!props.timeStartPos || !props.timeDurationHeight) return {};
  
    const padding = 2;
    const spacing = 1;
    const availableWidth = 100 - 2 * padding;
  
    let width, leftOffset;
  
    if (!props.event.hasOverlap) {

      width = availableWidth;
      leftOffset = padding;
    } else {

      width = (availableWidth / 2) - spacing;
  
      if (props.event.column === 0) {

        leftOffset = padding;
      } else {

        leftOffset = padding + (availableWidth / 2) + spacing;
      }
    }
  
    return {
      top: props.timeStartPos(props.event.time) + "px",
      height: props.timeDurationHeight(props.event.duration) + "px",
      width: `${width}%`,
      left: `${leftOffset}%`,
      position: "absolute",
    };
  });
  </script>
  
  <style scoped>
  .event-badge {
    position: absolute;
    left: 10px;
    right: 10px;
    color: white;
    padding: 4px;
    text-align: center;
    border-radius: 4px;
  }
  </style>