<template>
  <!-- CSV upload form -->
  <v-form v-model="valid" @submit.prevent>
    <v-file-input v-model="file" accept=".csv" clearable label="Select CSV file" prepend-icon="mdi-file-upload"
      chips></v-file-input>
    <v-btn color="primary" @click="handleFileUpload" block>
      Upload
    </v-btn>
  </v-form>
  <p>{{ uploadMessage }}</p>
</template>

<script setup lang="ts">
import { ref, defineProps } from 'vue';
import axios from 'axios';
import Papa from 'papaparse';  // Import PapaParse

const valid = ref<boolean>(false);
const file = ref<File | null>(null);
const cardTable = defineProps({
  table: {
    type: String,
    required: true,
  },
});

// Define the Card type
interface Card {
  card_name: string;
}

const uploadMessage = ref<string | null>(null);

// Handle the file upload and parse the CSV
const handleFileUpload = async () => {
  if (!file.value) {
    uploadMessage.value = "Please select a file to upload.";
    return;
  }

  // Parse the CSV file using PapaParse
  Papa.parse<Card>(file.value, {
    complete: async (result) => {
      const cards: Card[] = result.data;  // Assuming CSV is structured in rows of [card_name, user]
      // Send the parsed data to the backend
      await uploadCardsToDatabase(cards);
    },
    header: true,  // assuming the first row contains headers
  });
};

// Upload the parsed cards data to the PHP backend
const uploadCardsToDatabase = async (cards: Card[]) => {
  try {
    // Prepare the data to send to backend
    const data = {
      cards,
    };
    const response = await axios.post(`http://localhost:8080/backend/api/endpoint.php?path=${cardTable.table}`, data);
    console.log("Uploaded cards:", cards, "to table:", cardTable.table, response.data);
    uploadMessage.value = "Cards uploaded successfully!";
  } catch (error) {
    console.error("Error uploading cards:", error);
    uploadMessage.value = "Failed to upload cards.";
  }
};
</script>

<style scoped>
.wrapper button,
.custom-file-upload {
  display: inline-block;
  margin: 0 1rem;
}

.custom-file-upload {
  margin-left: 0;
}
</style>
