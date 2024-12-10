<template>
  <article class="card">
    <tr>
      <td>{{ card.card_name }}</td>
      <td>
        <button @click="deleteCard(card.id)">Delete</button>
      </td>
    </tr>
  </article>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'
import axios from 'axios'

defineProps({
  card: {
    type: Object,
    default: () => ({ id: 0, card_name: '' })
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
