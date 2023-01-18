import {
  createRouter,
  createWebHashHistory
} from "vue-router";
import store from '../store';
import AppLogin from '@/views/AppLogin';
import AppUserDashboard from '@/views/Dashboard/AppUserDashboard'
import ContactUs from '@/views/ContactUs';
import AppLayout from '@/components/AppLayout'
import AuthLayout from "@/components/AuthLayout.vue";
const routes = [{
    path: "/",
    redirect: "/dashboard",
    component: AppLayout,
    meta: {
      requiresAuth: true
    },
    children: [{
        path: "/dashboard",
        name: "Dashboard",
        component: AppUserDashboard
      },
    ],
  },
  {
    path: "/auth",
    redirect: "/login",
    name: "Auth",
    component: AuthLayout,
    meta: {isGuest: true},
    children: [
      {
        path: "/login",
        name: "login",
        component: AppLogin,
      },
      {
        path: "/contact",
        name: "contact",
        component: ContactUs,
      },
    ],
  },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  if (to.name === 'login' && store.getters.isLoggedIn) {
    next({
      name: from.name
    });
  } else if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!store.getters.isLoggedIn) {
      next({
        name: 'login'
      });
    } else {
      next();
    }
  } else {
    next();
  }
});
export default router;
