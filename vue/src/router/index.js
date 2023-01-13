import {
  createRouter,
  createWebHashHistory
} from "vue-router";
import Dashboard from '../views/Dashboard.vue';
import Login from '../views/Login.vue';
import Contact from '../views/Contact.vue';

const routes = [
    {
        path: '/login',
        component: Login,
        name: 'login'
    },
    {
        path: '/contact',
        component: Contact,
        name: 'contact'
    },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router;
