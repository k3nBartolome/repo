<template>
  <div>
    <!-- Header -->
    <header class="w-full bg-white shadow">
      <div
        class="flex items-center w-full max-w-screen-xl py-4 sm:px-6 lg:px-8 mx-auto"
      >
        <h2 class="text-3xl font-bold tracking-tight text-gray-900">
          User Manager
        </h2>
      </div>
    </header>

    <!-- Form Section -->
    <div class="py-8">
      <div
        class="px-4 py-6 mx-auto bg-white border border-gray-200 rounded-lg shadow-sm max-w-7xl sm:px-6 lg:px-8"
      >
        <form
          @submit.prevent="postUser"
          class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5"
        >
          <!-- Name Input -->
          <label class="block">
            Name
            <input
              v-model="name"
              type="text"
              class="block w-full mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
          </label>

          <!-- Email Input -->
          <label class="block">
            Email
            <input
              v-model="email"
              type="email"
              class="block w-full mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
          </label>

          <!-- Sites Dropdown -->
          <label class="block">
            Sites
            <select
              v-model="sites_selected"
              class="block w-full mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
              @change="getSites"
            >
              <option disabled value="" selected>Please select one</option>
              <option v-for="site in sites" :key="site.id" :value="site.id">
                {{ site.name }}
              </option>
            </select>
          </label>

          <!-- Roles Dropdown -->
          <label class="block">
            Roles
            <select
              v-model="roles_selected"
              class="block w-full mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
              @change="getRoles"
            >
              <option disabled value="" selected>Please select one</option>
              <option v-for="role in roles" :key="role.id" :value="role.name">
                {{ role.name }}
              </option>
            </select>
          </label>

          <!-- Password Input -->
          <label class="block">
            Password
            <input
              v-model="password"
              type="password"
              class="block w-full mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
          </label>

          <!-- Submit Button -->
          <button
            type="submit"
            class="flex items-center justify-center px-4 py-2 font-semibold text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
          >
            <i class="fa fa-building mr-2"></i> Add User
          </button>
        </form>
      </div>
    </div>

    <!-- Table Section -->
    <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div
        class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm"
      >
        <!-- Search Bar -->
        <div class="p-4 border-b border-gray-200">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search users..."
            class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            @input="debouncedSearch"
          />
        </div>

        <!-- User Table -->
        <table class="w-full table-auto">
          <thead class="bg-blue-50">
            <tr>
              <th
                class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider"
              >
                ID
              </th>
              <th
                class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider"
              >
                Name
              </th>
              <th
                class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider"
              >
                Email
              </th>
              <th
                class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider"
              >
                Assigned Site
              </th>
              <th
                class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider"
              >
                Created At
              </th>
              <th
                class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider"
              >
                Updated At
              </th>
              <th
                class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider"
              >
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr
              v-for="user in users.data"
              :key="user.user_id"
              class="hover:bg-gray-50"
            >
              <td class="px-6 py-4 text-sm text-gray-700">
                {{ user.user_id }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ user.name }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ user.email }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">
                {{ user.assigned_sites[0]?.name || "No site assigned" }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-700">
                {{ user.created_at }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-700">
                {{ user.updated_at }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-700">
                <button
                  @click="editUser(user)"
                  class="px-3 py-1 text-sm font-semibold text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                >
                  <i class="fa fa-edit"></i>
                </button>
                <button
                  @click="deleteUser(user.user_id)"
                  class="px-3 py-1 ml-2 text-sm font-semibold text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                >
                  <i class="fa fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div
          class="flex items-center justify-between p-4 border-t border-gray-200"
        >
          <button
            @click="changePage(currentPage - 1)"
            :disabled="currentPage === 1"
            class="px-4 py-2 text-sm font-semibold text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>
          <span class="text-sm text-gray-700">
            Page {{ currentPage }} of {{ totalPages }}
          </span>
          <button
            @click="changePage(currentPage + 1)"
            :disabled="currentPage === totalPages"
            class="px-4 py-2 text-sm font-semibold text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
      </div>
    </div>

    <!-- Edit User Modal -->
    <div
      v-if="showEditModal"
      class="fixed inset-0 flex justify-center items-center bg-gray-900 bg-opacity-50 z-50 p-4"
    >
      <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Edit User</h3>
        <form @submit.prevent="saveEditedUser">
          <!-- Name Input -->
          <label class="block">
            Name
            <input
              v-model="selectedUser.name"
              type="text"
              class="block w-full mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
          </label>

          <!-- Email Input -->
          <label class="block mt-4">
            Email
            <input
              v-model="selectedUser.email"
              type="email"
              class="block w-full mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
          </label>

          <!-- Password Input -->
          <label class="block mt-4">
            Password
            <input
              v-model="selectedUser.password"
              type="password"
              class="block w-full mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Leave blank to keep current password"
            />
          </label>

          <!-- Roles Dropdown -->
          <label class="block mt-4">
            Role
            <select
              v-model="selectedUser.role"
              class="block w-full mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option disabled value="">Select a role</option>
              <option v-for="role in roles" :key="role.id" :value="role.name">
                {{ role.name }}
              </option>
            </select>
          </label>

          <!-- Single Site Dropdown -->
          <label class="block mt-4">
            Assigned Site
            <select
              v-model="selectedUser.site_id"
              class="block w-full mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option disabled value="">Select a site</option>
              <option v-for="site in sites" :key="site.id" :value="site.id">
                {{ site.name }}
              </option>
            </select>
          </label>

          <!-- Save and Cancel Buttons -->
          <div class="mt-4 flex justify-end">
            <button
              type="submit"
              class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
            >
              Save
            </button>
            <button
              @click="showEditModal = false"
              class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md ml-2 hover:bg-gray-300"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import "font-awesome/css/font-awesome.min.css";
import axios from "axios";
import debounce from "lodash/debounce";

export default {
  data() {
    return {
      users: {
        data: [],
        meta: {},
      },
      roles_selected: "",
      sites_selected: "",
      name: "",
      roles: [],
      sites: [],
      email: "",
      password: "",
      searchQuery: "",
      currentPage: 1,
      perPage: 5, // Default perPage value matching backend
      totalPages: 1,
      loading: false,
      showEditModal: false, // Controls the edit modal
      selectedUser: {
        user_id: null,
        name: "",
        email: "",
        password: "",
        role: "",
        site_id: "", // Single site ID
      }, // Stores the user being edited
    };
  },
  mounted() {
    this.getUsers();
    this.getRoles();
    this.getSites();
  },
  methods: {
    // Fetch users with pagination and search
    async getUsers(page = 1) {
      this.loading = true;
      try {
        const token = this.$store.state.token;
        const params = {
          page: page,
          perPage: this.perPage,
          search: this.searchQuery,
        };

        const response = await axios.get(
          "http://127.0.0.1:8000/api/list_user",
          {
            params,
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.users = response.data;
          this.totalPages = response.data.meta.last_page;
          this.currentPage = page;
        }
      } catch (error) {
        console.error("Error fetching users:", error);
      } finally {
        this.loading = false;
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
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/sites", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.sites = response.data.data;
        }
      } catch (error) {
        console.error("Error fetching sites:", error);
      }
    },

    // Fetch all roles
    async getRoles() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/list_role",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.roles = response.data.data;
        }
      } catch (error) {
        console.error("Error fetching roles:", error);
      }
    },

    // Add a new user
    async postUser() {
      try {
        const token = this.$store.state.token;
        const response = await axios.post(
          "http://127.0.0.1:8000/api/create_user",
          {
            name: this.name,
            role: this.roles_selected,
            email: this.email,
            site_id: this.sites_selected,
            password: this.password,
          },
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.getUsers(this.currentPage); // Refresh the user list
          this.resetForm();
        }
      } catch (error) {
        console.error("Error adding user:", error);
      }
    },

    // Delete a user
    async deleteUser(user_id) {
      try {
        const token = this.$store.state.token;
        const response = await axios.delete(
          `http://127.0.0.1:8000/api/delete_user/${user_id}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.getUsers(this.currentPage); // Refresh the user list
        }
      } catch (error) {
        console.error("Error deleting user:", error);
      }
    },

    // Open the edit modal
    editUser(user) {
      this.selectedUser = {
        user_id: user.user_id,
        name: user.name,
        email: user.email,
        password: "", // Reset password field
        role: user.roles[0] || "", // Assuming a user has only one role
        site_id: user.assigned_sites[0]?.id || "", // Single site ID
      };
      this.showEditModal = true;
    },

    // Save the edited user
    async saveEditedUser() {
      try {
        const token = this.$store.state.token;
        const response = await axios.put(
          `http://127.0.0.1:8000/api/update_user/${this.selectedUser.user_id}`,
          {
            name: this.selectedUser.name,
            email: this.selectedUser.email,
            password: this.selectedUser.password || undefined, // Only send if provided
            role: this.selectedUser.role,
            site_id: this.selectedUser.site_id, // Single site ID
          },
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.showEditModal = false; // Close the modal
          this.getUsers(this.currentPage); // Refresh the user list
        }
      } catch (error) {
        console.error("Error updating user:", error);
      }
    },

    // Reset the form
    resetForm() {
      this.name = "";
      this.email = "";
      this.sites_selected = "";
      this.roles_selected = "";
      this.password = "";
    },
  },
};
</script>
