import pushbackCapacityFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/PushedBackCapacityFileIndia.vue";
import programManagementEditIndia from "@/views/DashboardNavItems/Admin/EditProgramIndia";
import siteManagementEditIndia from "@/views/DashboardNavItems/Admin/EditSiteIndia";
import ProgramManagementIndia from "@/views/Dashboard/AppProgramDashboardIndia.vue";
import SiteManagementIndia from "@/views//Dashboard/AppSiteDashboardIndia.vue";
import addCapacityFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/AddCapfileIndia.vue";
import AppUserLayoutIndia from "@/components/AppUserLayoutIndia";
import capacityFileIndia from "@/views/DashboardNavItems/User/CapacityFileIndia.vue";
import cancelCapacityFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/CancelCapacityFileIndia.vue";
import editCapFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/EditCapfileIndia.vue";


  {
    path: "/",
    component: AppUserLayoutIndia,
    meta: {
      requiresAuth: true,
      requiresRole: "user"
    },
    children: [{
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
