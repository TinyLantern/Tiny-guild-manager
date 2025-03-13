<template>
  <q-select
    v-model="localModel"
    :label="label"
    :options="options"
    :option-label="optionLabel"
    :option-value="optionValue"
    filled
    dense
    emit-value
    map-options
    :rules="rules"
  />
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  model: {
    type: [String, Number],
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  options: {
    type: Array,
    required: true,
  },
  optionLabel: {
    type: String,
    default: "label",
  },
  optionValue: {
    type: String,
    default: "value",
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