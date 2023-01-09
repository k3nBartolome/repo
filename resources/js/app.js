require('./bootstrap');

import { createApp } from 'vue';

const app = createApp({})


app.component('login', require('./components/auth/login.vue').default);

app.mount("#app");