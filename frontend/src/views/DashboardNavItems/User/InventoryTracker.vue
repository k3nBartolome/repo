
<template>

  <div class="flex flex-wrap px-12 py-1 md:py-6 md:px-12">
    <router-link to="/dashboard_manager/request" >
      <button class="bg-orange-300 tab" v-if="isUser || isRemx || isBudget || isSourcing">Dashboard</button>
    </router-link>
    <router-link to="/inventory/supply_manager" v-if="isUser || isRemx || isBudget">
      <button class="bg-green-300 tab">Supply Manager</button>
    </router-link>
    <router-link to="/site_request_manager/request" v-if="isUser || isRemx || isBudget || isSourcing">
      <button class="bg-pink-300 tab">
        Site Request
        <span v-if="Total > 0" class="count-notification">{{ Total }}</span>
      </button>
    </router-link>


    <router-link to="/award_manager/normal" v-if="isUser || isRemx || isBudget || isSourcing">
      <button class="bg-yellow-300 tab">Release Item</button>
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
import axios from "axios";
export default {
   data() {
    return {
      inventory: [],
      totalPending: "",
      totalReceived: "",
      Total:""
    };
  },
 mounted() {
    this.$router.afterEach(() => {
      window.location.reload();
    });
    this.getInventory();
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
methods: {
  async getInventory() {
    try {
      const token = this.$store.state.token;
      const response = await axios.get(
        "http://10.109.2.112:8081/api/inventoryall",
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      if (response.status === 200) {
        this.inventory = response.data.inventory;

        const pendingItems = this.inventory.filter(item => item.status === "Pending");
        const receivedItems = this.inventory.filter(item => item.status === "Approved" && item.approved_status === null);

        this.totalPending = pendingItems.length;
        this.totalReceived = receivedItems.length;
        this.Total = this.totalPending + this.totalReceived;
      } else {
        console.log("Error fetching inventory");
      }
    } catch (error) {
      console.log(error);
    }
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
  .count-notification {
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    margin-left: 5px;
  }

</style>
