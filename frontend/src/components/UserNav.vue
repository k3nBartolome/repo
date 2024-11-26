<template>
  <div>
    <nav class="bg-white">
      <div class="max-w-full mx-auto sm:px-2 lg:px-4">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <img class="w-8 h-8" src="logo.png" alt="Logo" />
            </div>
            <div class="flex-grow hidden ml-10 md:block">
              <div class="flex items-baseline justify-center space-x-4">
                <router-link
                  to="/capfile"
                  class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white link-button"
                  v-if="isUser || isSourcing"
                  >Capacity File</router-link
                >
                <router-link
                  to="/staffing"
                  class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white link-button"
                  >Staffing Tracker</router-link
                >
                <!-- <router-link
                  to="/sr_compliance"
                  class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white link-button"
                  >SR Pending Movements</router-link
                > -->
                <router-link
                  to="/h&s"
                  class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white link-button"
                  v-if="isUser"
                  >H&S Tool</router-link
                >

                <div
                  class="dropdown"
                  @mouseover="openDropdown"
                  @mouseleave="closeDropdown"
                  @click="toggleDropdown"
                >
                  <button
                    class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white"
                  >
                    Inventory Tracker
                  </button>
                  <div v-show="dropdownOpen" class="dropdown-content">
                    <router-link
                      to="/dashboard_manager/request"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >Dashboard</router-link
                    >
                    <router-link
                      to="/remx/supply_manager"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >REMX Supply</router-link
                    >
                    <router-link
                      to="/site_supply_manager/stocks"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >Sourcing Supply</router-link
                    >
                    <router-link
                      to="/site_request_manager/request"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >Request Manager</router-link
                    >
                    <router-link
                      to="/award_manager/normal"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >Release Item</router-link
                    >
                  </div>
                </div>
                <div
                  class="dropdown"
                  @mouseover="openDropdown"
                  @mouseleave="closeDropdown"
                  @click="toggleDropdown"
                >
                  <button
                    class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white"
                    v-if="isUser || isBudget || isRemx || isSourcing"
                  >
                    Sourcing
                  </button>
                  <div v-show="dropdownOpen" class="dropdown-content">
                    <router-link
                      to="/perx"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >PERX Tool</router-link
                    >
                    <router-link
                      to="/perxv2"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >PERX Tool SRv2</router-link
                    >
                    <router-link
                      to="/leads"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >Leads</router-link
                    >
                    <router-link
                      to="/applicants"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >Applicant Tool</router-link
                    >
                    <router-link
                      to="/referrals"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >Referrals</router-link
                    >
                    <router-link
                    to="/referralsv1"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                    role="menuitem"
                    >OSS Referr</router-link
                  >
                  </div>
                </div>
                <div
                class="dropdown"
                @mouseover="openDropdown"
                @mouseleave="closeDropdown"
                @click="toggleDropdown"
              >
                <button
                  class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white"
                  v-if="isUser || isSourcing"
                >
                  H&S
                </button>
                <div v-show="dropdownOpen" class="dropdown-content">

                  <router-link
                    to="/classes_information"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                    role="menuitem"
                    >Classes</router-link
                  >
                </div>
              </div>
                <div
                  class="dropdown"
                  @mouseover="openDropdown"
                  @mouseleave="closeDropdown"
                  @click="toggleDropdown"
                >
                  <button
                    class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white"
                  >
                    Admin
                  </button>
                  <div v-show="dropdownOpen" class="dropdown-content">
                    <router-link
                      to="/site_management"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >Sites</router-link
                    >
                    <router-link
                      to="/program_management"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >Programs</router-link
                    >
                  </div>
                </div>
              </div>
            </div>
            <div class="flex -mr-2 md:hidden">
              <button
                @click="toggleMobileMenu"
                class="inline-flex items-center justify-center p-2 text-gray-400 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
              >
                <span class="sr-only">Open main menu</span>
                <svg
                  class="block w-6 h-6"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  aria-hidden="true"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                </svg>
              </button>
            </div>
          </div>
          <div class="flex items-center justify-end">
            <div
              class="dropdown"
              @mouseover="openDropdown"
              @mouseleave="closeDropdown"
              @click="toggleDropdown"
            >
              <button
                class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white"
              >
                Country
              </button>
              <div v-show="dropdownOpen" class="dropdown-content">
                <router-link
                  to="/capfile"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                  role="menuitem"
                  >Philippines</router-link
                >
                <router-link
                  to="/capfileguatemala"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                  role="menuitem"
                  >Guatemala</router-link
                >
                <router-link
                  to="/capfilejamaica"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                  role="menuitem"
                  >Jamaica</router-link
                >
              </div>
            </div>
            <router-link
              to="/profile"
              class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white link-button"
              >{{ userName }}</router-link
            >
            <router-link
              to="/login"
              @click="logout"
              class="px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white link-button"
              >Logout</router-link
            >
          </div>
        </div>
      </div>
      <div
        :class="{ block: mobileMenuOpen, hidden: !mobileMenuOpen }"
        class="md:hidden"
      >
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <router-link
            to="/"
            class="block px-3 py-2 text-base font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white"
            >Home</router-link
          >
          <div class="relative" @click="toggleDropdown">
            <button
              class="block px-3 py-2 text-base font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none"
            >
              Dropdown
            </button>
            <div
              v-show="dropdownOpen"
              class="absolute right-0 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            >
              <div
                class="py-1"
                role="menu"
                aria-orientation="vertical"
                aria-labelledby="options-menu"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dropdownOpen: false,
      mobileMenuOpen: false,
    };
  },
  computed: {
    userName() {
      const userName = this.$store.state.name;
      return userName;
    },
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
    logout() {
      try {
        this.$store.dispatch("logout"); // Dispatch the logout action
      } catch (error) {
        console.error("Error during logout:", error);
      }
    },
    openDropdown() {
      if (window.innerWidth > 768) {
        this.dropdownOpen = true;
      }
    },
    closeDropdown() {
      if (window.innerWidth > 768) {
        this.dropdownOpen = false;
      }
    },
    toggleDropdown() {
      if (window.innerWidth <= 768) {
        this.dropdownOpen = !this.dropdownOpen;
      }
    },
    toggleMobileMenu() {
      this.mobileMenuOpen = !this.mobileMenuOpen;
    },
  },
};
</script>

<style>
.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 14px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {
  background-color: #ddd;
}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: #3e8e41;
}
.link-button {
  text-decoration: none;
}
</style>
