<template>
  <div class="greetings">
    <h1 class="green">
      <RouterLink to="/">{{ msg }}</RouterLink>
    </h1>
  </div>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router';
import { defineProps } from 'vue';

defineProps<{
  msg: string
}>()

</script>

<style scoped>
.greetings h1,
.greetings h3 {
  text-align: center;
}

@media (min-width: 1024px) {

  .greetings h1,
  .greetings h3 {
    text-align: left;
  }
}
</style>
