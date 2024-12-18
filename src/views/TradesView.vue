<template>
  <h1>Trades</h1>

  <v-row dense>
    <v-col cols="12" md="6" v-for="(user, index) in users" :key="index" :user="user">
      <v-card :title="user.name" rounded :elevation="4">
        <v-card-text>
          <v-chip-group multiple column v-model="selected">
            <v-chip v-for="(card) in user.inventory" :key="card.card_id" prepend-icon="mdi-cards" color="success"
              :text="card.card_name" :value="card.card_name" variant="text" label></v-chip>
            <v-divider></v-divider>
            <v-chip v-for="(card) in user.wishlist" :key="card.card_id" prepend-icon="mdi-cards-outline" color="info"
              :text="card.card_name" :value="card.card_name" variant="plain" label></v-chip>
          </v-chip-group>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>

</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
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
const selected = ref<string[]>([]);

onMounted(async () => {
  const response = await axios.get('http://localhost:8080/backend/api/endpoint.php?path=users')
  users.value = response.data

  console.log("users", users.value)
});

watch(selected, (newValue) => {
  console.log("Selected chip value:", newValue);
});

</script>
