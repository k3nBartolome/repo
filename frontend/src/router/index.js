import { createRouter, createWebHashHistory } from "vue-router";
import store from "../store";
import AppLogin from "@/views/AppLogin";
import ContactUs from "@/views/ContactUs";
import AppUserLayout from "@/components/AppUserLayout";
import AppUserLayoutIndia from "@/components/AppUserLayoutIndia";
import AppAdminLayout from "@/components/AppAdminLayout";
import AuthLayout from "@/components/AuthLayout";
import AppAdminDashboard from "@/views/Dashboard/AppAdminDashboard";
import UserManagement from "@/views/DashboardNavItems/Admin/UserManagement";
import programManagementEdit from "@/views/DashboardNavItems/Admin/EditProgram";
import siteManagementEdit from "@/views/DashboardNavItems/Admin/EditSite";
import SiteManagement from "@/views//Dashboard/AppSiteDashboard.vue";
import SiteManagementIndia from "@/views//Dashboard/AppSiteDashboardIndia.vue";
import capacityFile from "@/views/DashboardNavItems/User/CapacityFile.vue";
import capacityFileIndia from "@/views/DashboardNavItems/User/CapacityFileIndia.vue";
import addCapacityFile from "@/views/DashboardNavItems/User/Capfile/AddCapfile.vue";
import addCapacityFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/AddCapfileIndia.vue";
import ProgramManagement from "@/views/Dashboard/AppProgramDashboard.vue";
import ProgramManagementIndia from "@/views/Dashboard/AppProgramDashboardIndia.vue";
import pushbackCapacityFile from "@/views/DashboardNavItems/User/Capfile/PushedBackCapacityFile.vue";
import cancelCapacityFile from "@/views/DashboardNavItems/User/Capfile/CancelCapacityFile.vue";
import editCapFile from "@/views/DashboardNavItems/User/Capfile/EditCapfile.vue";
import pushbackCapacityFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/PushedBackCapacityFileIndia.vue";
import cancelCapacityFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/CancelCapacityFileIndia.vue";
import editCapFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/EditCapfileIndia.vue";
import powerBi from "@/views/DashboardNavItems/User/powerBi.vue";
import StaffingTracker from "@/views/DashboardNavItems/User/StaffingTracker.vue";

const routes = [
  {
    path: "/",
    component: AppUserLayoutIndia,
    meta: { requiresAuth: true, requiresRole: "user" },
    children: [
      {
        path: "/capfileindia",
        name: "capacityFileIndia",
        component: capacityFileIndia,
      },
      {
        path: "/staffing",
        name: "StaffingTracker",
        component: StaffingTracker,
      },
      {
        path: "/powerbi",
        name: "powerBi",
        component: powerBi,
      },
      {
        path: "/pushbackcapfileindia/:id",
        name: "pushbackCapacityFileIndia",
        component: pushbackCapacityFileIndia,
      },
      {
        path: "/cancelcapfileindia/:id",
        name: "cancelCapacityFileIndia",
        component: cancelCapacityFileIndia,
      },
      {
        path: "/editcapfileindia/:id",
        name: "editCapFileIndia",
        component: editCapFileIndia,
      },
      {
        path: "/addcapfileindia/:id",
        name: "addCapacityFileIndia",
        component: addCapacityFileIndia,
      },
      {
        path: "/site_managementindia",
        name: "sitemanagementIndia",
        component: SiteManagementIndia,
      },
      {
        path: "/site_managementindia/edit/:id",
        name: "sitemanagementeditIndia",
        //component: siteManagementEditIndia,
      },
      {
        path: "/program_managementindia",
        name: "programmanagementIndia",
        component: ProgramManagementIndia,
      },
      {
        path: "/program_managementindia/edit/:id",
        name: "programmanagementeditIndia",
        //component: programManagementEditIndia,
      },
    ],
  },
  {
    path: "/",
    component: AppUserLayout,
    meta: { requiresAuth: true, requiresRole: "user" },
    children: [
      {
        path: "/capfile",
        name: "capacityFile",
        component: capacityFile,
      },
      {
        path: "/staffing",
        name: "StaffingTracker",
        component: StaffingTracker,
      },
      {
        path: "/powerbi",
        name: "powerBi",
        component: powerBi,
      },
      {
        path: "/pushbackcapfile/:id",
        name: "pushbackCapacityFile",
        component: pushbackCapacityFile,
      },
      {
        path: "/cancelcapfile/:id",
        name: "cancelCapacityFile",
        component: cancelCapacityFile,
      },
      {
        path: "/editcapfile/:id",
        name: "editCapFile",
        component: editCapFile,
      },
      {
        path: "/addcapfile/:id",
        name: "addCapacityFile",
        component: addCapacityFile,
      },
      {
        path: "/site_management",
        name: "sitemanagement",
        component: SiteManagement,
      },
      {
        path: "/site_management/edit/:id",
        name: "sitemanagementedit",
        component: siteManagementEdit,
      },
      {
        path: "/program_management",
        name: "programmanagement",
        component: ProgramManagement,
      },
      {
        path: "/program_management/edit/:id",
        name: "programmanagementedit",
        component: programManagementEdit,
      },
    ],
  },
  {
    path: "/",
    component: AppAdminLayout,
    meta: {
      requiresAuth: true,
      requiresRole: "admin",
    },
    children: [
      {
        path: "/admin_dashboard",
        name: "adminDashboard",
        component: AppAdminDashboard,
      },
      {
        path: "/user_management",
        name: "usermanagement",
        component: UserManagement,
      },
    ],
  },
  {
    path: "/auth",
    name: "Auth",
    component: AuthLayout,
    meta: {
      isGuest: true,
    },
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
        next({
          query: {
            returnUrl: to.path,
          },
        });
      }
    } else {
      next({
        name: "login",
      });
    }
  } else if (to.meta.isGuest && !store.getters.isLoggedIn) {
    next();
  } else {
    next();
  }
});

export default router;
