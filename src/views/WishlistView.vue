<template>
  <div class="d-flex justify-space-between align-center">
    <h1>Wishlist</h1>
    <CSVUpload table="wishlist" @upload-success="uploadSuccess" />
  </div>
  <div class="wishlist">
    <CardList :cards="cardStore.wishlist" table="wishlist" @delete-item="deleteItem" />
  </div>
</template>

<script setup lang="ts">
import CSVUpload from '@/components/CSVUpload.vue'
import CardList from '@/components/CardList.vue'
import { onMounted } from 'vue';
import { useCardStore } from '@/stores/cardstore.ts';
const cardStore = useCardStore();

onMounted(async () => {
  await loadWishlist();
});

const uploadSuccess = async () => {
  await loadWishlist();
};

const loadWishlist = async () => {
  await cardStore.fetchWishlist();
};
const deleteItem = (itemId: number, tableName: string) => {
  cardStore.deleteCard(itemId, tableName);
};
</script>

<style>
@media (min-width: 1024px) {}
</style>
