<template>
  <div>
    <h1>Upload CSV to Add Cards</h1>

    <!-- CSV upload form -->
    <form @submit.prevent="handleFileUpload">
      <label for="file-upload" class="custom-file-upload">
        Choose File...
      </label>
      <input id="file-upload" type="file" ref="fileInput" accept=".csv" />
      <button type="submit">Upload CSV</button>
    </form>

    <!-- Display success or error message -->
    <p v-if="uploadMessage">{{ uploadMessage }}</p>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';
import Papa from 'papaparse';  // Import PapaParse

// Define the Card type
interface Card {
  card_name: string;
}

const uploadMessage = ref<string | null>(null);

// Define the file input reference
const fileInput = ref<HTMLInputElement | null>(null);

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
      console.log("Parsed CSV result:", result);
      const cards: Card[] = result.data;  // Assuming CSV is structured in rows of [card_name, type]

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

    const response = await axios.post('http://localhost:8080/backend/api/endpoint.php?path=cards', data);
    console.log(response.data);
    uploadMessage.value = "Cards uploaded successfully!";
  } catch (error) {
    console.error("Error uploading cards:", error);
    uploadMessage.value = "Failed to upload cards.";
  }
};
</script>
