<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router';
import AppHeader from './components/AppHeader.vue';
</script>

<template>
  <v-app>
    <v-container>
      <header>
        <div class="wrapper">
          <AppHeader msg="Great Material Continuum" />
          <nav>
            <RouterLink to="/"><v-icon icon="mdi-home"></v-icon></RouterLink>
            <RouterLink to="/inventory">Inventory</RouterLink>
            <RouterLink to="/about">About</RouterLink>
          </nav>
        </div>
      </header>

      <RouterView />
      <footer>
        <p>&copy; 2024 Great Material Continuum</p>
      </footer>
    </v-container>
  </v-app>
</template>

<style scoped>
.wrapper {
  display: flex;
  justify-content: space-between;
  vertical-align: middle;
}

footer {
  line-height: 1;
  font-size: small;
  margin-top: 1em;
  padding-top: 1em;
  color: var(--vt-c-text-dark);
  border-top: 1px solid var(--vt-c-primary-dark);
}

nav a.router-link-exact-active {
  color: var(--vt-c-text-dark-2);
}

nav a.router-link-exact-active:hover {
  background-color: transparent;
}

nav a {
  display: inline-block;
  padding: .5rem 1rem;
  text-align: center;
  border-radius: 8px;
}

nav a:first-of-type {
  border: 0;
}

@media (min-width: 1024px) {

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }

  nav {
    text-align: left;
  }
}
</style>
