<template>
  <v-card subtitle="Upload CSV to Add Cards">
    <v-card-actions>
      <!-- CSV upload form -->
      <form @submit.prevent="handleFileUpload">
        <label for="file-upload">
          <v-btn prepend-icon="mdi-folder-upload" @click="triggerFileInput">Choose a CSV file</v-btn>
        </label>
        <input id="file-upload" type="file" ref="fileInput" accept=".csv" style="display: none;" />
        <v-btn prepend-icon="mdi-export-variant" @click="handleFileUpload">upload</v-btn>
      </form>
      <!-- Display success or error message -->
      <p v-if="uploadMessage">{{ uploadMessage }}</p>
    </v-card-actions>
  </v-card>
</template>

<script setup lang="ts">
import { ref, defineProps } from 'vue';
import axios from 'axios';
import Papa from 'papaparse';  // Import PapaParse

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

// Define the file input reference
const fileInput = ref<HTMLInputElement | null>(null);

const triggerFileInput = () => {
  fileInput.value?.click();
};
// Handle the file upload and parse the CSV

const handleFileUpload = async () => {
  const input = fileInput.value;  // Access the file input reference

  if (!input || !input.files?.length) {
    uploadMessage.value = "Please select a file to upload.";
    return;
  }

  const file = input.files[0];  // Get the selected file

  // Parse the CSV file using PapaParse
  Papa.parse<Card>(file, {
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
