import { createRouter, createWebHashHistory } from "vue-router";
import store from "../store";
import AppLogin from "@/views/AppLogin";
import AppUserDashboard from "@/views/Dashboard/AppUserDashboard";
import ContactUs from "@/views/ContactUs";
import AppUserLayout from "@/components/AppUserLayout";
import AppManagerLayout from "@/components/AppManagerLayout";
import AppAdminLayout from "@/components/AppAdminLayout";
import AuthLayout from "@/components/AuthLayout";
import AppAdminDashboard from "@/views/Dashboard/AppAdminDashboard";
import AppManagerDashboard from "@/views/Dashboard/AppManagerDashboard";

const routes = [
  {
    path: "/",
    redirect: "/dashboard",
    component: AppUserLayout,
    meta: { requiresAuth: true, requiresRole: 'user' },
    children: [
      {
        path: "/dashboard",
        name: "Dashboard",
        component: AppUserDashboard
      },
    ],
  },
  {
    path: "/admin",
    redirect: "/admin/dashboard",
    component: AppAdminLayout,
    meta: { requiresAuth: true, requiresRole: 'admin' },
    children: [
      {
        path: "/admin/dashboard",
        name: "adminDashboard",
        component: AppAdminDashboard,
      },
    ],
  },
  {
    path: "/manager",
    redirect: "/manager/dashboard",
    component: AppManagerLayout,
    meta: { requiresAuth: true, requiresRole: 'manager' },
    children: [
      {
        path: "/manager/dashboard",
        name: "managerDashboard",
        component: AppManagerDashboard,
      },
    ],
  },
  {
    path: "/auth",
    redirect: "/login",
    name: "Auth",
    component: AuthLayout,
    meta: { isGuest: true },
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
});

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !store.getters.isLoggedIn) {
    next({ name: "login" });
  } else if (to.meta.requiresRole && to.meta.requiresRole !== store.getters.returnRole) {
    next({ name: from.name });
  } else if (store.getters.isLoggedIn) {
    if(store.getters.returnRole){
      next();}
  } else {
    next();
  }
});


export default router;
