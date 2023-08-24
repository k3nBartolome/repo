
<template>

  <div class="flex flex-wrap px-12 py-1 md:py-6 md:px-12">
    <router-link to="/dashboard_manager/request" >
      <button class="bg-orange-300 tab" v-if="isUser || isRemx || isBudget || isSourcing">Dashboard</button>
    </router-link>
    <router-link to="/inventory/supply_manager" v-if="isUser || isRemx || isBudget">
      <button class="bg-green-300 tab">Supply Manager</button>
    </router-link>
    <router-link to="/site_request_manager/request" v-if="isUser || isRemx || isBudget || isSourcing">
      <button class="bg-pink-300 tab">Site Request</button>
    </router-link>
    <!-- <router-link to="/purchase_manager/pending" v-if="isUser || isRemx || isBudget || isSourcing">
      <button class="bg-blue-300 tab">Purchase Request</button>
    </router-link> -->
    <router-link to="/award_manager/normal" v-if="isUser || isRemx || isBudget || isSourcing">
      <button class="bg-yellow-300 tab">Award Item</button>
    </router-link>
  </div>
  <main class="flex flex-col h-screen">
    <div class="flex flex-1 px-2 pt-1 md:px-1 ">
      <div class="w-full py-2 ">
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
  .tabs {
    display: flex;
  }

  .tab {
    padding: 8px 16px;
    cursor: pointer;
    border: 1px solid #ccc;
    border-bottom: none;
    border-radius: 8px 8px 0 0;
    margin-right: 4px;
  }

  .tab:last-child {
    margin-right: 0;
  }
  main {
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  .flex {
    flex: 1;
  }
</style>
