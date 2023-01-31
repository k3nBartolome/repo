import { createRouter, createWebHashHistory } from "vue-router";
import store from "../store";
import AppLogin from "@/views/AppLogin";
import AppUserDashboard from "@/views/Dashboard/AppUserDashboard";
import ContactUs from "@/views/ContactUs";
import AppUserLayout from "@/components/AppUserLayout";
import AppAdminLayout from "@/components/AppAdminLayout";
import AuthLayout from "@/components/AuthLayout";
import AppAdminDashboard from "@/views/Dashboard/AppAdminDashboard";
<<<<<<< HEAD
=======
import UserIndex from "@/views/UserManagement/User/UserIndex";
import AppManagerDashboard from "@/views/Dashboard/AppManagerDashboard";
>>>>>>> 1f563001a5d6e4b298c86a82c60cba9797dd15b6

const routes = [
  {
    path: "/",
    redirect: "/dashboard",
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
    path: "/admin",
    redirect: "/admin/dashboard",
    component: AppAdminLayout,
    meta: { requiresAuth: true, requiresRole: "admin" },
    children: [
      {
        path: "dashboard",
        name: "adminDashboard",
        component: AppAdminDashboard,
      },
      {
        path: "/admin/userIndex",
        name: "userIndex",
        component: UserIndex,
      },
    ],
  },
  {
<<<<<<< HEAD
=======
    path: "/manager",
    redirect: "/manager/dashboard",
    component: AppManagerLayout,
    meta: { requiresAuth: true, requiresRole: "manager" },
    children: [
      {
        path: "/dashboard",
        name: "managerDashboard",
        component: AppManagerDashboard,
      },
    ],
  },
  {
>>>>>>> 1f563001a5d6e4b298c86a82c60cba9797dd15b6
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
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.name === "login" && store.getters.isLoggedIn) {
    //redirect to dashboard based on user role
    if (store.getters.returnRole === "admin") {
      return next({ name: "adminDashboard" });
    } else if (store.getters.returnRole === "manager") {
      return next({ name: "managerDashboard" });
    } else if (store.getters.returnRole === "user") {
      return next({ name: "userDashboard" });
    }
  }
  // check if the route requires authentication and if the user is logged in
  if (to.meta.requiresAuth && store.getters.isLoggedIn) {
    // check the user's role and redirect them to the appropriate dashboard
    if (
      to.meta.requiresRole === "admin" &&
      store.getters.returnRole === "admin"
    ) {
      if (to.name === "adminDashboard") return next();
      next({ name: "adminDashboard" });
    } else if (
      to.meta.requiresRole === "manager" &&
      store.getters.returnRole === "manager"
    ) {
      if (to.name === "managerDashboard") return next();
      next({ name: "managerDashboard" });
    } else if (
      to.meta.requiresRole === "user" &&
      store.getters.returnRole === "user"
    ) {
      if (to.name === "userDashboard") return next();
      next({ name: "userDashboard" });
    } else {
      next({ name: "login" });
    }
  } else if (to.meta.isGuest && !store.getters.isLoggedIn) {
    // allow guests to access the route
    next();
  } else {
    // redirect to dashboard based on user role
    if (store.getters.returnRole === "admin") {
      if (to.name === "adminDashboard") return next();
      next({ name: "adminDashboard" });
    } else if (store.getters.returnRole === "manager") {
      if (to.name === "managerDashboard") return next();
      next({ name: "managerDashboard" });
    } else if (store.getters.returnRole === "user") {
      if (to.name === "userDashboard") return next();
      next({ name: "userDashboard" });
    } else {
      next({ name: "login" });
    }
  }
});
export default router;
