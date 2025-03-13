<template>
    <q-input
      v-model="localModel"
      :label="label"
      filled
      dense
      :rules="rules"

    />
  </template>
  
<script setup>
import { computed } from "vue";

const props = defineProps({
model: {
    type: String,
    required: true,
},
label: {
    type: String,
    required: true,
},
rules: {
    type: Array,
    default: () => [],
},
});

const emit = defineEmits(["update:model"]);

const localModel = computed({
    get: () => props.model,
    set: (value) => emit("update:model", value),
});
</script>

<style lang="scss">
.q-field{
    padding-bottom: 27px;
}
.q-field__inner{
    background-color: #4d4d4d;
    border-radius: 4px;
}
.q-field__native {
  font-weight: bold;
}
</style>