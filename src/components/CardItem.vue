<template>
  <tr class="card">
    <td>{{ card.card_name }}</td>
    <td>user{{ card.user_id }}</td>
    <td>
      <button @click="deleteCard(card.id)">Delete</button>
    </td>
  </tr>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'
import axios from 'axios'

defineProps({
  card: {
    type: Object,
    default: () => (null)
  },
})

const emit = defineEmits(['fetchCards'])

const deleteCard = async (card_id: number) => {
  try {
    await axios.delete(`http://localhost:8080/backend/api/endpoint.php?path=cards`, {
      data: { id: card_id }
    });
    emit('fetchCards');
  } catch (error) {
    console.error('Error deleting the card:', error);
  }
};

</script>
