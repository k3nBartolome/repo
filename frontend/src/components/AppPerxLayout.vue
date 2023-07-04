<template>
  <div class="main-container">
    <div class="nav-container">
      <NavBar />
    </div>
    <div>
      <div>
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<style>
</style>

<script>
//import FootBar from "../components/FooterLayout.vue";
import NavBar from "../components/PerxNav.vue";
export default {
  components: {
    NavBar,
    //FootBar,
  },
};
</script>
