<template>
  <h1>Inventory</h1>
  <div class="inventory">
    <CardList :cards="cardStore.haves" />
    <CSVUpload table="haves" />
  </div>
</template>


<script setup lang="ts">
import CSVUpload from '@/components/CSVUpload.vue'
import CardList from '@/components/CardList.vue'
import { onMounted } from 'vue';
import { useCardStore } from '@/stores/cardstore.ts';
const cardStore = useCardStore();

onMounted(async () => {
  await cardStore.fetchHaves();
});
</script>

<style>
@media (min-width: 1024px) {}
</style>
