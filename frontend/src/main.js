import { createApp } from 'vue'
import App from './App.vue'
import '../src/index.css'
import store from '../src/store'
import router from '../src/router'
import 'font-awesome/css/font-awesome.min.css'
import PrimeVue from 'primevue/config';

createApp(App)
.use(store)
.use(router)
.use(PrimeVue)
.mount('#app')
