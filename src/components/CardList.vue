<template>
  <div class="table-container">
    <table class="card-table">
      <thead>
        <tr>
          <th>Card</th>
          <th>User</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <CardItem v-for="card in cardStore.haves" :key="card.id" :card="card" />
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useCardStore } from '../stores/cardstore.ts';
import CardItem from './CardItem.vue'
const cardStore = useCardStore();

onMounted(() => {
  cardStore.fetchHaves();
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
