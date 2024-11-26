<template>
  <div class="container mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4 text-center">Update LOB Details</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <!-- Region -->
      <div class="flex flex-col">
        <label for="region" class="block text-sm font-medium">Region</label>
        <input
          id="region"
          v-model="form.region"
          type="text"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>

      <!-- Site -->
      <div class="flex flex-col">
        <label for="site" class="block text-sm font-medium">Site</label>
        <input
          id="site"
          v-model="form.site"
          type="text"
          class="p-2 mt-1 border rounded w-full"
        />
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
      form: {
        region: "",
        site: "",
        lob: "",
        team_name: "",
        project_code: "",
        updated_by: this.$store.state.user_id,
      },
      isSubmitting: false,
    };
  },
  methods: {
    async submitForm() {
      if (this.isSubmitting) return;

      this.isSubmitting = true;

      try {
        const apiUrl = `https://10.236.103.190/api/update/lob/${this.$route.params.id}`;
        const { data } = await axios.post(apiUrl, this.form);

        // Use the response data here (e.g., display success message)
        alert(data.message || "LOB updated successfully!");

        // Redirect to LOB details page or perform another action as needed
        this.$router.push({
          name: "LobDetails",
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
