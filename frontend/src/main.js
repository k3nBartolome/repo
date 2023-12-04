import { createApp } from 'vue'
import App from './App.vue'
import '../src/index.css'
import store from '../src/store'
import router from '../src/router'
import 'font-awesome/css/font-awesome.min.css'
import PrimeVue from 'primevue/config';

// Import PrimeVue styles (if you haven't already)


// Import any other styles or global CSS files here

createApp(App)
  .use(store)
  .use(router)
  .use(PrimeVue)
  .mount('#app');