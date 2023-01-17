<template>
  <div class="bg-white">
    <nav class="container px-6 py-2 mx-auto md:flex md:justify-between md:items-center">

      <div class="flex items-center justify-between">
        <img :src="logo" alt="VXI Logo" class="w-20 h-10">
        <!-- Mobile menu button -->
        <div @click="toggleNav" class="flex md:hidden">
          <button type="button" class="text-orange-600 hover:text-gray-400 focus:outline-none focus:text-orange-600">
            <svg viewBox="0 0 24 24" class="w-6 h-6 fill-current">
              <path fill-rule="evenodd"
                d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z">
              </path>
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
      <ul :class="showMenu ? 'flex' : 'hidden'"
        class="flex-col mt-8 space-y-4 md:flex md:space-y-0 md:flex-row md:items-center font-bold md:space-x-10 md:mt-0">
        <li class="text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600">Dashboard</li>
        <li class="text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600">Sourcing</li>
        <li class="text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600">Hiring and
          Selection</li>
        <li class="text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600">ReMX</li>
        <li class="text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600">Corporate
          Recruiting</li>
        <li class="text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600">Reports</li>
        <li class="text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600">Budget</li>
        <li class="text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600">Onboarding</li>
        <li class="text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600">Admin</li>
      </ul>
    </nav>
  </div>

  <div class="flex h-screen">
    <div class="w-full bg-white">
      <router-view :key="$route.path"></router-view>
    </div>
  </div>
  <footer class="bg-orange-600 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
      <p class="text-sm">© {{ currentYear }} VXI Global Holdings B.V., All rights reserved.</p>
      <div class="flex">
        <p class="text-sm mr-4">Powered by: Global Recruitment Marketing</p>
      </div>
    </div>
  </footer>
</template>
<script>
import { ref } from 'vue';
import logo from './storage/vxilogo.jpg'
export default {
  setup() {
    let showMenu = ref(false);
    const toggleNav = () => (showMenu.value = !showMenu.value);
    return { showMenu, toggleNav };
  },
  data() {
    return {
      logo,
      currentYear: new Date().getFullYear()
    }
  },
};
</script>