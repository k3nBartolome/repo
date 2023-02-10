<template>
  <div class="w-full">
    <NavBar />
  </div>
  <div class="w-auto h-full">
    <router-view></router-view>
  </div>
  <div class="w-full">
    <FootBar />
  </div>
</template>
<script>
import FootBar from "../components/FooterLayout.vue";
import NavBar from "../components/AdminNav.vue";
export default {
  components: {
    NavBar,
    FootBar
  }
}
</script>
