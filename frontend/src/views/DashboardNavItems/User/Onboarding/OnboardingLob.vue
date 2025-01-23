<template>
  <div class="container mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4 text-center">Update LOB Details</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <!-- Region -->
      <div class="flex flex-col">
        <label for="site" class="block text-sm font-medium">Region</label>
        <select
                  v-model="form.region"
                  
                   class="p-2 mt-1 border rounded w-full"
                >
                  <option disabled value="">Please select Site</option>
                  <option v-for="regions in regions" :key="regions" :value="regions">
                    {{ regions }}
                  </option>
                </select>
      </div>

      <!-- Site -->
      <div class="flex flex-col">
        <label for="site" class="block text-sm font-medium">Site</label>
        <select
                  v-model="form.site"
                  
                   class="p-2 mt-1 border rounded w-full"
                >
                  <option disabled value="">Please select Site</option>
                  <option v-for="site in sites" :key="site.id" :value="site.id">
                    {{ site.name }}
                  </option>
                </select>
      </div>

      <!-- LOB -->
      <div class="flex flex-col">
        <label for="lob" class="block text-sm font-medium">LOB</label>
        <input
          id="lob"
          v-model="form.lob"
          type="text"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>

      <!-- Team Name -->
      <div class="flex flex-col">
        <label for="team_name" class="block text-sm font-medium"
          >Team Name</label
        >
        <input
          id="team_name"
          v-model="form.team_name"
          type="text"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>

      <!-- Project Code -->
      <div class="flex flex-col">
        <label for="project_code" class="block text-sm font-medium"
          >Project Code</label
        >
        <input
          id="project_code"
          v-model="form.project_code"
          type="text"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label for="compliance_poc" class="block text-sm font-medium"
          >Compliance POC</label
        >
        <input
          id="compliance_poc"
          v-model="form.compliance_poc"
          type="text"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>
    </div>

    <!-- Submit Button -->
    <div class="mt-4 text-center">
      <button
        @click="submitForm"
        :disabled="isSubmitting"
        class="btn p-2 bg-blue-600 text-white font-semibold rounded w-full sm:w-1/2 lg:w-1/4 mx-auto"
      >
        {{ isSubmitting ? "Submitting..." : "Submit" }}
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      sites: [],
      regions:[],
       
      form: {
         site: "",
        region: "",
        lob: "",
        team_name: "",
        project_code: "",
        compliance_poc:"",
        updated_by: this.$store.state.user_id,
      },
      isSubmitting: false,
    };
  },async created() {
    // Initialize the selected site based on Vuex state
    this.site = this.$store.state.site_id || ""; // Default to an empty string if site_id is not set

    // Fetch sites from the API
    await this.getSites();
    this.getRegion();
   this.getLob(); 
  },
  methods: {
    async getSites() {
      try {
        const token = this.$store.state.token; // Retrieve the token from Vuex store
        const response = await axios.get("https://10.109.2.112/api/sites", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.sites = response.data.data; // Populate sites with API response data
          console.log("Sites loaded:", response.data.data);
        } else {
          console.log("Error fetching sites");
        }
      } catch (error) {
        console.error("Error while fetching sites:", error);
      }
    },
    async getRegion() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("https://10.109.2.112/api/regions", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.regions = response.data.data;
        }
      } catch (error) {
        console.error("Error fetching regions:", error);
      }
    },
    async getLob() {
      try {
        const apiUrl = `https://10.109.2.112/api/get/lob/${this.$route.params.id}`;
        const response = await axios.get(apiUrl);
        const data = response.data.data;

        this.form.region = data.region || "";
        this.form.lob = data.lob || "";
        this.form.team_name = data.team_name || "";
        this.form.project_code = data.project_code || "";
        this.form.site = data.site || "";
         this.form.compliance_poc = data.compliance_poc || "";
      } catch (error) {
        console.error("Error fetching DT data:", error.response || error.message);
        alert("Failed to load drug test information.");
      }
    },
    async submitForm() {
      if (this.isSubmitting) return;

      this.isSubmitting = true;

      try {
        const apiUrl = `https://10.109.2.112/api/update/lob/${this.$route.params.id}`;
        const { data } = await axios.post(apiUrl, this.form);

        // Use the response data here (e.g., display success message)
        alert(data.message || "LOB updated successfully!");

        // Redirect to LOB details page or perform another action as needed
        this.$router.push({
          name: "OnboardingUpdateSelection",
          params: { id: this.$route.params.id },
        });
      } catch (error) {
        console.error(error);
        alert("An error occurred while updating LOB.");
      } finally {
        this.isSubmitting = false;
      }
    },
  },
};
</script>

<style scoped>
.btn {
  background-color: #3490dc;
  color: white;
  font-weight: bold;
  transition: background-color 0.3s ease;
}

.btn:hover {
  background-color: #2779bd;
}

.container {
  max-width: 800px;
  margin: auto;
}
</style>
