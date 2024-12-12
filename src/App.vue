<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router';
import AppHeader from './components/AppHeader.vue';
</script>

<template>
  <v-app>
    <v-main>
      <v-container>
        <header>
          <div class="wrapper">
            <AppHeader msg="Great Material Continuum" />
            <nav>
              <RouterLink to="/"></RouterLink>
              <RouterLink to="/inventory"><v-icon icon="mdi-view-list"></v-icon> Inventory</RouterLink>
              <RouterLink to="/wishlist"><v-icon icon="mdi-format-list-checkbox"></v-icon> Wishlist</RouterLink>
              <RouterLink to="/trades"><v-icon icon="mdi-swap-horizontal-bold"></v-icon> Trades</RouterLink>
              <RouterLink to="/about"><v-icon icon="mdi-information-outline"></v-icon> About</RouterLink>
            </nav>
          </div>
        </header>

        <RouterView />
        <footer>
          <p>&copy; 2024 <RouterLink to="/">Great Material Continuum</RouterLink>
          </p>
        </footer>
      </v-container>
    </v-main>
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
