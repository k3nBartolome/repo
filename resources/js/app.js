require('./bootstrap');

import { createApp } from 'vue';
import router from './router/routes';

const app = createApp({
})
app.use(router)
app.component('index', require('./pages/auth/login.vue').default)
app.mount("#app");