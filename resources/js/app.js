require('./bootstrap');

import { createApp } from 'vue';
import router from './router/routes';
import App from './pages/auth/login.vue'

const myApp = createApp(App)
myApp.use(router)
myApp.mount('#app')