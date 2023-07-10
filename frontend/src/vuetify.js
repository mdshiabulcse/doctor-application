import { createApp } from 'vue';
import { Vuetify } from 'vuetify';
import 'vuetify/dist/vuetify.min.css';

import '@mdi/font/css/materialdesignicons.css';

export function setupVuetify(app) {
    const vuetify = new Vuetify();

    app.use(vuetify);
}

const app = createApp(App);
setupVuetify(app);
app.mount('#app');
  