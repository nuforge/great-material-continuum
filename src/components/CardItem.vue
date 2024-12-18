<template>
  <tr class="cardrow">
    <td class="name"><v-chip :text="card.card_name" prepend-icon="mdi-cards" label variant="text"></v-chip></td>
    <td class="userid"><v-chip :text="card.user_name" prepend-icon="mdi-account-circle-outline" label
        variant="text"></v-chip>
    </td>
    <td class="actions">
      <v-icon color="blue-grey-darken-2" icon="mdi-delete-outline" @click="handleDelete(card.id, table)"></v-icon>
    </td>
  </tr>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'

defineProps({
  card: {
    type: Object,
    default: () => (null)
  },
  table: {
    type: String,
    default: () => ('inventory')
  }
})

const emit = defineEmits(['delete-item']);

const handleDelete = (itemId: number, tableName: string) => {
  console.log('delete-item', itemId, tableName);
  emit('delete-item', itemId, tableName);
};
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
