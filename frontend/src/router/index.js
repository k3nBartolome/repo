import {
  createRouter,
  createWebHashHistory
} from "vue-router";
import store from "../store";
import AppLogin from "@/views/AppLogin";
import ContactUs from "@/views/ContactUs";
import AppPerxLayout from "@/components/AppPerxLayout";
import AppUserLayout from "@/components/AppUserLayout";
import AppAdminLayout from "@/components/AppAdminLayout";
import AuthLayout from "@/components/AuthLayout";
import AppAdminDashboard from "@/views/Dashboard/AppAdminDashboard";
import AppPerxDashboard from "@/views/Dashboard/AppPerxDashboard";
import UserManagement from "@/views/DashboardNavItems/Admin/UserManagement";
import programManagementEdit from "@/views/DashboardNavItems/Admin/EditProgram";
import siteManagementEdit from "@/views/DashboardNavItems/Admin/EditSite";
import SiteManagement from "@/views//Dashboard/AppSiteDashboard.vue";
import capacityFile from "@/views/DashboardNavItems/User/CapacityFile.vue";
import capacityFileReport from "@/views/DashboardNavItems/User/CapacityFileReport.vue";
import addCapacityFile from "@/views/DashboardNavItems/User/Capfile/AddCapfile.vue";
import ProgramManagement from "@/views/Dashboard/AppProgramDashboard.vue";
import pushbackCapacityFile from "@/views/DashboardNavItems/User/Capfile/PushedBackCapacityFile.vue";
import cancelCapacityFile from "@/views/DashboardNavItems/User/Capfile/CancelCapacityFile.vue";
import editCapFile from "@/views/DashboardNavItems/User/Capfile/EditCapfile.vue";
import powerBi from "@/views/DashboardNavItems/User/powerBi.vue";
import StaffingTracker from "@/views/DashboardNavItems/User/StaffingTracker.vue";
import AddStaffingTracker from "@/views/DashboardNavItems/User/AddStaffingTracker.vue";
import UpdateStaffingTracker from "@/views/DashboardNavItems/User/UpdateStaffingTracker.vue";
import inventoryTracker from "@/views/DashboardNavItems/User/InventoryTracker.vue";
import supplyManager from "@/views/DashboardNavItems/User/InventoryTracker/SupplyManager.vue";
import siteSupplyManager from "@/views/DashboardNavItems/User/InventoryTracker/SiteSupplyManager.vue";
import dashboardManager from "@/views/DashboardNavItems/User/InventoryTracker/DashboardManager.vue";
import dashboardAwarded from "@/views/DashboardNavItems/User/InventoryTracker/DashboardAwarded.vue";
import dashboardRequest from "@/views/DashboardNavItems/User/InventoryTracker/DashboardRequests.vue";
import dashboardSupply from "@/views/DashboardNavItems/User/InventoryTracker/DashboardSupply.vue";
import dashboardSiteSupply from "@/views/DashboardNavItems/User/InventoryTracker/DashboardSiteSupply.vue";
import siteRequestManager from "@/views/DashboardNavItems/User/InventoryTracker/SiteRequestManager.vue";
import siteRequestReceived from "@/views/DashboardNavItems/User/InventoryTracker/SiteRequestReceived.vue";
import siteRequest from "@/views/DashboardNavItems/User/InventoryTracker/SiteRequest.vue";
import awardManager from "@/views/DashboardNavItems/User/InventoryTracker/AwardManager.vue";
import awardNormal from "@/views/DashboardNavItems/User/InventoryTracker/AwardNormal.vue";
import awardPremium from "@/views/DashboardNavItems/User/InventoryTracker/AwardPremium.vue";
import requestManagerPending from "@/views/DashboardNavItems/User/InventoryTracker/RequestManagerPending.vue";
import requestManagerApproved from "@/views/DashboardNavItems/User/InventoryTracker/RequestManagerApproved.vue";
import requestManagerDenied from "@/views/DashboardNavItems/User/InventoryTracker/RequestManagerDenied.vue";
import requestManagerCancelled from "@/views/DashboardNavItems/User/InventoryTracker/RequestManagerCancelled.vue";

const routes = [{
    path: "/",
    component: AppUserLayout,
    meta: {
      requiresAuth: true,
      requiresRoles: ["user", "remx", "sourcing", "budget"]
    },
    children: [{
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
        path: "/inventory",
        name: "inventoryTracker",
        component: inventoryTracker,
        children: [{
            path: "supply_manager",
            name: "supplyManager",
            component: supplyManager,
          },
          {
            path: "/dashboard_manager",
            name: "dashboardManager",
            component: dashboardManager,
            children: [{
              path: "request",
              name: "dashboardRequest",
              component: dashboardRequest,
            },
            {
              path: "supply",
              name: "dashboardSupply",
              component: dashboardSupply,
            },
            {
              path: "site_supply",
              name: "dashboardSiteSupply",
              component: dashboardSiteSupply,
            },
            {
              path: "awarded",
              name: "dashboardAwarded",
              component: dashboardAwarded,
            },
          ],
          },
          {
            path: "/site_request_manager",
            name: "siteRequestManager",
            component: siteRequestManager,
            children: [{
                path: "request",
                name: "siteRequest",
                component: siteRequest,
              },
              {
                path: "received",
                name: "siteRequestReceived",
                component: siteRequestReceived,
              },
              {
                path: "pending",
                name: "requestManagerPending",
                component: requestManagerPending,
              },
              {
                path: "approved",
                name: "requestManagerApproved",
                component: requestManagerApproved,
              },
              {
                path: "denied",
                name: "requestManagerDenied",
                component: requestManagerDenied,
              },
              {
                path: "cancelled",
                name: "requestManagerCancelled",
                component: requestManagerCancelled,
              },
            ],
          },
          {
            path: "site_supply_manager",
            name: "siteSupplyManager",
            component: siteSupplyManager,
          },
          {
            path: "/award_manager",
            name: "awardManager",
            component: awardManager,
            children: [{
                path: "normal",
                name: "awardNormal",
                component: awardNormal,
              },
              {
                path: "premium",
                name: "awardPremium",
                component: awardPremium,
              },
            ],
          },
        ],
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
        path: "/staffing_report",
        name: "powerBi",
        component: powerBi,
      },
      {
        path: "/capfile_report",
        name: "capacityFileReport",
        component: capacityFileReport,
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
    ],
  },
  {
    path: "/",
    component: AppPerxLayout,
    meta: {
      requiresAuth: true,
      requiresRole: "perx",
    },
    children: [{
      path: "/perx_manager",
      name: "Perx",
      component: AppPerxDashboard,
    }, ],
  },
  {
    path: "/auth",
    name: "Auth",
    component: AuthLayout,
    meta: {
      isGuest: true,
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
      const requiredRoles = to.meta.requiresRoles || [];
      const userRole = store.getters.returnRole;

      if (requiredRoles.includes(userRole)) {
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
