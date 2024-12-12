<template>
  <div class="table-container">
    <v-table class="card-table">
      <thead>
        <tr>
          <th>Card</th>
          <th>User</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <CardItem v-for="card in cardStore.wants" :key="card.id" :card="card" table="wants" />
      </tbody>
    </v-table>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useCardStore } from '@/stores/cardstore.ts';
import CardItem from './CardItem.vue'
const cardStore = useCardStore();

onMounted(async () => {
  await cardStore.fetchWants();
});
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
