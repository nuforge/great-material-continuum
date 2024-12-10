<script setup lang="ts">
import CSVUpload from '../components/CSVUpload.vue'
import CardList from '../components/CardList.vue'
</script>

<template>
  <div class="inventory">
    <CSVUpload />
    <CardList />
  </div>
</template>

<style>
@media (min-width: 1024px) {}
</style>
