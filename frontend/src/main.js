import { createApp } from 'vue'
import App from './App.vue'
import '../src/index.css'
import store from '../src/store'
import router from '../src/router'
/* import axios from 'axios'

axios.interceptors.request.use(config => {
  config.headers.common['Authorization'] = `Bearer ${localStorage.getItem("token")}`;
  return config;
}, error => {
  return Promise.reject(error);
}); */

createApp(App)
.use(store)
.use(router)
.mount('#app')
