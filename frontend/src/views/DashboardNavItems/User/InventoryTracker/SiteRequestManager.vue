<template>
  <div class="px-0 pt-1 border-b border-gray-200 dark:border-gray-700">
    <div class="container">
      <div class="row">
        <div class="col-md-2 col-sm-6">
          <router-link to="/site_request_manager/request" class="link-button">
            <button
              class="tab-button"
              :class="{
                'selected-tab': isActiveTab('/site_request_manager/request'),
              }"
            >
              Request
            </button>
          </router-link>
        </div>
        <div class="col-md-2 col-sm-6">
          <router-link to="/site_request_manager/pending" class="link-button">
            <button
              class="tab-button"
              :class="{
                'selected-tab': isActiveTab('/site_request_manager/pending'),
              }"
            >
              Pending Request
              <span v-if="totalPending > 0" class="count-notification">{{
                totalPending
              }}</span>
            </button>
          </router-link>
        </div>
        <div class="col-md-2 col-sm-6">
          <router-link to="/site_request_manager/approved" class="link-button">
            <button
              class="tab-button"
              :class="{
                'selected-tab': isActiveTab('/site_request_manager/approved'),
              }"
            >
              Approved Request
            </button>
          </router-link>
        </div>
        <div class="col-md-2 col-sm-6">
          <router-link
            to="/site_request_manager/denied"
            v-if="isUser || isRemx || isBudget || isSourcing"
            class="link-button"
          >
            <button
              class="tab-button"
              :class="{
                'selected-tab': isActiveTab('/site_request_manager/denied'),
              }"
            >
              Denied Request
            </button>
          </router-link>
        </div>
        <div class="col-md-2 col-sm-6">
          <router-link
            to="/site_request_manager/received"
            v-if="isUser || isRemx || isBudget || isSourcing"
            class="link-button"
          >
            <button
              class="tab-button"
              :class="{
                'selected-tab': isActiveTab('/site_request_manager/received'),
              }"
            >
              Receive
              <span v-if="totalReceived > 0" class="count-notification">{{
                totalReceived
              }}</span>
            </button>
          </router-link>
        </div>
        <div class="col-md-2 col-sm-6">
          <router-link to="/site_request_manager/cancelled" class="link-button">
            <button
              class="tab-button"
              :class="{
                'selected-tab': isActiveTab('/site_request_manager/cancelled'),
              }"
            >
              Cancelled Request
            </button>
          </router-link>
        </div>
      </div>
    </div>
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
          "https://10.236.103.168/api/inventoryall",
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
* {
  margin: 0;
  padding: 0;
  border: 0;
  outline: 0;
  font-size: 100%;
  vertical-align: baseline;
  background: transparent;
}
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
.tab-button {
  display: block;
  width: 100%;
  padding: 1rem;
  border: none;
  background-color: transparent;
  border-bottom: 2px solid transparent;
  transition: border-color 0.3s, color 0.3s;
  text-align: center;
  color: black;
  text-decoration: none !important;
}

.tab-button:hover {
  color: #6366f1;
}

@media (min-width: 576px) {
  .tab-button {
    padding: 1rem 0.75rem;
  }
}
@media (min-width: 768px) {
  .tab-button {
    padding: 1rem 0.5rem;
  }
}
.link-button {
  text-decoration: none;
}
</style>
