import {
  createRouter,
  createWebHashHistory
} from "vue-router";
import store from "../store";
import AppLogin from "@/views/AppLogin";
import AppUserDashboard from "@/views/Dashboard/AppUserDashboard";
import ContactUs from "@/views/ContactUs";
import AppUserLayout from "@/components/AppUserLayout";
import AppAdminLayout from "@/components/AppAdminLayout";
import AuthLayout from "@/components/AuthLayout";
import AppAdminDashboard from "@/views/Dashboard/AppAdminDashboard";
import UserManagement from "@/views/DashboardNavItems/Admin/UserManagement";
import UserAdd from "@/views/DashboardNavItems/Admin/User/UserAdd";
import UserEdit from "@/views/DashboardNavItems/Admin/User/UserEdit";
import UserShow from '@/views/DashboardNavItems/Admin/User/UserShow';
import SiteManagement from '../views/Dashboard/AppSiteDashboard.vue';

const routes = [{
    path: "/",
    component: AppUserLayout,
    meta: {
      requiresAuth: true,
      requiresRole: "user"
    },
    children: [{
      path: "/dashboard",
      name: "userDashboard",
      component: AppUserDashboard,
    }, ],
  },
  {
    path: "/",
    component: AppAdminLayout,
    meta: {
      requiresAuth: true,
      requiresRole: "admin"
    },
    children: [{
        path: "/admin_dashboard",
        name: "adminDashboard",
        component: AppAdminDashboard,
      },
      {
        path: "/user_management",
        name: "usermanagement",
        component: UserManagement,
      },
      {
        path: "/user_add",
        name: "userAdd",
        component: UserAdd,
      },
      {
        path: "/user_edit/:user_id",
        name: "userEdit",
        component: UserEdit,
      },
      {
        path: "/user_show/:user_id",
        name: "userShow",
        component: UserShow,
      },
      {
        path: "/site_management",
        name: "sitemanagement",
        component: SiteManagement,
      },
    ],
  },
  {
    path: "/auth",
    name: "Auth",
    component: AuthLayout,
    meta: {
      isGuest: true
    },
    children: [{
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
        next({
          query: {
            returnUrl: to.path
          }
        });
      }
    } else {
      next({
        name: "login"
      });
    }
  } else if (to.meta.isGuest && !store.getters.isLoggedIn) {
    next();
  } else {
    next();
  }
});

export default router;
