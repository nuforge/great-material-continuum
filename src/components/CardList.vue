<template>
  <div class="table-container">
    <v-table class="card-table rounded">
      <thead>
        <tr>
          <th>Card</th>
          <th>User</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <CardItem v-for="card in cards" :key="card.card_id" :card="card" table="wants" />
      </tbody>
    </v-table>
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import CardItem from './CardItem.vue'

defineProps({
  cards: {
    type: Object,
    default: () => ({})
  }
})

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
