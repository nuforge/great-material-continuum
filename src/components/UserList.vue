<template>
  <div>
    <h1>User List</h1>
    <ul>
      <li v-for="user in users" :key="user.id">{{ user.name }} ({{ user.email }})</li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

const users = ref<{ id: number; name: string; email: string }[]>([]);

onMounted(async () => {
  try {
    const response = await axios.get('http://localhost:8080/backend/api/endpoint.php?path=users');
    users.value = response.data;
  } catch (error) {
    console.error('Error fetching users:', error);
  }
});
</script>

<style scoped>
/* Add your styles here */
</style>
