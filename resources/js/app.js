require('./bootstrap')

import { createApp } from 'vue'
import Login from './components/auth/login'

const app = createApp({})

app.component('login', Login)

app.mount('#app')