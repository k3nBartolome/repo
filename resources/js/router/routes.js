import {
    createRouter,
    createWebHistory
} from 'vue-router';
import ContactUs from '../pages/components/contactus.vue';
import Index from '../pages/auth/login.vue';

const routes = [
    {
      name:'index',
      path: '/',
      component: Index,
    },
    {
      name:'contactus',
      path: '/contactus',
      component: ContactUs
    }
  ]
  
  const router = createRouter({
    history: createWebHistory(),
    routes
  })
  
  export default router