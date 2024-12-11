<script setup lang="ts">
import CSVUpload from '../components/CSVUpload.vue'
import CardWishlist from '../components/CardWishlist.vue'
</script>

<template>
  <h1>Wishlist</h1>
  <div class="inventory">
    <CSVUpload table="wants" />
    <CardWishlist />
  </div>
</template>

<style>
@media (min-width: 1024px) {}
</style>
