import { createApp } from 'vue';
import { createPinia } from 'pinia';
import './style.css';
import './template.js';
import router from "./router/index.js";
import ElementPlus from 'element-plus';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import App from './App.vue';


const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);


const  app=createApp(App);
    app.use(pinia);
    app.use(ElementPlus);
    app.use(router);
    app.mount('#app');
