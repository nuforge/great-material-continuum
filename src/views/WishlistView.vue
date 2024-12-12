<template>
  <h1>Wishlist</h1>
  <div class="wishlist">
    <CardList :cards="cardStore.wants" />
    <CSVUpload table="wants" />
  </div>
</template>


<script setup lang="ts">
import CSVUpload from '@/components/CSVUpload.vue'
import CardList from '@/components/CardList.vue'
import { onMounted } from 'vue';
import { useCardStore } from '@/stores/cardstore.ts';
const cardStore = useCardStore();

onMounted(async () => {
  await cardStore.fetchWants();
});
</script>

<style>
@media (min-width: 1024px) {}
</style>
