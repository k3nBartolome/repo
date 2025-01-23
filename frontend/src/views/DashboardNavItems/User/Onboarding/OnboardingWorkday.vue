<template>
  <div class="container mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4 text-center">Update Workday Details</h2>

   
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
     <div class="flex flex-col">
      <label for="workday_id" class="block text-sm font-medium">WORKDAY ID </label>
      <input
        id="workday_id"
        v-model="form.workday_id"
        type="text"
        class="p-2 mt-1 border rounded w-full"
      />
    </div>
      <div class="flex flex-col">
        <label for="contract_status" class="block text-sm font-medium"
          >CONTRACT STATUS</label
        >
        <select v-model="form.contract_status" class="p-2 mt-1 border rounded w-full">
          <option disabled>Please select one</option>
          <option value="DONE - WORKDAY">DONE - WORKDAY</option>
          <option value="DONE - MANUAL">DONE - MANUAL</option>
          <option value="PENDING">PENDING</option>
        </select>
      </div>
      <div class="flex flex-col">
        <label for="contract_remarks" class="block text-sm font-medium"
          >CONTRACT REMARKS
        </label>
        <select v-model="form.contract_remarks" class="p-2 mt-1 border rounded w-full">
          <option disabled>Please select one</option>
          <option value="COMPLETE">COMPLETE</option>
          <option value="WITH FINDINGS">WITH FINDINGS</option>
        </select>
      </div>

      <div class="flex flex-col">
        <label for="completion" class="block text-sm font-medium">201 COMPLETION </label>
        <select v-model="form.completion" class="p-2 mt-1 border rounded w-full">
          <option disabled>Please select one</option>
          <option value="COMPLETE">COMPLETE</option>
          <option value="WITH FINDINGS">WITH FINDINGS</option>
          <option value="PENDING">PENDING</option>
        </select>
      </div>
      <div class="flex flex-col">
        <label for="contract_findings" class="block text-sm font-medium"
          >CONTRACT FINDINGS
        </label>
        <input
          id="contract_findings"
          v-model="form.contract_findings"
          type="text"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>

      <div class="flex flex-col">
        <label for="per_findings" class="block text-sm font-medium">PER FINDINGS </label>
        <input
          id="per_findings"
          v-model="form.per_findings"
          type="text"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label for="lob" class="block text-sm font-medium">RO FEEDBACK </label>
        <input
          id="lob"
          v-model="form.ro_feedback"
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
        workday_id: "",
        ro_feedback: "",
        per_findings: "",
        completion: "",
        contract_findings: "",
        contract_remarks: "",
        contract_status: "",

        updated_by: this.$store.state.user_id,
      },
      isSubmitting: false,
    };
  },
  async created() {
    // Initialize the selected site based on Vuex state
    this.site = this.$store.state.site_id || ""; // Default to an empty string if site_id is not set

    // Fetch sites from the API
 
    this.getWorkday();
  },
  methods: {
   async getWorkday() {
  try {
    const apiUrl = `https://10.109.2.112/api/get/workday/${this.$route.params.id}`;
    const response = await axios.get(apiUrl);
    const data = response.data.data;

    this.form.workday_id = data.workday_id || ""; // Default to empty string
    this.form.ro_feedback = data.ro_feedback || ""; // Default to empty string
    this.form.per_findings = data.per_findings || ""; // Default to empty string
    this.form.completion = data.completion || "PENDING"; // Example default value
    this.form.contract_findings = data.contract_findings || ""; // Default to empty string
    this.form.contract_remarks = data.contract_remarks || ""; // Default to empty string
    this.form.contract_status = data.contract_status || "Pending"; // Default value from API
  } catch (error) {
    console.error("Error fetching DT data:", error.response || error.message);
    alert("Failed to load workday information.");
  }
}

,
    async submitForm() {
      if (this.isSubmitting) return;

      this.isSubmitting = true;

      try {
        const apiUrl = `https://10.109.2.112/api/update/workday/${this.$route.params.id}`;
        const { data } = await axios.post(apiUrl, this.form);

        // Use the response data here (e.g., display success message)
        alert(data.message || "LOB updated successfully!");

        // Redirect to workday details page or perform another action as needed
        this.$router.push({
          name: "OnboardingUpdateSelection",
          params: { id: this.$route.params.id },
        });
      } catch (error) {
        console.error(error);
        alert("An error occurred while updating workday.");
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
