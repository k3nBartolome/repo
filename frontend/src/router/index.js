import {
  createRouter,
  createWebHashHistory
} from "vue-router";
import store from '../store';
import AppLogin from '@/views/AppLogin';
import AppDashboard from '@/views/AppDashboard';
import ContactUs from '@/views/ContactUs';
import AppLayout from '@/components/AppLayout'

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
        component: AppDashboard
      },
    ],
  },
  {
    path: '/login',
    component: AppLogin,
    name: 'login',
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/contact',
    component: ContactUs,
    name: 'contact',
    meta: {
      requiresAuth: false
    }
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
