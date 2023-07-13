/* eslint-disable import/order */
import '@/@iconify/icons-bundle'
import App from '@/App.vue'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
import router from '@/router'
import '@core/scss/template/index.scss'
import '@layouts/styles/index.scss'
import '@styles/styles.scss'
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import { createPinia } from 'pinia'
import { createApp } from 'vue'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

loadFonts()


// Create vue app
const app = createApp(App)

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);
// Use plugins
app.use(vuetify);
app.use(createPinia());
app.use(router);

// Mount vue app
app.mount('#app')
