<template>
  <div class="container p-4 mx-auto">
    <!-- Search Bar -->
    <div class="flex justify-end mb-4">
      <input
        type="text"
        v-model="searchQuery"
        @input="debouncedSearch"
        placeholder="Search by name"
        class="w-full max-w-sm px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-300"
      />
    </div>

    <!-- Loading Indicator for Users -->
    <div v-if="loading" class="flex items-center justify-center mb-4">
      <svg class="w-5 h-5 text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4V2m0 20v-2m8-8h2M4 12H2m16.24-7.76l-1.42-1.42M5.18 18.36l-1.42 1.42M18.36 18.36l1.42-1.42M5.18 5.18L3.76 3.76" />
      </svg>
      <span class="ml-2 text-blue-500">Loading...</span>
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto">
      <table v-if="!loading" class="min-w-full border border-collapse border-gray-200 table-auto">
        <thead>
          <tr class="bg-gray-100">
            <th class="px-4 py-2 border">Name</th>
            <th class="px-4 py-2 border">Email</th>
            <th class="px-4 py-2 border">Assigned Sites</th>
            <th class="px-4 py-2 border">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users.data" :key="user.user_id" class="hover:bg-gray-50">
            <td class="px-4 py-2 border">{{ user.name }}</td>
            <td class="px-4 py-2 border">{{ user.email }}</td>
            <td class="px-4 py-2 border">
              <ul class="pl-5 list-disc">
                <li v-for="site in user.assigned_sites" :key="site">{{ site }}</li>
              </ul>
            </td>
            <td class="px-4 py-2 border">
              <button @click="openModal(user)" class="text-blue-500 hover:text-blue-700">
                Assign Sites
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between mt-4">
      <button
        @click="changePage(currentPage - 1)"
        :disabled="currentPage === 1"
        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg disabled:opacity-50"
      >
        Previous
      </button>
      <span class="px-4 py-2 text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
      <button
        @click="changePage(currentPage + 1)"
        :disabled="currentPage === totalPages"
        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg disabled:opacity-50"
      >
        Next
      </button>
    </div>

    <!-- Modal for Assigning Sites -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-50"
    >
      <div class="w-full max-w-md p-6 bg-white rounded-lg">
        <h3 class="mb-4 text-lg font-semibold">Assign Sites to {{ selectedUser.name }}</h3>
        <div class="overflow-y-auto max-h-64">
          <div v-for="site in sites" :key="site.site_id" class="flex items-center mb-2">
            <input
              type="checkbox"
              :value="site.site_id"
              v-model="selectedSites"
              :id="site.site_id"
              class="mr-2"
            />
            <label :for="site.site_id">{{ site.name }}</label>
          </div>
        </div>
        <div class="flex justify-end mt-4">
          <button
            @click="saveAssignedSites"
            class="px-4 py-2 mr-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600"
          >
            Save
          </button>
          <button
            @click="closeModal"
            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import debounce from "lodash/debounce";

export default {
  data() {
    return {
      users: {
        data: [],
        meta: {},
        links: {},
      },
      currentPage: 1,
      totalPages: 1,
      sites: [],
      selectedUser: null,
      selectedSites: [],
      showModal: false,
      searchQuery: "",
      loading: false, // Added loading state
    };
  },
  methods: {
    // Fetch users with pagination and search
    async getUsers(page = 1) {
      this.loading = true; // Set loading to true
      try {
        const token = this.$store.state.token;
        const params = {
          page: page,
          search: this.searchQuery, // Include search query
        };

        const response = await axios.get(`http://127.0.0.1:8000/api/list_users`, {
          params,
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.users = response.data;
          this.totalPages = response.data.meta.last_page;
          this.currentPage = page;
        } else {
          console.error("Error fetching users");
        }
      } catch (error) {
        console.error("Error fetching users:", error);
      } finally {
        this.loading = false; // Set loading to false when done
      }
    },

    // Debounced search to prevent too many requests
    debouncedSearch: debounce(function () {
      this.getUsers(1); // Reset to page 1 on new search
    }, 300),

    // Change the page and fetch users for the selected page
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.getUsers(page);
      }
    },

    // Fetch all sites for the modal
    async getSites() {
      this.loading = true; // Set loading to true
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/sites", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          console.log("Fetched Sites:", response.data.data);
          this.sites = response.data.data;
        } else {
          console.log("Error fetching sites");
        }
      } catch (error) {
        console.error("Error fetching sites:", error);
      } finally {
        this.loading = false; // Set loading to false when done
      }
    },

    // Open the modal to assign sites to the user
    openModal(user) {
      this.selectedUser = user;

      console.log("All Sites:", this.sites);

      // Ensure sites are already loaded
      if (this.sites.length === 0) {
        console.error("Sites data is not loaded.");
        return;
      }

      // Map assigned_sites (site names) to site_ids
      this.selectedSites = user.assigned_sites.map((assignedSiteName) => {
        // Find the site by name (case-insensitive and trimmed)
        const site = this.sites.find(site => site.name.trim().toLowerCase() === assignedSiteName.trim().toLowerCase());
        console.log("Matching Site:", site);
        return site ? site.site_id : null;
      }).filter(site_id => site_id !== null); // Remove null values

      console.log("Assigned Sites:", user.assigned_sites);
      console.log("Selected Sites:", this.selectedSites);

      this.showModal = true;
    },

    // Save the assigned sites
    async saveAssignedSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.put(
          `http://127.0.0.1:8000/api/user/${this.selectedUser.user_id}/assign-sites`,
          { site_ids: this.selectedSites }, // Send the selected sites' IDs as an array
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.showModal = false; // Close the modal after saving
          this.getUsers(this.currentPage); // Refresh the user list
        } else {
          console.error("Error assigning sites");
        }
      } catch (error) {
        console.error("Error assigning sites:", error);
      }
    },

    // Close the modal
    closeModal() {
      this.showModal = false;
    },
  },
  mounted: async function() {
    await this.getSites(); // Ensure sites are loaded first
    this.getUsers(); // Then fetch users
  },
};
</script>
