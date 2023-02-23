<template>
  <div class="main-container">
    <div class="nav-container">
      <NavBar />
    </div>
    <div class="content-container">
      <router-view></router-view>
    </div>
    <div class="footer-container">
      <FootBar />
    </div>
  </div>
</template>

<style>
.main-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.content-container {
  flex-grow: 1; 
  padding-bottom: 50px; 
}

.footer-container {
  position: fixed;
  bottom: 0;
  width: 100%;
  padding-top: 20px; 
}
</style>

<script>
import FootBar from "../components/FooterLayout.vue";
import NavBar from "../components/AdminNav.vue";
export default {
  components: {
    NavBar,
    FootBar,
  },
};
</script>
