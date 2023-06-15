<template>
  <div class="main-container">
    <div class="nav-container">
      <NavBar />
    </div>
    <div class="content-container ">
      <div class="scrollable-content ">
        <router-view></router-view>
      </div>
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
  padding-top: 4px;
  flex-grow: 1;
  padding-bottom: 50px;
  height: 850px;
}

.scrollable-content {
  overflow-x: auto;
  overflow-y: auto;
  height: 100%;
}

.scrollable-content::-webkit-scrollbar {
  height: 20px;
  background-color: #f5f5f5;
}

.scrollable-content::-webkit-scrollbar-thumb {
  background-color: #888;
  border-radius: 5px;
}

.scrollable-content::-webkit-scrollbar-thumb:hover {
  background-color: #555;
}

.footer-container {
  position: fixed;
  bottom: 0;
  width: 100%;
  padding-top: 20px;
  z-index: 1;
}
</style>

<script>
//import FootBar from "../components/FooterLayout.vue";
import NavBar from "../components/UserNav.vue";
export default {
  components: {
    NavBar,
    //FootBar,
  },
};
</script>
