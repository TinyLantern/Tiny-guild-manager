<template>
  <q-table
    :rows="rows"
    :columns="columns"
    row-key="id"
    :pagination="pagination"
    :filter="filter"
    :rows-per-page-options="rowsPerPageOptions"
    bordered
    class="q-mt-md no-shadow"
  >
    <template v-slot:body="props">
      <q-tr :props="props" @click="$router.push(`/member/${props.row.id}`)" class="clickable-row">
        <q-td v-for="col in props.cols" :key="col.name" :props="props" class="text-center">
          <template v-if="col.name === 'weapons'">
            <div class="weapon-icons">
              <img
                v-for="weapon in [props.row.primaryWeapon, props.row.secondaryWeapon]"
                :key="weapon"
                :src="weaponIcons[weapon] || '/src/assets/weapons/default.webp'"
                :alt="weapon"
                class="weapon-icon"
              />
            </div>
          </template>
          <template v-else>
            {{ col.value }}
          </template>
        </q-td>
      </q-tr>
    </template>
  </q-table>
</template>

<script setup>
import { weaponIcons } from 'src/pages/configs/MembersConfig';

defineProps({
  rows: {
    type: Array,
    required: true,
  },
  columns: {
    type: Array,
    required: true,
  },
  pagination: {
    type: Object,
    default: () => ({ rowsPerPage: 10 }),
  },
  filter: {
    type: String,
    default: '',
  },
  rowsPerPageOptions: {
    type: Array,
    default: () => [10, 20, 30, 40, 50, 60, 70],
  },
});
</script>

<style lang="scss">
.weapon-icons {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
}

.weapon-icon {
  width: 24px;
  height: 24px;
  object-fit: contain;
}

.weapons-cell {
  text-align: center;
}

.q-table {
  width: 100%;
  border-radius: 4px;
}

.q-table .q-tr {
  background-color: #4d4d4d;
}

.q-th,
.q-td {
  width: auto;
  text-align: center;
  padding: 0.75rem;
  font-weight: bold;
}

thead {
  background-color: var(--custom-purple);
}

.q-table__bottom{
  background-color: var(--custom-purple);
  font-weight: bold;
}

.q-table th{
  font-weight: bold !important;
  font-size: 14px;
}

.q-th {
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  white-space: nowrap; 
  gap: 0.3rem;
}

.q-table__sort-icon {
  margin-left: 0.3rem;
  color: #FFFF00;
}


.q-tr:nth-child(odd) {
  background-color: var(--custom-dark);
}

.text-grey-8{
  color: #fff !important;
  font-weight: bold;
}
</style>