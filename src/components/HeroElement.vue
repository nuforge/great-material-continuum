<template>
  <section class="hero" :style="{ backgroundImage: `url(${background})` }">
    <div class="hero-content">
      <h1 class="hero-title">{{ title }}</h1>
      <p class="hero-subtitle">{{ subtitle }}</p>
      <button class="hero-button" @click="handleClick">{{ buttonText }}</button>
    </div>
  </section>
</template>

<script setup lang="ts">
defineProps({
  title: {
    type: String,
    required: true,
  },
  subtitle: {
    type: String,
    required: true,
  },
  buttonText: {
    type: String,
    required: true,
  },
  background: {
    type: String,
    required: true,
  },
})
const handleClick = () => {
  console.log('Button clicked')
}
</script>

<style scoped>
.hero {
  height: 300px;
  padding: 1rem;
  color: white;
  display: flex;
}
</style>
