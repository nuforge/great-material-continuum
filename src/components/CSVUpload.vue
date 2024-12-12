<template>
  <v-dialog max-width="500">
    <template v-slot:activator="{ props: dialogOpen }">
      <v-btn v-bind="dialogOpen" text="Upload CSV List" prepend-icon="mdi-file-upload" variant="outlined" size="small"
        color="grey"></v-btn>
    </template>
    <template v-slot:default="{ isActive }">
      <v-card title="Upload CSV List" prepend-icon="mdi-file-upload" max-width="400">
        <v-card-text>{{ uploadMessage }}
          <!-- CSV upload form -->
          <v-form v-model="valid" @submit.prevent>
            <v-file-input v-model="file" accept=".csv" clearable label="Select CSV file" prepend-icon="mdi-paperclip"
              chips></v-file-input>
          </v-form>
        </v-card-text>
        <v-card-actions class="d-flex jusify-space-between">
          <v-btn text="Close" color="warning" @click="isActive.value = false"></v-btn>
          <v-btn text="Upload" color="primary" @click="handleFileUpload"></v-btn>
        </v-card-actions>
      </v-card>
    </template>
  </v-dialog>
</template>

<script setup lang="ts">
import { ref, defineProps } from 'vue';
import axios from 'axios';
import Papa from 'papaparse';  // Import PapaParse

const valid = ref<boolean>(false);
const file = ref<File | null>(null);
const uploadMessage = ref<string | null>(null);

// Define the Card type
interface Card {
  card_name: string;
}

const cardTable = defineProps({
  table: {
    type: String,
    required: true,
  },
});

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
