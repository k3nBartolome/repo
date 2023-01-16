<template>
  <div class="flex items-center justify-center min-h-full px-4 pb-64 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
      <div class=" items center">
        <img class="w-auto h-12 mx-auto" src="https://cdn.contactcenterworld.com/images/company/vxi-global-solutions-1200px-logo.png" alt="Your Company" />
        <h2 class="mt-6 text-3xl font-bold tracking-tight text-center text-gray-900">Sign in</h2>
        
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="login">
        <div class="-space-y-px rounded-md shadow-sm">
          <div class="flex items center">
            <label for="email-address" class="sr-only">Email address</label>
            <input id="email-address" v-model="email" type="email" autocomplete="email" required="true" class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-none appearance-none rounded-t-md focus:z-10 focus:border-orange-600 focus:outline-none focus:ring-orange-600 sm:text-sm" placeholder="Email address" />
          </div>
          <div class="flex items center">
            <label for="password" class="sr-only">Password</label>
            <input id="password" v-model="password" type="password" autocomplete="current-password" required="true" class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-none appearance-none rounded-b-md focus:z-10 focus:border-orange-600 focus:outline-none focus:ring-orange-600 sm:text-sm" placeholder="Password" />
          </div>
        </div>

        <div class="flex items-center justify-between">
          <div class="text-sm">
            <a href="#" class="font-medium text-orange-600 hover:text-gray-700">Forgot your password?</a>
          </div>
        </div>

        <div class="flex items center">
          <button type="submit" class="relative flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-orange-600 border border-transparent rounded-md group hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
              <LockClosedIcon class="w-5 h-5 text-white group-hover:text-orange-600" aria-hidden="true" />
            </span>
            Sign in
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
<script>  
import axios from "axios";

export default {
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    login() {
      axios
        .post("http://127.0.0.1:8000/api/login", {
          email: this.email,
          password: this.password,
        })
        .then((response) => {
          localStorage.setItem("token", response.data.access_token);
          localStorage.setItem("role", response.data.access_role);
          this.$router.push("/dashboard");
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  created() {
    axios.interceptors.request.use(config => {
      config.headers.Authorization = `Bearer ${localStorage.getItem('token')}`
      return config
    });
  }
};
</script>