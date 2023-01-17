<template>
  <AuthLayout/>
  <div class="flex h-screen">
    <div class="w-f bg-white">
      <router-view></router-view>
    </div>
  </div>
</template>
<script>
import AuthLayout from './components/AuthLayout.vue'
export default { 
  components: {
    AuthLayout,
  },
  
}
</script>

<style>

</style>