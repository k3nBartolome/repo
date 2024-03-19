<template>
  <div>
    <nav class="bg-white">
      <div class="max-w-full mx-auto sm:px-2 lg:px-4">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <img class="h-8 w-8" src="logo.png" alt="Logo">
            </div>
            <div class="hidden md:block ml-10 flex-grow">
              <div class="flex items-baseline space-x-4 justify-center">
                <router-link to="/capfile" class=" hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium link-button"  v-if="isUser">Capacity File</router-link>
                <router-link to="/staffing" class=" hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium link-button"  v-if="isUser">Staffing Tracker</router-link>
                <router-link to="/sr_compliance" class=" hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium link-button">SR Pending Movemnet</router-link>
                <div class="dropdown" @mouseover="openDropdown" @mouseleave="closeDropdown" @click="toggleDropdown">
                  <button class=" hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Inventory Tracker</button>
                  <div v-show="dropdownOpen" class="dropdown-content">
                    <router-link to="/dashboard_manager/request" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Dashboard</router-link>
                    <router-link to="/inventory/supply_manager" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Supply Manager</router-link>
                    <router-link to="/site_supply_manager/stocks" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Site Supply Manager</router-link>
                    <router-link to="/site_request_manager/request" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Request Manager</router-link>
                    <router-link to="/award_manager/normal" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Release Item</router-link>                  </div>
                </div>
                <div class="dropdown" @mouseover="openDropdown" @mouseleave="closeDropdown" @click="toggleDropdown">
                  <button class=" hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium" v-if="isUser|| isBudget || isSourcing">Budget</button>
                  <div v-show="dropdownOpen" class="dropdown-content">
                    <router-link to="/perx" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">PERX Tool</router-link>
                    
                  </div>
                </div>
                <div class="dropdown" @mouseover="openDropdown" @mouseleave="closeDropdown" @click="toggleDropdown">
                  <button class=" hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Admin</button>
                  <div v-show="dropdownOpen" class="dropdown-content">
                    <router-link to="/site_management" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Sites</router-link>
                    <router-link to="/program_management" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Programs</router-link>
                  </div>
                </div>
              </div>
            </div>
            <div class="-mr-2 flex md:hidden">
              <button @click="toggleMobileMenu" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                <span class="sr-only">Open main menu</span>
                <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
            </div>
          </div>
          <div class="flex items-center justify-end">
            
            <div class="dropdown" @mouseover="openDropdown" @mouseleave="closeDropdown" @click="toggleDropdown">
              <button class=" hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Country</button>
              <div v-show="dropdownOpen" class="dropdown-content">
                <router-link to="/capfile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Philippines</router-link>
            <router-link to="/capfileguatemala" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Guatemala</router-link>
            <router-link to="/capfilejamaica" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Jamaica</router-link>
              </div>
            </div>
            <router-link to="/profile" class=" hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium link-button">{{ userName }}</router-link>
            <router-link to="/login" @click="logout" class=" hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium link-button">Logout</router-link>
          </div>
        </div>
      </div>
      <div :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <router-link to="/" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Home</router-link>
          <div class="relative" @click="toggleDropdown">
            <button class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium focus:outline-none">Dropdown</button>
            <div v-show="dropdownOpen" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
              <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                <router-link to="/about" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">About</router-link>
                <router-link to="/services" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Services</router-link>
                <router-link to="/contact" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Contact</router-link>
              </div>
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
      mobileMenuOpen: false
    }
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
        this.$store.dispatch('logout');  // Dispatch the logout action
      } catch (error) {
        console.error('Error during logout:', error);
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
    }
  }
}
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
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #ddd;}

.dropdown:hover .dropdown-content {display: block;}

.dropdown:hover .dropbtn {background-color: #3e8e41;}
.link-button {
  text-decoration: none;
}
</style>
