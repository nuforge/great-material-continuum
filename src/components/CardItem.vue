<template>
  <tr class="cardrow">
    <td class="name">{{ card.card_name }}</td>
    <td class="userid">{{ card.user_id }}</td>
    <td class="actions">
      <v-icon icon="mdi-delete" @click="cardStore.deleteCard(card.id)"></v-icon>
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

<style scoped>
.delete-icon {
  cursor: pointer;
}

.cardrow:nth-child(odd) {
  background-color: var(--vt-c-black-soft);
}

.cardrow:hover {
  background-color: hsla(160, 100%, 37%, 0.2);
}

td.name {
  font-weight: bold;
}
</style>
