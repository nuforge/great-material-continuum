<script setup lang="ts">
import { onMounted, ref } from 'vue';
import axios from 'axios'

const trades = ref([]);

onMounted(async () => {
  const response = await axios.get('http://localhost:8080/backend/api/endpoint.php?path=trades')
  console.log('Fetched trades:', response)
  trades.value = response.data
});
</script>

<template>
  <h1>Trades</h1>
  <div v-for="trade in trades" :key="trade" :trade="trade">
    <p>{{ trade }}</p>
  </div>
</template>

<style>
@media (min-width: 1024px) {}
</style>
