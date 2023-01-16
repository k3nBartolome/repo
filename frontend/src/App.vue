<template>
  <AuthLayout/>
  <div class="flex h-screen">
    <div class="w-1/2 bg-orange-200">
      
    </div>
    <div class="w-1/2 bg-white">
      <router-view></router-view>
    </div>
  </div>`
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