<template>
  <tr class="cardrow">
    <td class="name">{{ card.card_name }}</td>
    <td class="userid">{{ card.user_id }}</td>
    <td class="actions">
      <button @click="cardStore.deleteCard(card.id)">Delete</button>
    </td>
  </tr>
</template>

<script setup lang="ts">
import { defineProps } from 'vue'
import { useCardStore } from '../stores/cardstore.ts';
const cardStore = useCardStore();

defineProps({
  card: {
    type: Object,
    default: () => (null)
  },
})
</script>

<style type="scss">
.cardrow:nth-child(odd) {
  background-color: var(--vt-c-black-soft);
}
</style>
