<template>
  <div class="main-container">
    <div class="nav-container sticky-nav pb-0">
      <NavBar />
    </div>
    <div class="w-full pt-0">
      <div>
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<style>
.sticky-nav {
  position: sticky;
  top: 0;
  z-index: 100; /* Adjust the z-index as needed */
  background-color: white; /* Background color of the sticky nav */
  /* Add any other styles you want for your sticky nav here */
}

/* Add any other styles for .main-container or other elements as needed */
</style>

<script>
import NavBar from "../components/UserNav.vue";

export default {
  components: {
    NavBar,
  },
};
</script>
