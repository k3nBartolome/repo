<template>
  <div class="px-2 border-b border-gray-200 dark:border-gray-700">
    <ul class="flex -mb-px text-sm font-medium text-center">
    <router-link to="/award_manager/normal">
      <li class="mr-2" role="presentation" v-if="isUser || isRemx || isBudget || isSourcing">
        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 focus:outline-none" type="button" role="tab" >Released Normal Item</button>
      </li>
    </router-link>
    <router-link to="/award_manager/premium" v-if="isUser || isRemx || isBudget || isSourcing">
      <li role="presentation">
        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 focus:outline-none"  type="button" role="tab" >Released Premium Item</button>
      </li>
    </router-link>
    </ul>
  </div>
  <main class="flex flex-col h-screen">
    <div class="flex flex-1 px-4 py-2 md:px-1 ">
      <div class="w-full py-6 ">
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
  computed: {
    isUser() {
      const userRole = this.$store.state.role;
      return userRole === "user";
    },
    isRemx() {
      const userRole = this.$store.state.role;
      return userRole === "remx";
    },
    isBudget() {
      const userRole = this.$store.state.role;
      return userRole === "budget";
    },
    isSourcing() {
      const userRole = this.$store.state.role;
      return userRole === "sourcing";
    },
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
