<template>
  <div class="px-2 pt-1 border-b border-gray-200 dark:border-gray-700">
    <ul class="flex -mb-px text-sm font-medium text-center tabs">
      <router-link to="/site_request_manager/request">
        <li
          class="tab mr-2"
          role="presentation"
          v-if="isUser || isRemx || isBudget || isSourcing"
        >
          <button
            class="tab-button"
            :class="{
              'selected-tab': isActiveTab('/site_request_manager/request'),
            }"
            type="button"
            role="tab"
          >
            Request
          </button>
        </li>
      </router-link>
      <router-link to="/site_request_manager/pending">
        <li
          class="tab mr-2"
          role="presentation"
          v-if="isUser || isRemx || isBudget || isSourcing"
        >
          <button
            class="tab-button"
            :class="{
              'selected-tab': isActiveTab('/site_request_manager/pending'),
            }"
            type="button"
            role="tab"
          >
            Pending Request
            <span v-if="totalPending > 0" class="count-notification">{{
              totalPending
            }}</span>
          </button>
        </li>
      </router-link>
      <router-link to="/site_request_manager/approved">
        <li
          class="tab mr-2"
          role="presentation"
          v-if="isUser || isRemx || isBudget || isSourcing"
        >
          <button
            class="tab-button"
            :class="{
              'selected-tab': isActiveTab('/site_request_manager/approved'),
            }"
            type="button"
            role="tab"
          >
            Approved Request
          </button>
        </li>
      </router-link>
      <router-link
        to="/site_request_manager/denied"
        v-if="isUser || isRemx || isBudget || isSourcing"
      >
        <li class="tab" role="presentation">
          <button
            class="tab-button"
            :class="{
              'selected-tab': isActiveTab('/site_request_manager/denied'),
            }"
            type="button"
            role="tab"
          >
            Denied Request
          </button>
        </li>
      </router-link>
      <router-link
        to="/site_request_manager/received"
        v-if="isUser || isRemx || isBudget || isSourcing"
      >
        <li class="tab" role="presentation">
          <button
            class="tab-button"
            :class="{
              'selected-tab': isActiveTab('/site_request_manager/received'),
            }"
            type="button"
            role="tab"
          >
            Receive
            <span v-if="totalReceived > 0" class="count-notification">{{
              totalReceived
            }}</span>
          </button>
        </li>
      </router-link>
      <router-link to="/site_request_manager/cancelled">
        <li class="tab" role="presentation">
          <button
            class="tab-button"
            :class="{
              'selected-tab': isActiveTab('/site_request_manager/cancelled'),
            }"
            type="button"
            role="tab"
          >
            Cancelled Request
          </button>
        </li>
      </router-link>
    </ul>
  </div>
  <main class="flex flex-col h-screen">
    <div class="flex flex-1 px-4 py-2 md:px-1">
      <div class="w-full py-6">
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
      Total: "",
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
          "http://127.0.0.1:8000/api/inventoryall",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.inventory = response.data.inventory;

          const pendingItems = this.inventory.filter(
            (item) => item.status === "Pending"
          );
          const receivedItems = this.inventory.filter(
            (item) =>
              item.status === "Approved" && item.approved_status === null
          );

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
    isActiveTab(route) {
      return this.$route.path === route;
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
