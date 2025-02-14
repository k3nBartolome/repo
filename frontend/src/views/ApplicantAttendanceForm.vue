<template>
  <div class="flex items-center justify-center min-h-screen p-4 bg-gray-100">
    <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-lg">
      <h1 class="mb-6 text-2xl font-semibold text-center">Fill in the Details</h1>
      <form @submit.prevent="submitForm" class="space-y-4">
        <!-- Site -->
        <div>
          <label for="site" class="block text-sm font-medium text-gray-700">Site:</label>
          <select id="site" disabled v-model="form.site_id" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" :class="{'border-red-500': errors.site_id}">
            <option value="" disabled>Select a site</option>
            <option v-for="site in sites" :key="site.id" :value="site.id">
              {{ site.name }}
            </option>
          </select>
          <p v-if="errors.site_id" class="mt-1 text-xs text-red-500">{{ errors.site_id }}</p>
        </div>

        <!-- First Name -->
        <div>
          <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
          <input id="first_name" type="text" v-model="form.first_name" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" :class="{'border-red-500': errors.first_name}" />
          <p v-if="errors.first_name" class="mt-1 text-xs text-red-500">{{ errors.first_name }}</p>
        </div>

        <!-- Middle Name -->
        <div>
          <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name:</label>
          <input id="middle_name" type="text" v-model="form.middle_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" :class="{'border-red-500': errors.middle_name}" />
          <p v-if="errors.middle_name" class="mt-1 text-xs text-red-500">{{ errors.middle_name }}</p>
        </div>

        <!-- Last Name -->
        <div>
          <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
          <input id="last_name" type="text" v-model="form.last_name" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" :class="{'border-red-500': errors.last_name}" />
          <p v-if="errors.last_name" class="mt-1 text-xs text-red-500">{{ errors.last_name }}</p>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
          <input id="email" type="email" v-model="form.email" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" :class="{'border-red-500': errors.email}" />
          <p v-if="errors.email" class="mt-1 text-xs text-red-500">{{ errors.email }}</p>
        </div>

        <!-- Contact Number -->
        <div>
          <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number:</label>
          <input id="contact_number" type="text" v-model="form.contact_number" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" :class="{'border-red-500': errors.contact_number}" />
          <p v-if="errors.contact_number" class="mt-1 text-xs text-red-500">{{ errors.contact_number }}</p>
        </div>

        <!-- Submit Button -->
        <div>
          <button type="submit" class="w-full px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Submit</button>
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
      sites: [], // List of available sites
      form: {
        site_id: "",
        first_name: "",
        middle_name: "",
        last_name: "",
        email: "",
        contact_number: "",
      },
      errors: {}, // Object to store validation errors
    };
  },
  created() {
    this.fetchSites(); // Load sites when the component is created
  },
  mounted() {
    const siteId = this.$route.params.id;
    if (siteId) {
      this.form.site_id = siteId;
    }
  },
  methods: {
    async fetchSites() {
      try {
        const token = this.$store.state.token; // Retrieve token from Vuex store
        const config = {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        };

        const response = await axios.get("http://127.0.0.1:8000/api/sites", config);
        this.sites = response.data.data;
      } catch (error) {
        alert("Failed to load sites. Please try again.");
      }
    },
async submitForm() {
  this.errors = {}; // Clear previous errors

  // Simple client-side validation
  if (!this.form.site_id) {
    this.errors.site_id = "Site is required.";
  }
  if (!this.form.first_name) {
    this.errors.first_name = "First Name is required.";
  }
  if (!this.form.last_name) {
    this.errors.last_name = "Last Name is required.";
  }
  if (!this.form.email) {
    this.errors.email = "Email is required.";
  }
  if (!this.form.contact_number) {
    this.errors.contact_number = "Contact Number is required.";
  }

  // If there are validation errors, stop the submission
  if (Object.keys(this.errors).length > 0) {
    return;
  }

  try {
    const token = this.$store.state.token; // Retrieve token from Vuex store
    const config = {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    };

    await axios.post("http://127.0.0.1:8000/api/applicant/create", this.form, config);

    alert("Applicant details submitted successfully!");
    this.resetForm();
    this.$router.push({ name: "ApplicantChecker" }); // Replace 'SuccessPage' with the route name or path
    window.location.reload();
  } catch (error) {
    if (error.response && error.response.data) {
      // Display custom error message from backend
      alert(error.response.data.message || "Failed to submit the applicant details. Please try again.");
    } else {
      alert("Failed to submit the applicant details. Please try again.");
    }
  }
},



    resetForm() {
      this.form = {
        first_name: "",
        middle_name: "",
        last_name: "",
        email: "",
        contact_number: "",
      };
      this.errors = {};
    },
  },
};
</script>

<style scoped>
/* Optional custom styling for error messages */
</style>
