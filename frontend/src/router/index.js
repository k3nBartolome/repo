import { createRouter, createWebHashHistory } from "vue-router";
import store from "../store";
import AppLogin from "@/views/AppLogin";
import ContactUs from "@/views/ContactUs";
import AppPerxLayout from "@/components/AppPerxLayout";
import AppUserLayout from "@/components/AppUserLayout";
import AppUserLayoutIndia from "@/components/AppUserLayoutIndia";
import AppUserLayoutJamaica from "@/components/AppUserLayoutJamaica";
import AppUserLayoutGuatemala from "@/components/AppUserLayoutGuatemala";
import AppAdminLayout from "@/components/AppAdminLayout";
import AuthLayout from "@/components/AuthLayout";
import AppAdminDashboard from "@/views/Dashboard/AppAdminDashboard";
import AppPerxDashboard from "@/views/Dashboard/AppPerxDashboard";
import UserManagement from "@/views/DashboardNavItems/Admin/UserManagement";
import programManagementEdit from "@/views/DashboardNavItems/Admin/EditProgram";
import siteManagementEdit from "@/views/DashboardNavItems/Admin/EditSite";
import programManagementEditIndia from "@/views/DashboardNavItems/Admin/EditProgramIndia";
import siteManagementEditIndia from "@/views/DashboardNavItems/Admin/EditSiteIndia";
import programManagementEditJamaica from "@/views/DashboardNavItems/Admin/EditProgramJamaica";
import siteManagementEditJamaica from "@/views/DashboardNavItems/Admin/EditSiteJamaica";
import programManagementEditGuatemala from "@/views/DashboardNavItems/Admin/EditProgramGuatemala";
import siteManagementEditGuatemala from "@/views/DashboardNavItems/Admin/EditSiteGuatemala";
import SiteManagement from "@/views//Dashboard/AppSiteDashboard.vue";
import SiteManagementIndia from "@/views//Dashboard/AppSiteDashboardIndia.vue";
import SiteManagementJamaica from "@/views//Dashboard/AppSiteDashboardJamaica.vue";
import SiteManagementGuatemala from "@/views//Dashboard/AppSiteDashboardGuatemala.vue";
import capacityFile from "@/views/DashboardNavItems/User/CapacityFile.vue";
import capacityFileIndia from "@/views/DashboardNavItems/User/CapacityFileIndia.vue";
import capacityFileJamaica from "@/views/DashboardNavItems/User/CapacityFileJamaica.vue";
import capacityFileGuatemala from "@/views/DashboardNavItems/User/CapacityFileGuatemala.vue";
import addCapacityFile from "@/views/DashboardNavItems/User/Capfile/AddCapfile.vue";
import addCapacityFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/AddCapfileIndia.vue";
import addCapacityFileJamaica from "@/views/DashboardNavItems/User/CapfileJamaica/AddCapfileJamaica.vue";
import addCapacityFileGuatemala from "@/views/DashboardNavItems/User/CapfileGuatemala/AddCapfileGuatemala.vue";
import ProgramManagement from "@/views/Dashboard/AppProgramDashboard.vue";
import ProgramManagementIndia from "@/views/Dashboard/AppProgramDashboardIndia.vue";
import ProgramManagementJamaica from "@/views/Dashboard/AppProgramDashboardJamaica.vue";
import ProgramManagementGuatemala from "@/views/Dashboard/AppProgramDashboardGuatemala.vue";
import pushbackCapacityFile from "@/views/DashboardNavItems/User/Capfile/PushedBackCapacityFile.vue";
import cancelCapacityFile from "@/views/DashboardNavItems/User/Capfile/CancelCapacityFile.vue";
import editCapFile from "@/views/DashboardNavItems/User/Capfile/EditCapfile.vue";
import pushbackCapacityFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/PushedBackCapacityFileIndia.vue";
import cancelCapacityFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/CancelCapacityFileIndia.vue";
import editCapFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/EditCapfileIndia.vue";
import pushbackCapacityFileJamaica from "@/views/DashboardNavItems/User/CapfileJamaica/PushedBackCapacityFileJamaica.vue";
import cancelCapacityFileJamaica from "@/views/DashboardNavItems/User/CapfileJamaica/CancelCapacityFileJamaica.vue";
import editCapFileJamaica from "@/views/DashboardNavItems/User/CapfileJamaica/EditCapfileJamaica.vue";
import pushbackCapacityFileGuatemala from "@/views/DashboardNavItems/User/CapfileGuatemala/PushedBackCapacityFileGuatemala.vue";
import cancelCapacityFileGuatemala from "@/views/DashboardNavItems/User/CapfileGuatemala/CancelCapacityFileGuatemala.vue";
import editCapFileGuatemala from "@/views/DashboardNavItems/User/CapfileGuatemala/EditCapfileGuatemala.vue";
import powerBi from "@/views/DashboardNavItems/User/powerBi.vue";
import StaffingTracker from "@/views/DashboardNavItems/User/StaffingTracker.vue";
import AddStaffingTracker from "@/views/DashboardNavItems/User/AddStaffingTracker.vue";
import UpdateStaffingTracker from "@/views/DashboardNavItems/User/UpdateStaffingTracker.vue";

const routes = [
  {
    path: "/",
    component: AppUserLayoutGuatemala,
    meta: { requiresAuth: true, requiresRole: "user" },
    children: [
      {
        path: "/capfileguatemala",
        name: "capacityFileGuatemala",
        component: capacityFileGuatemala,
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
        path: "/pushbackcapfileguatemala/:id",
        name: "pushbackCapacityFileGuatemala",
        component: pushbackCapacityFileGuatemala,
      },
      {
        path: "/cancelcapfileguatemala/:id",
        name: "cancelCapacityFileGuatemala",
        component: cancelCapacityFileGuatemala,
      },
      {
        path: "/editcapfileguatemala/:id",
        name: "editCapFileGuatemala",
        component: editCapFileGuatemala,
      },
      {
        path: "/addcapfileguatemala/:id",
        name: "addCapacityFileGuatemala",
        component: addCapacityFileGuatemala,
      },
      {
        path: "/site_managementguatemala",
        name: "sitemanagementGuatemala",
        component: SiteManagementGuatemala,
      },
      {
        path: "/site_managementguatemala/edit/:id",
        name: "sitemanagementeditGuatemala",
        component: siteManagementEditGuatemala,
      },
      {
        path: "/program_managementguatemala",
        name: "programmanagementGuatemala",
        component: ProgramManagementGuatemala,
      },
      {
        path: "/program_managementguatemala/edit/:id",
        name: "programmanagementeditGuatemala",
        component: programManagementEditGuatemala,
      },
    ],
  },
  {
    path: "/",
    component: AppUserLayoutJamaica,
    meta: { requiresAuth: true, requiresRole: "user" },
    children: [
      {
        path: "/capfilejamaica",
        name: "capacityFileJamaica",
        component: capacityFileJamaica,
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
        path: "/pushbackcapfilejamaica/:id",
        name: "pushbackCapacityFileJamaica",
        component: pushbackCapacityFileJamaica,
      },
      {
        path: "/cancelcapfilejamaica/:id",
        name: "cancelCapacityFileJamaica",
        component: cancelCapacityFileJamaica,
      },
      {
        path: "/editcapfilejamaica/:id",
        name: "editCapFileJamaica",
        component: editCapFileJamaica,
      },
      {
        path: "/addcapfilejamaica/:id",
        name: "addCapacityFileJamaica",
        component: addCapacityFileJamaica,
      },
      {
        path: "/site_managementjamaica",
        name: "sitemanagementJamaica",
        component: SiteManagementJamaica,
      },
      {
        path: "/site_managementjamaica/edit/:id",
        name: "sitemanagementeditJamaica",
        component: siteManagementEditJamaica,
      },
      {
        path: "/program_managementjamaica",
        name: "programmanagementJamaica",
        component: ProgramManagementJamaica,
      },
      {
        path: "/program_managementjamaica/edit/:id",
        name: "programmanagementeditJamaica",
        component: programManagementEditJamaica,
      },
    ],
  },
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
        component: siteManagementEditIndia,
      },
      {
        path: "/program_managementindia",
        name: "programmanagementIndia",
        component: ProgramManagementIndia,
      },
      {
        path: "/program_managementindia/edit/:id",
        name: "programmanagementeditIndia",
        component: programManagementEditIndia,
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
        path: "/addstaffing/:id",
        name: "AddStaffingTracker",
        component: AddStaffingTracker,
      },
      {
        path: "/updatestaffing/:id",
        name: "UpdateStaffingTracker",
        component: UpdateStaffingTracker,
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
    path: "/",
    component: AppPerxLayout,
    meta: {
      requiresAuth: true,
      requiresRole: "perx",
    },
    children: [
      {
        path: "/perx_manager",
        name: "Perx",
        component: AppPerxDashboard,
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
