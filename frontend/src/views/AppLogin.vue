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

        <div class="flex items-center justify-between">
          <div class="text-sm">
            <a href="#" class="font-medium text-orange-600 hover:text-gray-700"
              >Forgot your password?</a
            >
          </div>
        </div>

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
      form: {
        message: " ",
      },
    };
  },
  methods: {
    ...mapMutations(["setUser", "setToken", "setRole","setUserId"]),
    async login(e) {
      e.preventDefault();
      let user;
      let role;
      let token;
      let user_id;
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
          console.log(response.data);
        })
        .catch(function (error) {
          console.log(error);
          isLogin = false;
        });
      if (isLogin) {
          this.setUser(user);
          this.setToken(token);
          this.setRole(role);
          this.setUserId(user_id);
        if(role ==='admin'){
          this.$router.push({ path: "/admin_dashboard"});
        }
        else{
          this.$router.push({ path: "/dashboard" });
        }
      } else {
        this.form.message = "Invalid Credentials";
      }
    },
  },
};
</script>
