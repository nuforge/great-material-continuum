<template>
  <div class="table-container">
    <v-table class="card-table rounded">
      <thead>
        <tr>
          <th>id</th>
          <th>Card</th>
          <th>User</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <CardItem v-for="card in cards" :key="card.id" :card="card" :table="table" @delete-item="deleteItem" />
      </tbody>
    </v-table>
  </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';
import CardItem from './CardItem.vue'

defineProps({
  cards: {
    type: Object,
    default: () => ({})
  },
  table: {
    type: String,
    default: () => ('inventory')
  }
})


const emit = defineEmits(['delete-item']);

const deleteItem = (itemId: number, tableName: string) => {
  console.log('delete-item', itemId, tableName);
  emit('delete-item', itemId, tableName);
};

</script>

<style>
.card-table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;

  th,
  td {
    text-align: left;
    padding: 10px;
  }

  th:last-child,
  td:last-child {
    text-align: right;
  }

}
</style>
