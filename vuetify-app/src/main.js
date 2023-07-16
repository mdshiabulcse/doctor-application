

// Components
import App from './App.vue'
import './style.css';
import './template.js';
import ElementPlus from 'element-plus';
import router from "./router/index.js";
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
// Composables
import { createApp } from 'vue'
import { Field, Form, ErrorMessage } from 'vee-validate';
// Plugins
import { registerPlugins } from '@/plugins'





const app = createApp(App)
app.component('Field', Field);
app.component('Form', Form);
app.component('ErrorMessage', ErrorMessage);
// Register Vuetify components


// app.component('VTextField', VTextField);
const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)


registerPlugins(app)
app.use(ElementPlus)
app.use(router);
app.use(pinia);
app.mount('#app')
