<template>
  <div class="container mx-auto mt-8 flex flex-col items-center">
    <h1 class="text-2xl font-semibold mb-4">Update Profile</h1>
    <form @submit.prevent="updateProfile" class="w-full max-w-md">
      <span v-if="successMessage" class="text-green-500">{{ successMessage }}</span>
      <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-600"
          >Name:</label
        >
        <input
          v-model="name"
          type="text"
          required
          class="mt-1 p-2 w-full border rounded-md"
        />
      </div>

      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-600"
          >Email:</label
        >
        <input
          v-model="email"
          type="email"
          required
          class="mt-1 p-2 w-full border rounded-md"
        />
      </div>

      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-600"
          >Password:</label
        >
        <input
          v-model="password"
          :type="showPassword ? 'text' : 'password'"
          class="mt-1 p-2 w-full border rounded-md"
        />
      </div>

      <div v-if="password">
        <div class="mb-4">
          <label
            for="confirmPassword"
            class="block text-sm font-medium text-gray-600"
            >Confirm Password:</label
          >
          <input
            v-model="confirmPassword"
            :type="showPassword ? 'text' : 'password'"
            class="mt-1 p-2 w-full border rounded-md"
          />
        </div>
      </div>

      <button
        type="submit"
        class="bg-blue-500 text-white p-2 rounded-md w-full"
      >
        Update Profile
      </button>
    </form>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      name: "",
      email: "",
      password: "",
      showPassword: false,
      confirmPassword: "",
      successMessage:"",
    };
  },
  mounted() {
    this.getProfile();
  },
  methods: {
    async getProfile() {
      try {
        const token = this.$store.state.token;
        const id = this.$store.state.user_id;

        const response = await axios.get(
          `http://127.0.0.1:8000/api/show_user/${id}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        const userProfile = response.data.data;

        this.name = userProfile.name;
        this.email = userProfile.email;

        console.log(response.data.data);
      } catch (error) {
        console.log(error);
      }
    },

    async updateProfile() {
  try {
    const token = this.$store.state.token;
    const id = this.$store.state.user_id;

    const payload = {
      name: this.name,
      email: this.email,
    };

    if (this.password) {
      payload.password = this.password;
      payload.password_confirmation = this.confirmPassword;
    }

    const response = await axios.put(
      `http://127.0.0.1:8000/api/update_user/profile/${id}`,
      payload,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    );

    this.name = response.data.user.name;
    this.email = response.data.user.email;

    // Add this line to set the success message
    this.successMessage = "Profile updated successfully!";

    console.log(response.data.message);
  } catch (error) {
    console.log(error);

  }}
},
};
</script>

<style scoped></style>
