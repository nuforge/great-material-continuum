<template>
  <h1>Trades</h1>
  <v-card v-for="(user, index) in users" :key="index" :user="user" class="">
    <v-card-title>{{ user.name }}</v-card-title>
    <v-card-text>
      <h3>Inventory</h3>
      <v-chip-group>
        <v-chip v-for="(card, i) in user.inventory" :key="i" prepend-icon="mdi-cards-outline">{{
          card.card_name
        }}</v-chip></v-chip-group>
      <h3>Wishlist</h3>

      <v-chip-group>
        <v-chip v-for="(card, i) in user.wishlist" :key="i" prepend-icon="mdi-cards-outline" color="blue">{{
          card.card_name
        }}</v-chip>
      </v-chip-group>
    </v-card-text>
  </v-card>

</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import axios from 'axios'

interface Card {
  card_id: number;
  card_name: string;
}

interface User {
  id: number;
  name: string;
  inventory: Card[];
  wishlist: Card[];
}

const users = ref<User[]>([]);

onMounted(async () => {
  const response = await axios.get('http://localhost:8080/backend/api/endpoint.php?path=users')
  users.value = response.data

  console.log("users", users.value)
});
</script>
