<template>
  <h1>Trades</h1>
  <div class="table-container" v-for="trade in trades" :key="trade.user_id" :trade="trade">
    <v-table class="mb-4" density="compact">
      <thead>
        <tr>
          <th>User</th>
          <th>Have</th>
          <th>Want</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{ trade['user_name'] }}</td>
          <td v-for="have in trade['haves']" :key="have.card_name" :card="have">{{ have.card_name }}</td>
          <td v-for="want in trade['wants']" :key="want.card_name" :card="want">{{ want.card_name }}</td>
        </tr>
      </tbody>
    </v-table>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import axios from 'axios'

interface Trade {
  user_name: string;
  user_id: number;
  haves: { card_name: string }[];
  wants: { card_name: string }[];
}

const trades = ref<Trade[]>([]);

onMounted(async () => {
  const response = await axios.get('http://localhost:8080/backend/api/endpoint.php?path=trades')
  console.log('Fetched trades:', response)
  trades.value = response.data
});
</script>

<style>
@media (min-width: 1024px) {}
</style>
