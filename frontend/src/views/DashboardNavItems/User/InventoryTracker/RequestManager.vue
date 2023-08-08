<template>
  <div class="pt-1 px-2 border-b border-gray-200 dark:border-gray-700">
    <ul class="flex -mb-px text-sm font-medium text-center">
      <router-link to="/request_manager/pending">
      <li class="mr-2" role="presentation">
        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 focus:outline-none" type="button" role="tab" >Pending Request</button>
      </li>
    </router-link>
    <router-link to="/request_manager/approved">
      <li class="mr-2" role="presentation">
        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 focus:outline-none" type="button" role="tab" >Approved Request</button>
      </li>
    </router-link>
    <router-link to="/request_manager/denied">
      <li role="presentation">
        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 focus:outline-none"  type="button" role="tab" >Denied Request</button>
      </li>
    </router-link>
    </ul>
  </div>
  <main class="flex flex-col h-screen">
    <div class="flex flex-1 px-4 py-2 md:px-1 ">
      <div class=" py-6 w-full">
        <router-view />
      </div>
    </div>
  </main>
</template>
<script>
export default {
  mounted() {
    this.$router.afterEach(() => {
      window.location.reload();
    });
  },
};
</script>
<style>
  main {
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  .selected-tab {
    border-color: #6366f1;
    color: #6366f1;
    font-weight: bold;
  }

</style>