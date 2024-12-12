<template>
  <h1>Inventory</h1>
  <div class="inventory">
    <CardList :cards="cardStore.inventory" table="inventory" @delete-item="deleteItem" />
    <CSVUpload table="inventory" />
  </div>
</template>

<script setup lang="ts">
import CSVUpload from '@/components/CSVUpload.vue'
import CardList from '@/components/CardList.vue'
import { onMounted } from 'vue';
import { useCardStore } from '@/stores/cardstore.ts';
const cardStore = useCardStore();

onMounted(async () => {
  await cardStore.fetchInventory();
});

const deleteItem = (itemId: number, tableName: string) => {
  cardStore.deleteCard(itemId, tableName);
};
</script>

<style>
@media (min-width: 1024px) {}
</style>
