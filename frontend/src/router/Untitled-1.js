import pushbackCapacityFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/PushedBackCapacityFileIndia.vue";
import cancelCapacityFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/CancelCapacityFileIndia.vue";
import editCapFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/EditCapfileIndia.vue";
import pushbackCapacityFileJamaica from "@/views/DashboardNavItems/User/CapfileJamaica/PushedBackCapacityFileJamaica.vue";
import cancelCapacityFileJamaica from "@/views/DashboardNavItems/User/CapfileJamaica/CancelCapacityFileJamaica.vue";
import editCapFileJamaica from "@/views/DashboardNavItems/User/CapfileJamaica/EditCapfileJamaica.vue";
import pushbackCapacityFileGuatemala from "@/views/DashboardNavItems/User/CapfileGuatemala/PushedBackCapacityFileGuatemala.vue";
import cancelCapacityFileGuatemala from "@/views/DashboardNavItems/User/CapfileGuatemala/CancelCapacityFileGuatemala.vue";
import editCapFileGuatemala from "@/views/DashboardNavItems/User/CapfileGuatemala/EditCapfileGuatemala.vue";

import capacityFileIndia from "@/views/DashboardNavItems/User/CapacityFileIndia.vue";
import capacityFileJamaica from "@/views/DashboardNavItems/User/CapacityFileJamaica.vue";
import capacityFileGuatemala from "@/views/DashboardNavItems/User/CapacityFileGuatemala.vue";
import addCapacityFileIndia from "@/views/DashboardNavItems/User/CapfileIndia/AddCapfileIndia.vue";
import addCapacityFileJamaica from "@/views/DashboardNavItems/User/CapfileJamaica/AddCapfileJamaica.vue";
import addCapacityFileGuatemala from "@/views/DashboardNavItems/User/CapfileGuatemala/AddCapfileGuatemala.vue";
import SiteManagementIndia from "@/views//Dashboard/AppSiteDashboardIndia.vue";
import SiteManagementJamaica from "@/views//Dashboard/AppSiteDashboardJamaica.vue";
import SiteManagementGuatemala from "@/views//Dashboard/AppSiteDashboardGuatemala.vue";
import AppUserLayoutIndia from "@/components/AppUserLayoutIndia";
import AppUserLayoutJamaica from "@/components/AppUserLayoutJamaica";
import AppUserLayoutGuatemala from "@/components/AppUserLayoutGuatemala";
import programManagementEditIndia from "@/views/DashboardNavItems/Admin/EditProgramIndia";
import siteManagementEditIndia from "@/views/DashboardNavItems/Admin/EditSiteIndia";
import programManagementEditJamaica from "@/views/DashboardNavItems/Admin/EditProgramJamaica";
import siteManagementEditJamaica from "@/views/DashboardNavItems/Admin/EditSiteJamaica";
import programManagementEditGuatemala from "@/views/DashboardNavItems/Admin/EditProgramGuatemala";
import siteManagementEditGuatemala from "@/views/DashboardNavItems/Admin/EditSiteGuatemala";
import ProgramManagementIndia from "@/views/Dashboard/AppProgramDashboardIndia.vue";
import ProgramManagementJamaica from "@/views/Dashboard/AppProgramDashboardJamaica.vue";
import ProgramManagementGuatemala from "@/views/Dashboard/AppProgramDashboardGuatemala.vue";
{path: "/",
    component: AppUserLayoutGuatemala,
    meta: {
      requiresAuth: true,
      requiresRole: "user"
    },
    children: [{
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
        path: "/staffing_report",
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
    meta: {
      requiresAuth: true,
      requiresRole: "user"
    },
    children: [{
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
        path: "/staffing_report",
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
        path: "/staffing_report",
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