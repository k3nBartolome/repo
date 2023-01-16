import {
  createRouter,
  createWebHashHistory
} from "vue-router";
import store from '../store';
import AppLogin from '@/views/AppLogin';
import AppDashboard from '@/views/AppDashboard';
import ContactUs from '@/views/ContactUs';

const routes = [{
    path: '/dashboard',
    component: AppDashboard,
    name: 'dashboard',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/login',
    component: AppLogin,
    name: 'login',
    meta: {
      guest: true
    }
  },
  {
    path: '/contact',
    component: ContactUs,
    name: 'contact',
    meta: {
      guest: true
    }
  },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !store.state.user.token) {
    next({ name: "login" });
  } else if (store.state.user.token && to.meta.isGuest) {
    next({ name: "dashboard" });
  } else {
    next();
  }
});
export default router;
