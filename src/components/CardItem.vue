<template>
  <tr class="cardrow">
    <td class="name"><v-icon color="blue-grey-darken-2" icon="mdi-cards-outline" size="small"></v-icon> {{
      card.card_id }}. {{
        card.card_name }} </td>
    <td class="userid"><v-icon color="blue-grey-darken-2" icon="mdi-account-circle-outline" size="small"></v-icon> {{
      card.user_id }}. {{
        card.user_name }}
    </td>
    <td class="actions">
      <v-icon color="blue-grey-darken-2" icon="mdi-delete-outline"
        @click="cardStore.deleteCard(card.id, table)"></v-icon>
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
  table: {
    type: String,
    default: () => ('haves')
  }
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
