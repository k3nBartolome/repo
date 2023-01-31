import { createRouter, createWebHashHistory } from "vue-router";
import store from "../store";
import AppLogin from "@/views/AppLogin";
import AppUserDashboard from "@/views/Dashboard/AppUserDashboard";
import ContactUs from "@/views/ContactUs";
import AppUserLayout from "@/components/AppUserLayout";
import AppAdminLayout from "@/components/AppAdminLayout";
import AuthLayout from "@/components/AuthLayout";
import AppAdminDashboard from "@/views/Dashboard/AppAdminDashboard";

const routes = [
  {
    path: "/",
    component: AppUserLayout,
    meta: { requiresAuth: true, requiresRole: "user" },
    children: [
      {
        path: "/dashboard",
        name: "userDashboard",
        component: AppUserDashboard,
      },
    ],
  },
  {
    path: "/",
    component: AppAdminLayout,
    meta: { requiresAuth: true, requiresRole: "admin" },
    children: [
      {
        path: "/admindashboard",
        name: "adminDashboard",
        component: AppAdminDashboard,
      },
    ],
  },
  {
    path: "/auth",
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
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth) {
    if (store.getters.isLoggedIn) {
      if (to.meta.requiresRole === store.getters.returnRole) {
        next();
      } else {
        next({ name: "login" });
      }
    } else {
      next({ name: "login" });
    }
  } else if (to.meta.isGuest && !store.getters.isLoggedIn) {
    next();
  } else {
    next();
  }
});

export default router;
