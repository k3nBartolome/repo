<template>
  <div
    class="flex items-center justify-center min-h-full px-4 pb-64 sm:px-6 lg:px-8"
  >
    <div class="w-full max-w-md space-y-8">
      <div class="items center">
        <img
          class="w-auto h-12 mx-auto"
          src="https://cdn.contactcenterworld.com/images/company/vxi-global-solutions-1200px-logo.png"
          alt="Your Company"
        />
        <h2
          class="mt-6 text-3xl font-bold tracking-tight text-center text-gray-900"
        >
          Sign in
        </h2>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="login">
        <div class="-space-y-px rounded-md shadow-sm">
          <div class="flex items center">
            <label for="email-address" class="sr-only">Email address</label>
            <input
              id="email-address"
              v-model="email"
              type="email"
              autocomplete="email"
              required="true"
              class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-none appearance-none rounded-t-md focus:z-10 focus:border-orange-600 focus:outline-none focus:ring-orange-600 sm:text-sm"
              placeholder="Email address"
            />
          </div>
          <div class="flex items center">
            <label for="password" class="sr-only">Password</label>
            <input
              id="password"
              v-model="password"
              type="password"
              autocomplete="current-password"
              required="true"
              class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-none appearance-none rounded-b-md focus:z-10 focus:border-orange-600 focus:outline-none focus:ring-orange-600 sm:text-sm"
              placeholder="Password"
            />
          </div>
        </div>
        <span v-if="successMessage" class="text-red-500">{{
          successMessage
        }}</span>

        <div class="flex items center">
          <button
            type="submit"
            class="relative flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-orange-500 border border-transparent rounded-md group hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
          >
            Sign in
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import { mapMutations } from "vuex";
export default {
  data: () => {
    return {
      email: "",
      password: "",
      successMessage: "",
    };
  },
  methods: {
    ...mapMutations([
      "setUser",
      "setToken",
      "setRole",
      "setUserId",
      "setPermissions",
      "setUserName",
    ]),
    async login(e) {
      e.preventDefault();
      let user;
      let role;
      let token;
      let user_id;
      let permissions;
      let isLogin;

      await axios
        .post("http://127.0.0.1:8000/api/login", {
          email: this.email,
          password: this.password,
        })
        .then(function (response) {
          isLogin = true;
          user = response.data.user;
          token = response.data.token;
          role = response.data.role;
          user_id = response.data.user_id;
          permissions = response.data.permissions;
          console.log(response.data);
        })
        .catch(function (error) {
          console.error("API Error:", error);
          isLogin = false;
        });

      if (isLogin) {
        this.setUser(user);
        this.setToken(token);
        this.setRole(role);
        this.setUserId(user_id);
        this.setPermissions(permissions);

        console.log("Logged in with role:", role);

        if (role === "admin") {
          console.log("Redirecting to admin_dashboard");
          this.$router.push({ path: "/admin_dashboard" });
        } else if (role === "user") {
          console.log("Redirecting to capfile");
          this.$router.push({ path: "/capfile" });
        } else if (
          role === "remx" ||
          role === "sourcing" ||
          role === "budget"
        ) {
          console.log("Redirecting to dashboard_manager");
          this.$router.push({ path: "/dashboard_manager/request" });
        } else {
          console.log("Redirecting to perx_manager");
          this.$router.push({ path: "/perx_manager" });
        }
      } else {
        this.successMessage = "Invalid Credentials!";
      }
    },
  },
};
</script>
