<template>
  <div>
    <!-- Display the name of the first user -->
    <p>{{ users ? users.name : "loading..." }}</p>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Define message as an object type with a name property
const users = ref<{ name: string } | null>(null);

const fetchUsers = async () => {
  try {
    const response = await axios.get('http://localhost:8080/backend/api/endpoint.php?path=users');

    // Assign the first user from the response to message
    if (response.data.length > 0) {
      users.value = response.data[0]; // Assuming the response is an array of users
    }
  } catch (error) {
    console.error('Error fetching the message:', error);
  }
};


onMounted(fetchUsers);
</script>
