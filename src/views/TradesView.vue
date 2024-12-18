<template>
  <h1>Trades</h1>

  <v-row dense>
    <v-col cols="12" md="6" v-for="(user, index) in users" :key="index" :user="user">
      <v-card :title="user.name" rounded>
        <v-card-title></v-card-title>
        <v-card-text>
          <h3>Inventory</h3>
          <v-chip-group selected-class="text-primary" multiple column>
            <v-chip v-for="(card, i) in user.inventory" :key="i" prepend-icon="mdi-cards-outline" color="success"
              :text="card.card_name"></v-chip>
          </v-chip-group>
          <h3>Wishlist</h3>

          <v-chip-group selected-class="text-primary" multiple column>
            <v-chip v-for="(card, i) in user.wishlist" :key="i" prepend-icon="mdi-cards-outline" color="info"
              :text="card.card_name"></v-chip>
          </v-chip-group>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>

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
