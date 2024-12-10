<template>
  <section class="card-list">
    <table class="wrapper">
      <CardItem v-for="card in cards" :key="card.id" :card="card" @fetchCards="fetchCards" />
    </table>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import CardItem from './CardItem.vue'
import axios from 'axios';

const cards = ref<{ id: number, card_name: string }[]>([]);

const fetchCards = async () => {
  try {
    const response = await axios.get('http://localhost:8080/backend/api/endpoint.php?path=cards');

    // Assign the first cards from the response to message
    if (response.data.length > 0) {
      cards.value = response.data; // Assuming the response is an array of cards
    } else {
      cards.value = [];
    }
  } catch (error) {
    console.error('Error fetching the message:', error);
  }
};
onMounted(fetchCards);
</script>
