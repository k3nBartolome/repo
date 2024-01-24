import { createRouter, createWebHashHistory } from "vue-router";
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
import userProfile from "@/views/DashboardNavItems/Admin/UserProfile.vue";
import programManagementEdit from "@/views/DashboardNavItems/Admin/EditProgram";
import siteManagementEdit from "@/views/DashboardNavItems/Admin/EditSite";
import SiteManagement from "@/views//Dashboard/AppSiteDashboard.vue";
import WebRockData from "@/views//Dashboard/WebRockData.vue";
import capacityFile from "@/views/DashboardNavItems/User/CapacityFile.vue";
import capacityFileReport from "@/views/DashboardNavItems/User/CapacityFileReport.vue";
import CapFileHiring from "@/views/DashboardNavItems/User/CapacityFileDashboard/CapFileHiring.vue";
import CapFileClassHistory from "@/views/DashboardNavItems/User/CapacityFileDashboard/CapFileClassHistory";
import CapFilePushedback from "@/views/DashboardNavItems/User/CapacityFileDashboard/CapFilePushedback";
import CapFileCancelled from "@/views/DashboardNavItems/User/CapacityFileDashboard/CapFileCancelled";
import addCapacityFile from "@/views/DashboardNavItems/User/Capfile/AddCapfile.vue";
import ProgramManagement from "@/views/Dashboard/AppProgramDashboard.vue";
import pushbackCapacityFile from "@/views/DashboardNavItems/User/Capfile/PushedBackCapacityFile.vue";
import cancelCapacityFile from "@/views/DashboardNavItems/User/Capfile/CancelCapacityFile.vue";
import editCapFile from "@/views/DashboardNavItems/User/Capfile/EditCapfile.vue";
import StaffingTrackerReport from "@/views/DashboardNavItems/User/StaffingTrackerReport.vue";
import StaffingTracker from "@/views/DashboardNavItems/User/StaffingTracker.vue";
import AddStaffingTracker from "@/views/DashboardNavItems/User/AddStaffingTracker.vue";
import UpdateStaffingTracker from "@/views/DashboardNavItems/User/UpdateStaffingTracker.vue";
import inventoryTracker from "@/views/DashboardNavItems/User/InventoryTracker.vue";
import supplyManager from "@/views/DashboardNavItems/User/InventoryTracker/SupplyManager.vue";
import siteSupplyManager from "@/views/DashboardNavItems/User/InventoryTracker/SiteSupplyManager.vue";
import siteSupplyStock from "@/views/DashboardNavItems/User/InventoryTracker/SiteSupplyStock.vue";
import siteTransfer from "@/views/DashboardNavItems/User/InventoryTracker/SiteTransferRequest.vue";
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
import staffingTrackerMonthDashboard from "@/views/DashboardNavItems/User/StaffingTrackerDashboard/StaffingTrackerMonthDashboard.vue";
import staffingTrackerSiteDashboard from "@/views/DashboardNavItems/User/StaffingTrackerDashboard/StaffingTrackerSiteDashboard.vue";
import staffingTrackerWeekDashboard from "@/views/DashboardNavItems/User/StaffingTrackerDashboard/StaffingTrackerWeekDashboard.vue";
import perxAuditTools from "@/views/DashboardNavItems/User/PerxAuditTools.vue";
import SrFilterTool from "@/views/DashboardNavItems/User/SrFilterTool.vue";
import SrManager from "@/views/DashboardNavItems/User/SrManager.vue";

const routes = [
  {
    path: "/",
    component: AppUserLayout,
    meta: {
      requiresAuth: true,
      requiresRoles: ["user", "remx", "sourcing", "budget"],
    },
    children: [

      {
        path: "/profile",
        name: "userProfile",
        component: userProfile,
      },
      {
        path: "/perx",
        name: "perxAuditTools",
        component: perxAuditTools,
      },
      
      {
        path: "/user_management",
        name: "usermanagement",
        component: UserManagement,
      },
      {
        path: "/",
        name: "SrManager",
        component: SrManager,
        children: [
          {
            path: "/sr_filter",
            name: "SrFilterTool",
            component: SrFilterTool,
          },
          {
            path: "/sr_compliance",
            name: "WebRockData",
            component: WebRockData,
          },
        ],
      },
      {
        path: "/inventory",
        name: "inventoryTracker",
        component: inventoryTracker,
        children: [
          {
            path: "supply_manager",
            name: "supplyManager",
            component: supplyManager,
          },
          {
            path: "/dashboard_manager",
            name: "dashboardManager",
            component: dashboardManager,
            children: [
              {
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
            path: "/site_supply_manager",
            name: "siteSupplyManager",
            component: siteSupplyManager,
            children: [
              {
                path: "stocks",
                name: "siteSupplyStock",
                component: siteSupplyStock,
              },
              {
                path: "transfer",
                name: "siteTransfer",
                component: siteTransfer,
              },
            ],
          },
          {
            path: "/site_request_manager",
            name: "siteRequestManager",
            component: siteRequestManager,
            children: [
              {
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
            path: "/award_manager",
            name: "awardManager",
            component: awardManager,
            children: [
              {
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
        name: "StaffingTrackerReport",
        component: StaffingTrackerReport,
        children: [
          {
            path: "site",
            name: "staffingTrackerSiteDashboard",
            component: staffingTrackerSiteDashboard,
          },
          {
            path: "week",
            name: "staffingTrackerWeekDashboard",
            component: staffingTrackerWeekDashboard,
          },
          {
            path: "month",
            name: "staffingTrackerMonthDashboard",
            component: staffingTrackerMonthDashboard,
          },
          {
            path: "/staffing",
            name: "StaffingTracker",
            component: StaffingTracker,
          },
        ],
      },
      {
        path: "/capfile",
        name: "capacityFileReport",
        component: capacityFileReport,
        children: [
          {
            path: "history",
            name: "CapFileClassHistory",
            component: CapFileClassHistory,
          },
          {
            path: "summary",
            name: "CapFileHiring",
            component: CapFileHiring,
          },
          {
            path: "cancelled",
            name: "CapFileCancelled",
            component: CapFileCancelled,
          },
          {
            path: "moved",
            name: "CapFilePushedback",
            component: CapFilePushedback,
          },
          {
            path: "/capfile",
            name: "capacityFile",
            component: capacityFile,
          },
        ],
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
