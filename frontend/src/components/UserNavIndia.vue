<template>
  <div class="bg-white">
    <nav class="container px-6 py-2 mx-auto md:flex md:justify-between md:items-center">
      <div class="flex items-center justify-between">
        <img :src="logo" alt="VXI Logo" class="w-20 h-10" />
        <!-- Mobile menu button -->
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
      <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
      <ul
        :class="showMenu ? 'flex' : 'hidden'"
        class="flex-col mt-8 space-y-4 font-bold md:flex md:space-y-0 md:flex-row md:items-center md:space-x-10 md:mt-0"
      >
              <router-link to="/capfileindia"
                ><li
                  class="text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600"
                >
                  Capacity File India
                </li></router-link
              >
        <router-link to="/staffing">
          <li
            class="text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600"
          >
            Staffing Tracker
          </li></router-link
        >
        <router-link to="/powerbi"
          ><li
            class="text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600"
          >
            Power BI Report
          </li></router-link
        >
        <div class="py-4">
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
              <router-link to="/site_managementindia"
                ><li
                  class="text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600"
                >
                  Sites
                </li></router-link
              >
              <router-link to="/program_managementindia"
                ><li
                  class="text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600"
                >
                  Programs
                </li></router-link
              >
            </div>
          </div>
        </div>
      </ul>
      <ul class="flex-col mt-8 space-y-4 font-bold md:flex md:space-y-0 md:flex-row md:items-center md:space-x-10 md:mt-0">
        <div class="py-4">
          <select v-model="selectedOption" @change="navigateToPage" class="rounded border-gray-300 focus:ring focus:ring-indigo-200 focus:border-indigo-500">
            <option value="/capfileindia" class="flex items-center">IND</option>
            <option value="/capfile" class="flex items-center">PH</option>
          </select>
          <div class="relative inline-block">
            <button @click="toggleDropdown2" class="px-4 py-2 font-bold text-black rounded cursor-pointer">
              <i class="material-icons" style="font-size: 48px; color: red;"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg></i>
            </button>
            <div v-show="isDropdown2Open" class="absolute z-10 py-6 bg-orange-500 border border-2 border-orange-500 rounded shadow-md px-14">
              <router-link to="/site_management">
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
      selectedOption: '/capfileindia',
      isDropdownOpen: false,
      isDropdown2Open: false,

    };
  },
  methods: {
    toggleDropdown() {
      this.isDropdownOpen = !this.isDropdownOpen;
    },
    toggleDropdown2() {
      this.isDropdown2Open = !this.isDropdown2Open;
    },
    navigateToPage() {
      this.$router.push(this.selectedOption);
    }
  },
};
</script>
<style>
button:focus {
  outline: none;
}

</style>
