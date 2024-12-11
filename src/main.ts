import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import { VApp, VBtn, VIcon, VCard, VContainer, VDialog } from 'vuetify/components'

import '@mdi/font/css/materialdesignicons.css'

const app = createApp(App)

const vuetify = createVuetify({
  theme: {
    defaultTheme: 'dark',
  },
  components: {
    VApp,
    VCard,
    VIcon,
    VContainer,
    VBtn,
    VDialog,
  },
})

app.use(createPinia())
app.use(router)
app.use(vuetify)

app.mount('#app')
