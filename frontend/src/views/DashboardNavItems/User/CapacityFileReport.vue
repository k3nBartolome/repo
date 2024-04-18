<template>
  <div class="px-0 pt-1 border-b border-gray-200 dark:border-gray-700">
    <div class="container">
      <div class="row">
        <div class="col-md-2 col-sm-6">
          <router-link to="/capfile" class="link-button" v-if="isUser || isSourcing">
            <button
              class="tab-button"
              :class="{
                'selected-tab': isActiveTab('/capfile'),
              }"
            >
              Manage
            </button>
          </router-link>
        </div>
        <div class="col-md-2 col-sm-6">
          <router-link
            to="/capfile/summary"
            class="link-button"
            v-if="isUser || isSourcing"
          >
            <button
              class="tab-button"
              :class="{
                'selected-tab': isActiveTab('/capfile/summary'),
              }"
            >
              Hiring Summary
            </button>
          </router-link>
        </div>
        <div class="col-md-2 col-sm-6">
          <router-link
            to="/capfile/history"
            class="link-button"
            v-if="isUser || isSourcing"
          >
            <button
              class="tab-button"
              :class="{
                'selected-tab': isActiveTab('/capfile/history'),
              }"
            >
              Class History
            </button>
          </router-link>
        </div>
        <div class="col-md-2 col-sm-6">
          <router-link
            to="/capfile/cancelled"
            class="link-button"
            v-if="isUser || isSourcing"
          >
            <button
              class="tab-button"
              :class="{
                'selected-tab': isActiveTab('/capfile/cancelled'),
              }"
            >
              Cancelled Class
            </button>
          </router-link>
        </div>
        <div class="col-md-2 col-sm-6">
          <router-link
            to="/capfile/moved"
            class="link-button"
            v-if="isUser || isSourcing"
          >
            <button
              class="tab-button"
              :class="{
                'selected-tab': isActiveTab('/capfile/moved'),
              }"
            >
              Moved Class
            </button>
          </router-link>
        </div>
      </div>
    </div>
  </div>
  <main class="flex flex-col h-screen overflow-x-hidden">
    <div class="flex flex-1 px-4 py-2 md:px-1">
      <div class="w-full py-6">
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
  methods: {
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
