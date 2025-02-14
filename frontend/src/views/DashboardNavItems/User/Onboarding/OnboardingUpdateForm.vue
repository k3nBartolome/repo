<template>
  <div class="px-4 py-4">
    <form @submit.prevent="updateEmployee">
      <!-- Employee Details Section -->
      <div class="mb-4">
        <div class="flex items-center justify-between mb-2">
          <span class="text-lg font-semibold">Employee Details</span>
          <div class="flex items-center space-x-4">
            <button
              type="button"
              @click="toggleSection1"
              class="text-blue-500 hover:text-blue-700 focus:outline-none"
            >
              <svg
                v-if="isSection1Visible"
                xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
              <svg
                v-else
                xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M5 15l7-7 7 7"
                />
              </svg>
            </button>
            <button
              type="button"
              @click="toggleEdit"
              class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
              {{ isEditable ? "Cancel" : "Edit" }}
            </button>
          </div>
        </div>

        <div
          v-if="isSection1Visible"
          class="px-4 py-4 border rounded-lg bg-gray-50"
        >
          <div
            class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 md:grid-cols-5"
          >
            <!-- Employee ID -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Employee ID</label
              >
              <input
                v-model="employee_data.employee_id"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- First Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >First Name</label
              >
              <input
                v-model="employee_data.first_name"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Last Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Last Name</label
              >
              <input
                v-model="employee_data.last_name"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Middle Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Middle Name</label
              >
              <input
                v-model="employee_data.middle_name"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Date of Birth -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Date of Birth</label
              >
              <input
                v-model="employee_data.birthdate"
                type="date"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Email -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Email</label
              >
              <input
                v-model="employee_data.email"
                type="email"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Contact Number -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Contact#</label
              >
              <input
                v-model="employee_data.contact_number"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Hired Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Hired Date</label
              >
              <input
                v-model="employee_data.hired_date"
                type="date"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Hired Month -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Hired Month</label
              >
              <input
                v-model="employee_data.hired_month"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Employee Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Employee Status</label
              >
              <input
                v-model="employee_data.status"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Employment Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Employment Status</label
              >
              <input
                v-model="employee_data.employment_status"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Account Associate -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Position</label
              >
              <input
                v-model="employee_data.account_associate"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Account Type -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Account Type</label
              >
              <input
                v-model="employee_data.account_type"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Updated By -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Updated By</label
              >
              <input
                v-model="employee_data.updated_by"
                type="text"
                readonly
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Last Updated At -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Last Updated At</label
              >
              <input
                v-model="employee_data.updated_at"
                type="text"
                readonly
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Programs Section -->
      <div class="mb-4">
        <div class="flex items-center justify-between mb-2">
          <span class="text-lg font-semibold">Programs</span>
          <button
            type="button"
            @click="toggleSection2"
            class="text-blue-500 hover:text-blue-700 focus:outline-none"
          >
            <svg
              v-if="isSection2Visible"
              xmlns="http://www.w3.org/2000/svg"
              class="w-6 h-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
              />
            </svg>
            <svg
              v-else
              xmlns="http://www.w3.org/2000/svg"
              class="w-6 h-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 15l7-7 7 7"
              />
            </svg>
          </button>
        </div>

        <div
          v-if="isSection2Visible"
          class="px-4 py-4 border rounded-lg bg-gray-50"
        >
          <div
            class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 md:grid-cols-6"
          >
            <!-- Region -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Region</label
              >
              <input
                v-model="lob_data.region"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Site -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Site</label
              >
              <input
                v-model="lob_data.site"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- LOB -->
            <div>
              <label class="block text-sm font-medium text-gray-700">LOB</label>
              <input
                v-model="lob_data.lob"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Team Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Team Name</label
              >
              <input
                v-model="lob_data.team_name"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Project Code -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Project Code</label
              >
              <input
                v-model="lob_data.project_code"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Compliance POC -->
            <div>
              <label class="block text-sm font-medium text-gray-700"
                >Compliance POC</label
              >
              <input
                v-model="lob_data.compliance_poc"
                type="text"
                :readonly="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Save Button -->
      <div class="mt-6 text-right">
        <button
          v-if="isEditable"
          type="submit"
          class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          Save Details
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      employee_data: {
        employee_id: [],
        status: [],
        first_name: [],
        middle_name: [],
        last_name: [],
        hired_date: [],
        birthdate: [],
        contact_number: [],
        email: [],
        account_associate: [],
        account_type: [],
        employment_status: [],
        updated_by: [],
        updated_at: [],
        hired_month: [],
      },
      lob_data: {
        region: [],
        site: [],
        lob: [],
        team_name: [],
        project_code: [],
        compliance_poc: [],
      },
      requirements_data: {},
      workday_data: {
        workday_id: [],
        ro_feedback: [],
        per_findings: [],
        completion: [],
        contract_findings: [],
        contract_remarks: [],
        contract_status: [],
      },
      isSection1Visible: true,
      isSection2Visible: true,
      isEditable: false, // Edit mode state
    };
  },
  mounted() {
    this.fetchEmployeeData();
  },
  methods: {
    async downloadEmployeeImages() {
      this.isLoading = true; // Show loading state
      try {
        const employeeResponse = await axios.get(
          `http://127.0.0.1:8000/api/employees/${this.$route.params.id}`
        );

        console.log("Employee Response:", employeeResponse.data);

        const fileName = employeeResponse.data.fileName || "default.zip";
        console.log("File Name from API:", fileName);

        const response = await axios.get(
          `http://127.0.0.1:8000/api/employees/${this.$route.params.id}/download-images`,
          {
            responseType: "blob", // Important for handling file downloads
          }
        );

        // Create a download link for the ZIP file
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", fileName); // Set correct filename
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      } catch (error) {
        console.error("Error downloading employee images:", error);
      } finally {
        this.isLoading = false; // Hide loading state
      }
    },

    async fetchEmployeeData() {
      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/show/employees/${this.$route.params.id}`
        );
        if (response.status === 200) {
          this.employee_data = response.data.employee_data || {};
          this.requirements_data = response.data.requirements_data || {};
          this.lob_data = response.data.lob_data || {};
          this.workday_data = response.data.workday_data || {};
        } else {
          console.error(
            "Error: Employee data fetch failed with status:",
            response.status
          );
        }
      } catch (error) {
        console.error("Error fetching employee data:", error);
      }
    },

    toggleSection1() {
      this.isSection1Visible = !this.isSection1Visible;
    },

    toggleSection2() {
      this.isSection2Visible = !this.isSection2Visible;
    },

    toggleEdit() {
      this.isEditable = !this.isEditable;
    },

    async updateEmployee() {
      try {
        const token = this.$store.state.token;
        const headers = {
          Authorization: `Bearer ${token}`,
        };

        const payload = {
          // Employee Details
          employee_id: this.employee_id,
          last_name: this.last_name,
          first_name: this.first_name,
          middle_name: this.middle_name,
          employee_status: this.employee_status,
          hired_date: this.hired_date,
          hired_month: this.hired_month,
          birthdate: this.birthdate,
          contact_number: this.contact_number,
          email: this.email,
          account_associate: this.account_associate,

          // Programs Section
          region: this.region,
          site: this.site,
          lob: this.lob,
          team_name: this.team_name,
          project_code: this.project_code,
        };

        const response = await axios.put(
          `http://127.0.0.1:8000/api/employees/${this.$route.params.id}`,
          payload,
          { headers }
        );

        if (response.status === 200) {
          alert("Employee details updated successfully!");
          this.isEditable = false; // Exit edit mode after saving
        } else {
          alert("Failed to update employee details.");
        }
      } catch (error) {
        console.log(error);
        alert("An error occurred while updating employee details.");
      }
    },
  },
};
</script>

<style scoped>
img {
  object-fit: cover;
}
</style>
