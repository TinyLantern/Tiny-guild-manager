<template>
    <div class="q-pa-md table-wrap">
      <q-table
        :title="title"
        :rows="data"
        :columns="formattedColumns"
        row-key="id"
        :pagination="pagination"
        :dense="dense"
        @row-click="onRowClick"
        class="no-shadow"
      />
    </div>

  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import { useRouter } from 'vue-router';

  const router = useRouter();
  
  const props = defineProps({
    title: String,
    columns: Array,
    data: Array 
  });
  
  const dense = ref(true);
  
  const pagination = ref({
    rowsPerPage: 10,
  });
  

  const formattedColumns = computed(() => {
    return props.columns.map(col => ({
      name: col,
      label: col,
      field: col,
      align: 'left',
    }));
  });

  const onRowClick = (event, row) => {
  if (row.id) {
    router.push(`/activity/${row.id}`);
  }
  };
  </script>
  
  <style lang="scss">
  .table-wrap {
    background-color: var(--custom-dark);
    border-radius: 8px;
    padding: 1rem;
    margin: 1rem;
  }

  .q-table thead {
    background-color: var(--custom-purple);
  }
  .q-table__bottom{
    background-color: var(--custom-purple);
  }
  </style>
  