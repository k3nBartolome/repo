<template>
  <div
    class="flex items-center justify-center min-h-screen px-4 bg-gray-100 sm:px-6 lg:px-8"
  >
    <div class="w-full max-w-md p-10 space-y-8 bg-white rounded-lg shadow-lg">
      <div class="flex justify-center">
        <img
          class="w-auto h-12 mx-auto"
          src="https://cdn.contactcenterworld.com/images/company/vxi-global-solutions-1200px-logo.png"
          alt="Your Company"
        />
      </div>
      <h2
        class="mt-6 text-3xl font-bold tracking-tight text-center text-gray-900"
      >
        Sign in
      </h2>
      <form class="mt-8 space-y-6" @submit.prevent="login">
        <div class="space-y-4">
          <!-- Email Input -->
          <div class="flex flex-col">
            <label for="email-address" class="sr-only">Email address</label>
            <input
              id="email-address"
              v-model="email"
              type="email"
              autocomplete="email"
              required
              class="relative block w-full px-4 py-3 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
              placeholder="Email address"
            />
          </div>

          <!-- Password Input -->
          <div class="flex flex-col">
            <label for="password" class="sr-only">Password</label>
            <input
              id="password"
              v-model="password"
              type="password"
              autocomplete="current-password"
              required
              class="relative block w-full px-4 py-3 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
              placeholder="Password"
            />
          </div>

          <!-- Error Message -->
          <div v-if="successMessage" class="text-sm text-center text-red-500">
            {{ successMessage }}
          </div>

          <!-- Submit Button -->
          <div class="flex items-center justify-center">
            <button
              type="submit"
              class="w-full px-4 py-3 text-lg font-semibold text-white transition duration-200 ease-in-out bg-orange-500 rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500"
            >
              Sign in
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import { mapMutations } from "vuex";

export default {
  data: () => ({
    email: "",
    password: "",
    successMessage: "",
  }),
  methods: {
    ...mapMutations([
      "setUser",
      "setToken",
      "setRole",
      "setUserId",
      "setPermissions",
      "setUserName",
      "setSite",
    ]),
    async login() {
      try {
        // Convert email to lowercase
        this.email = this.email.trim().toLowerCase();

        // Step 1: Fetch CSRF token
        await axios.get("http://127.0.0.1:8000/sanctum/csrf-cookie", {
          withCredentials: true,
        });

        // Step 2: Send login request
        const response = await axios.post(
          "http://127.0.0.1:8000/api/login",
          {
            email: this.email,
            password: this.password,
          },
          { withCredentials: true }
        );

        // Step 3: Extract response data
        const { user, token, role, user_id, permissions, site_id } =
          response.data;

        // Step 4: Save user info to Vuex store
        this.setUser(user);
        this.setToken(token);
        this.setRole(role);
        this.setUserId(user_id);
        this.setPermissions(permissions);
        this.setSite(site_id);

        // Step 5: Store token in localStorage
        localStorage.setItem("token", token);

        // Step 6: Set global Axios header
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

        // Step 7: Redirect based on role
        if (role === "onboarding") {
          await this.$router.push({ path: "/onboarding_dashboard" });
        } else if (role === "user") {
          await this.$router.push({ path: "/capfile" });
        } else if (["remx", "sourcing", "budget"].includes(role)) {
          await this.$router.push({ path: "/dashboard_manager/request" });
        } else if (role === "frontdesk") {
          await this.$router.push({ path: "/applicant_attendance_list" });
        }
      } catch (error) {
        this.successMessage =
          error.response?.data?.message || "Invalid Credentials!";
      }
    },
  },
};
</script>

<style scoped>
/* Custom CSS for the login page */
.bg-gray-100 {
  background-color: #f7fafc;
}

.bg-orange-500 {
  background-color: #e85a24;
}

.bg-orange-600 {
  background-color: #d15f00;
}

button:hover {
  background-color: #d15f00;
}

button:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(233, 90, 36, 0.5);
}

input:focus {
  box-shadow: 0 0 0 2px rgba(233, 90, 36, 0.5);
}
</style>
