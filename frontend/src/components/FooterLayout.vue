<template>
  <footer class="w-full p-4 text-white bg-orange-500">
    <div class="container flex items-center justify-between mx-auto">
      <p class="text-sm">© {{ currentYear }} VXI Global Holdings B.V., All rights reserved.</p>
      <div class="flex">
        <p class="mr-4 text-sm">Powered by: Global Recruitment Marketing</p>
      </div>
    </div>
  </footer></template>
<script>
export default {
  data() {
    return {
      currentYear: new Date().getFullYear()
    }
  },
};
</script>