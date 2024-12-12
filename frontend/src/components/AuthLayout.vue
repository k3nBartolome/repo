<template>
  <div class="bg-white shadow-md">
    <nav class="container px-6 py-3 mx-auto md:flex md:justify-between md:items-center">

      <div class="flex items-center justify-between">
        <!-- Logo -->
        <img :src="logo" alt="VXI Logo" class="w-20 h-10">

        <!-- Mobile menu button -->
        <div @click="toggleNav" class="flex md:hidden">
          <button type="button" class="text-orange-600 hover:text-gray-400 focus:outline-none focus:text-orange-600 transition duration-300">
            <svg viewBox="0 0 24 24" class="w-6 h-6 fill-current">
              <path fill-rule="evenodd" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"></path>
            </svg>
          </button>
        </div>
      </div>

      <!-- Desktop and Mobile Menu -->
      <ul :class="showMenu ? 'flex' : 'hidden'" class="flex-col mt-6 space-y-4 md:flex md:space-y-0 md:flex-row md:items-center md:space-x-10 md:mt-0">
        
        <!-- Attendance Checker Link -->
        <router-link to="/applicant_checker">
          <li class="text-lg text-gray-800 hover:text-orange-600 transition duration-300 ease-in-out transform hover:scale-105">
            Attendance Checker
          </li>
        </router-link>

        <!-- Login Link -->
        <router-link to="/login">
          <li>
            <button class="inline-flex items-center py-2 px-4 font-semibold text-white bg-orange-500 rounded-full w-28 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-opacity-50 transition duration-300 ease-in-out">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>Login</span>
            </button>
          </li>
        </router-link>
      </ul>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="flex h-screen">
    <div class="w-full bg-white">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import logo from './storage/vxilogo.jpg';

export default {
  setup() {
    let showMenu = ref(false);
    const toggleNav = () => (showMenu.value = !showMenu.value);
    return { showMenu, toggleNav };
  },
  data() {
    return {
      logo
    };
  },
};
</script>

<style scoped>
/* Enhanced mobile menu */
@media (max-width: 768px) {
  .md\\:hidden {
    display: block;
  }

  .md\\:flex {
    display: none;
  }

  .showMenu {
    display: block;
  }
}

/* List styling */
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}

ul li {
  font-weight: 500;
  font-size: 1.125rem;
  transition: transform 0.3s ease, color 0.3s ease;
}

ul li:hover {
  transform: scale(1.05);
  color: #E85A24;
}

/* Menu button transition */
button {
  transition: transform 0.3s ease, background-color 0.3s ease;
}

button:hover {
  background-color: #E85A24;
  transform: scale(1.05);
}

/* Ensure a smooth hover effect */
button:focus, a:focus {
  outline: none;
}

/* Mobile responsive */
@media (max-width: 768px) {
  .md\\:hidden {
    display: block;
  }

  .md\\:flex {
    display: none;
  }
}

@media (min-width: 769px) {
  .md\\:hidden {
    display: none;
  }
}

button, a {
  cursor: pointer;
}

button:focus, a:focus {
  outline: none;
}

/* Smooth transition effects for hover and focus states */
a:hover, button:hover {
  transition: transform 0.3s ease, background-color 0.3s ease;
}
</style>
