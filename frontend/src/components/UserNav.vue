<template>
  <div class="nav-container sticky top-0 z-50 bg-white">
    <nav class="container px-6 py-0 mx-auto md:flex md:justify-between md:items-center">
      <div class="flex items-center justify-between">
        <img :src="logo" alt="VXI Logo" class="w-20 h-10" />
        <div @click="toggleNav" class="flex md:hidden">
          <button
            type="button"
            class="text-orange-600 hover:text-gray-400 focus:outline-none focus:text-orange-600"
          >
            <svg viewBox="0 0 24 24" class="w-6 h-6 fill-current">
              <path
                fill-rule="evenodd"
                d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"
              ></path>
            </svg>
          </button>
        </div>
      </div>
      <ul
        :class="showMenu ? 'flex' : 'hidden'"
        class="flex-col mt-8 space-y-4 font-bold md:flex md:space-y-0 md:flex-row md:items-center md:space-x-10 md:mt-0"
      >
        <router-link to="/capfile" class="link-button">
          <li class="tab-button"
              :class="{ 'selected-tab': isActiveTab('/capfile') }"
            v-if="isUser"
                      >
            Capacity File
          </li>
        </router-link>
        <router-link to="/staffing" class="link-button">
          <li class="tab-button"
              :class="{ 'selected-tab': isActiveTab('/staffing') }"
            v-if="isUser"
                      >
            Staffing Tracker
          </li>
        </router-link>

        <li class="tab-button">
          <div v-if="isUser|| isBudget || isSourcing">
            <div class="relative inline-block py-0">
              <button
                @click="toggleDropdown5"
                class="px-4 py-2 text-black rounded cursor-pointer"
              >
              Budget
              </button>
              <div
                v-show="isDropdown5Open"
                class="absolute z-10 py-2 truncate bg-white rounded shadow-md"
              >
              <router-link to="/perx" class="link-button">
                <li class="tab-button"
                    :class="{ 'selected-tab': isActiveTab('/perx') }"
                  v-if="isUser|| isBudget || isSourcing"
                            >
                  PERX Tool
                </li>
              </router-link>
              </div>
            </div>
          </div>
        </li>
        <li class="tab-button">
          <div v-if="isUser|| isBudget">
            <div class="relative inline-block py-0">
              <button
                @click="toggleDropdown4"
                class="px-4 py-2 text-black rounded cursor-pointer"
              >
              Inventory Tracker
              </button>
              <div
                v-show="isDropdown4Open"
                class="absolute z-10 py-2 truncate bg-white rounded shadow-md"
              >
              <router-link to="/dashboard_manager/request" class="link-button">
                  <li class="tab-button"  v-if="isUser || isRemx || isBudget || isSourcing"
                :class="{ 'selected-tab': isActiveTab('//dashboard_manager/request') }"
                                    >
                    Dashboard
                  </li>
                </router-link>
                <router-link to="/inventory/supply_manager" class="link-button">
                  <li class="tab-button"  v-if="isUser || isRemx || isBudget"
                :class="{ 'selected-tab': isActiveTab('/inventory/supply_manager') }"
                                    >
                    Supply Manager
                  </li>
                </router-link>
                <router-link to="/site_supply_manager/stocks" class="link-button">
                  <li class="tab-button"  v-if="isUser || isRemx || isBudget"
                :class="{ 'selected-tab': isActiveTab('/inventory/site_supply_manager') }"
                                    >
                    Site Supply Manager
                  </li>
                </router-link>
                <router-link to="/site_request_manager/request" class="link-button">
                  <li class="tab-button"  v-if="isUser || isRemx || isBudget || isSourcing"
                :class="{ 'selected-tab': isActiveTab('/site_request_manager/request') }"
                                    >
                    Site Request
                  </li>
                </router-link>
                <router-link to="/award_manager/normal" class="link-button">
                  <li class="tab-button"  v-if="isUser || isRemx || isBudget || isSourcing"
                :class="{ 'selected-tab': isActiveTab('/award_manager/normal') }"
                                    >
                    Release Item
                  </li>
                </router-link>
              </div>
            </div>
          </div>
        </li>
        <li class="tab-button">
        <div v-if="isUser">
          <div class="relative inline-block">
            <button
              @click="toggleDropdown3"
              class="px-4 py-2 text-black rounded cursor-pointer"
            >
              Reports
            </button>
            <div
              v-show="isDropdown3Open"
              class="absolute z-10 py-2 truncate bg-white rounded shadow-md"
            >
              <router-link to="/staffing_report" class="link-button">
                <li class="tab-button"
              :class="{ 'selected-tab': isActiveTab('/staffing_report') }"
                                  >
                  Staffing Tracker
                </li>
              </router-link>
              <router-link to="/capfile/summary" class="link-button">
                <li class="tab-button"
              :class="{ 'selected-tab': isActiveTab('/capfile/summary') }"
                                  >
                  Capacity File
                </li>
              </router-link>
            </div>
          </div>
        </div>
      </li>
      <li class="tab-button">
        <div v-if="isUser">
          <div class="relative inline-block">
            <button
              @click="toggleDropdown"
              class="px-4 py-2 text-black rounded cursor-pointer"
            >
              Admin
            </button>
            <div
              v-show="isDropdownOpen"
              class="absolute z-10 py-2 bg-white rounded shadow-md"
            >
              <router-link to="/site_management" class="link-button">
                <li class="tab-button"
              :class="{ 'selected-tab': isActiveTab('/site_management') }"
                                  >
                  Sites
                </li>
              </router-link>
              <router-link to="/program_management" class="link-button">
                <li class="tab-button"
              :class="{ 'selected-tab': isActiveTab('/program_management') }"
                                  >
                  Programs
                </li>
              </router-link>
            </div>
          </div>
        </div>
      </li>
      </ul>
      <ul class="flex-col mt-8 space-y-4 font-bold md:flex md:space-y-0 md:flex-row md:items-center md:space-x-10 md:mt-0">
        <div class="py-4">
          <select
            v-model="selectedOption"
            @change="navigateToPage"
            class="border-gray-300 rounded focus:ring focus:ring-indigo-200 focus:border-indigo-500"
          >
            <option value="/capfileindia" class="flex items-center">IND</option>
            <option value="/capfile" class="flex items-center">PH</option>
            <option value="/capfilejamaica" class="flex items-center">JAM</option>
            <option value="/capfileguatemala" class="flex items-center">GUA</option>
          </select>
          <div class="relative inline-block" >
            <button
              @click="toggleDropdown2"
              class="px-4 py-2 font-bold text-black rounded cursor-pointer"
            >
              <i class="material-icons" style="font-size: 48px; color: red;">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  class="w-6 h-6"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                </svg>
              </i>
            </button>
            <div
              v-show="isDropdown2Open"
              class="absolute z-10 py-6 bg-orange-500 border border-2 border-orange-500 rounded shadow-md px-14"
            >
              <router-link to="/login" class="link-button">
                <li class="font-bold text-white truncate hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600">
                  <i class="bg-red-600 fa fa-sign-out"></i> Logout
                </li>
              </router-link>
            </div>
          </div>
        </div>
      </ul>
    </nav>
  </div>
</template>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script>
import { ref } from "vue";
import logo from "./storage/vxilogo.jpg";

export default {
  setup() {
    let showMenu = ref(false);
    const toggleNav = () => (showMenu.value = !showMenu.value);
    return { showMenu, toggleNav };
  },
  data() {
    return {
      logo,
      selectedOption: "/capfile",
      isDropdownOpen: false,
      isDropdown2Open: false,
      isDropdown3Open: false,
      isDropdown4Open: false,
      isDropdown5Open: false,
    };
  },
  computed: {
    isUser() {
      const userRole = this.$store.state.role;
      return userRole === "user";
    },
    isRemx() {
      const userRole = this.$store.state.role;
      return userRole === "remx";
    },
    isBudget() {
      const userRole = this.$store.state.role;
      return userRole === "budget";
    },
    isSourcing() {
      const userRole = this.$store.state.role;
      return userRole === "sourcing";
    },
  },
  methods: {
    isActiveTab(route) {
      return this.$route.path === route;
    },
    toggleDropdown() {
      this.isDropdownOpen = !this.isDropdownOpen;
    },
    toggleDropdown2() {
      this.isDropdown2Open = !this.isDropdown2Open;
    },
    toggleDropdown3() {
      this.isDropdown3Open = !this.isDropdown3Open;
    },
    toggleDropdown4() {
      this.isDropdown4Open = !this.isDropdown4Open;
    },
    toggleDropdown5() {
      this.isDropdown5Open = !this.isDropdown5Open;
    },
    navigateToPage() {
      this.$router.push(this.selectedOption);
    },
  },
};
</script>
<style>
.sticky {
  position: sticky;
  top: 0;
  background-color: white;
  z-index: 50; /* Adjust the z-index as needed */
}
* {
  margin: 0;
  padding: 0;
  border: 0;
  outline: 0;
  font-size: 100%;
  vertical-align: baseline;
  background: transparent;
}
main {
  display: flex;
  flex-direction: column;
  height: 100%;
}
.selected-tab {
  border-color: #6366f1;
  color: #6366f1;
  font-weight: bold;
}
.tab-button {
  display: block;
  width: 100%;
  padding: 0;
  border: none;
  background-color: transparent;
  border-bottom: 2px solid transparent;
  transition: border-color 0.3s, color 0.3s;
  text-align: center;
  color: black;
  text-decoration: none !important;
}
.tab-button li {
  margin-top: 0px; /* Adjust as needed */
  margin-bottom: 0px; /* Adjust as needed */
}
.tab-button:hover {
  color: #6366f1;
}
li.tab-button > div > div > ul > li {
  margin-top: 0px; /* Adjust as needed */
  margin-bottom: 0px; /* Adjust as needed */
}
@media (min-width: 576px) {
  .tab-button {
    padding: 0;
  }
}
@media (min-width: 768px) {
  .tab-button {
    padding: 0;
  }
}
.link-button {
  text-decoration: none;
}
</style>
