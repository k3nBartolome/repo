import { createApp } from 'vue'
import App from './App.vue'
import '../src/index.css'
import store from '../src/store'
import router from '../src/router'
import VueAuth from 'vue-auth'
import axios from 'axios'


createApp(App)
.use(VueAuth, {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js')
  })
.use(axios)
.use(store)
.use(router)
.mount('#app')
