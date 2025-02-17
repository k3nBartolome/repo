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
              @click="toggleEmployeeInfo"
              class="text-blue-500 hover:text-blue-700 focus:outline-none"
            >
              <svg
                v-if="isEmployeeInfo"
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
          v-if="isEmployeeInfo"
          class="px-4 py-4 border rounded-lg bg-gray-50"
        >
          <div
            class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 md:grid-cols-5"
          >
            <!-- Employee ID -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Employee ID</label
              >
              <input
                v-model="employee_id"
                type="text"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- First Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >First Name</label
              >
              <input
                v-model="first_name"
                type="text"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Last Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Last Name</label
              >
              <input
                v-model="last_name"
                type="text"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Middle Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Middle Name</label
              >
              <input
                v-model="middle_name"
                type="text"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Date of Birth -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Date of Birth</label
              >
              <input
                v-model="birthdate"
                type="date"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Email -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Email</label
              >
              <input
                v-model="email"
                type="email"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Contact Number -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Contact#</label
              >
              <input
                v-model="contact_number"
                type="text"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Hired Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Hired Date</label
              >
              <input
                v-model="hired_date"
                type="date"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Hired Month -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Hired Month</label
              >
              <select
                v-model="hired_month"
                disabled
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option>Select one</option>
                <option value="JAN">JAN</option>
                <option value="FEB">FEB</option>
                <option value="MAR">MAR</option>
                <option value="APR">APR</option>
                <option value="MAY">MAY</option>
                <option value="JUN">JUN</option>
                <option value="JUL">JUL</option>
                <option value="AUG">AUG</option>
                <option value="SEP">SEP</option>
                <option value="OCT">OCT</option>
                <option value="NOV">NOV</option>
                <option value="DEC">DEC</option>
              </select>
            </div>
            <!-- Employee Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Employee Status</label
              >
              <select
                v-model="employee_status"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option>Select one</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Separated">Separated</option>
              </select>
            </div>
            <!-- Employment Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Employment Status</label
              >
              <select
                v-model="employment_status"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option>Select one</option>
                <option value="Hired">Hired</option>
                <option value="To be Hired">To be Hired</option>
              </select>
            </div>
            <!-- Account Associate -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Position</label
              >
              <input
                v-model="account_associate"
                type="text"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Account Type -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Account Type</label
              >
              <select
                v-model="account_type"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option>Select one</option>
                <option value="Agent">Agent</option>
                <option value="Non-Agent">Non-Agent</option>
              </select>
            </div>
            <!-- Updated By -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Updated By</label
              >
              <input
                v-model="updated_by"
                type="text"
                disabled
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Last Updated At -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Last Updated At</label
              >
              <input
                v-model="updated_at"
                type="text"
                disabled
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
          </div>
        </div>
      </div>
      <!-- Workday Section -->
      <div class="mb-4">
        <div class="flex items-center justify-between mb-2">
          <span class="text-lg font-semibold">Workday Details</span>
          <button
            type="button"
            @click="toggleWorkdayInfo"
            class="text-blue-500 hover:text-blue-700 focus:outline-none"
          >
            <svg
              v-if="isWorkdayInfo"
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
          v-if="isWorkdayInfo"
          class="px-4 py-4 border rounded-lg bg-gray-50"
        >
          <div
            class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 md:grid-cols-6"
          >
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Workday ID</label
              >
              <input
                v-model="workday_id"
                type="text"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >RO Feedback</label
              >
              <input
                v-model="ro_feedback"
                type="text"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Per Findings</label
              >
              <input
                v-model="per_findings"
                type="text"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >201 Completion</label
              >
              <select
                v-model="completion"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option disabled>Please select one</option>
                <option value="COMPLETE">COMPLETE</option>
                <option value="WITH FINDINGS">WITH FINDINGS</option>
                <option value="PENDING">PENDING</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Contract Findings</label
              >
              <input
                v-model="contract_findings"
                type="text"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Contract Remarks</label
              >
              <select
                v-model="contract_remarks"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option disabled>Please select one</option>
                <option value="COMPLETE">COMPLETE</option>
                <option value="WITH FINDINGS">WITH FINDINGS</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Contract Status</label
              >
              <select
                v-model="contract_status"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option disabled>Please select one</option>
                <option value="DONE - WORKDAY">DONE - WORKDAY</option>
                <option value="DONE - MANUAL">DONE - MANUAL</option>
                <option value="PENDING">PENDING</option>
              </select>
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
            @click="toggleProgramInfo"
            class="text-blue-500 hover:text-blue-700 focus:outline-none"
          >
            <svg
              v-if="isProgramInfo"
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
              ></path>
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
          v-if="isProgramInfo"
          class="px-4 py-4 border rounded-lg bg-gray-50"
        >
          <div
            class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 md:grid-cols-6"
          >
            <!-- Region -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Region</label
              >
              <select
                v-model="region"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option disabled value="">Please select Site</option>
                <option
                  v-for="regions in regions"
                  :key="regions"
                  :value="regions"
                >
                  {{ regions }}
                </option>
              </select>
            </div>
            <!-- Site -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Site</label
              >
              <select
                v-model="site"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option disabled value="">Please select Site</option>
                <option v-for="site in sites" :key="site.id" :value="site.id">
                  {{ site.name }}
                </option>
              </select>
            </div>
            <!-- LOB -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >LOB</label
              >
              <input
                v-model="lob"
                type="text"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Team Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Team Name</label
              >
              <input
                v-model="team_name"
                type="text"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Project Code -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Project Code</label
              >
              <input
                v-model="project_code"
                type="text"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <!-- Compliance POC -->
            <div>
              <label class="block text-sm font-medium text-gray-700 truncate"
                >Compliance POC</label
              >
              <input
                v-model="compliance_poc"
                type="text"
                :disabled="!isEditable"
                class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
          </div>
        </div>
      </div>
      <!-- NBI -->
      <div class="mb-4">
        <!-- Header with Toggle Button -->
        <div class="flex items-center justify-between mb-2">
          <span class="text-lg font-semibold text-gray-800">NBI Details</span>
          <button
            type="button"
            @click="toggleNbiInfo"
            class="p-1 text-blue-500 rounded-full hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          >
            <svg
              v-if="isNbiInfo"
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

        <!-- NBI Info Section -->
        <div v-if="isNbiInfo" class="p-4 border rounded-lg bg-gray-50">
          <!-- Flex Container for Form and Image -->
          <div class="flex flex-col gap-4 lg:flex-row">
            <!-- Form Section on the Left -->
            <div class="w-full lg:w-1/3">
              <!-- Grid for Input Fields -->
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Final Status -->
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 truncate"
                    >Final Status</label
                  >
                  <select
                    v-model="nbi_final_status"
                    @change="updateNBIData"
                    class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option disabled>Please select one</option>
                    <option value="YES">Yes</option>
                    <option value="NO">No</option>
                    <option value="NO - HIT RECEIPT">NO - HIT RECEIPT</option>
                    <option value="QUALITY CONTROL">QUALITY CONTROL</option>
                  </select>
                </div>

                <!-- Validity Date -->
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 truncate"
                    >Validity Date</label
                  >
                  <input
                    v-model="nbi_validity_date"
                    type="date"
                    @change="updateNBIData"
                    class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>

                <!-- Printed Date -->
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 truncate"
                    >Printed Date</label
                  >
                  <input
                    v-model="nbi_printed_date"
                    type="date"
                    @change="updateNBIData"
                    class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>

                <!-- Submitted Date -->
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 truncate"
                    >Submitted Date</label
                  >
                  <input
                    v-model="nbi_submitted_date"
                    type="date"
                    @change="updateNBIData"
                    class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>

                <!-- Remarks -->
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 truncate"
                    >Remarks</label
                  >
                  <input
                    v-model="nbi_remarks"
                    type="text"
                    @input="updateNBIData"
                    class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>

                <!-- NBI Proof Upload -->
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 truncate"
                    >NBI Proof</label
                  >
                  <input
                    type="file"
                    @change="uploadNbiImage"
                    class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>
              </div>
            </div>

            <!-- Image Preview on the Right -->
            <div
              v-if="nbi_file_name"
              class="flex items-center justify-center w-full lg:w-2/3"
            >
              <img
                :src="nbi_file_name"
                alt="Preview Image"
                class="object-contain w-full h-full max-h-[230px] border rounded-lg shadow-md"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="mb-4">
        <!-- Header with Toggle Button -->
        <div class="flex items-center justify-between mb-2">
          <span class="text-lg font-semibold text-gray-800">DT Details</span>
          <button
            type="button"
            @click="toggleDtInfo"
            class="p-1 text-blue-500 rounded-full hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          >
            <svg
              v-if="isDtInfo"
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

        <!-- DT Info Section -->
        <div v-if="isDtInfo" class="p-4 border rounded-lg bg-gray-50">
          <!-- Flex Container for Form and Image -->
          <div class="flex flex-col gap-4 lg:flex-row">
            <!-- Form Section on the Left -->
            <div class="w-full lg:w-1/3">
              <!-- Grid for Input Fields -->
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Final Status -->
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 truncate"
                    >Final Status</label
                  >
                  <select
                    v-model="dt_final_status"
                    @change="updateDTData"
                    class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option disabled>Please select one</option>
                    <option value="NEGATIVE">NEGATIVE</option>
                    <option value="POSITIVE - FOR CONFIRMATORY">
                      POSITIVE - FOR CONFIRMATORY
                    </option>
                    <option value="(BLANK)">(BLANK)</option>
                  </select>
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 truncate"
                    >Transaction Date</label
                  >
                  <input
                    v-model="dt_transaction_date"
                    @change="updateDTData"
                    type="date"
                    class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 truncate"
                    >Result Date</label
                  >
                  <input
                    v-model="dt_results_date"
                    @change="updateDTData"
                    type="date"
                    class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 truncate"
                    >Endorsed Date</label
                  >
                  <input
                    v-model="dt_endorsed_date"
                    @change="updateDTData"
                    type="date"
                    class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 truncate"
                    >Remarks</label
                  >
                  <input
                    v-model="dt_remarks"
                    type="text"
                    @input="updateDTData"
                    class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 truncate"
                    >DT Proof</label
                  >
                  <input
                    type="file"
                    @change="uploadImage"
                    class="w-full p-2 mt-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                </div>
              </div>
            </div>
            <div
              v-if="dt_file_name"
              class="flex items-center justify-center w-full lg:w-2/3"
            >
              <img
                :src="dt_file_name"
                alt="Preview Image"
                class="object-contain w-full h-full max-h-[230px] border rounded-lg shadow-md"
              />
            </div>
          </div>
        </div>
      </div>
    </form>

    <!-- Sticky Save Button -->
    <div class="sticky-save-button">
      <button
        v-if="isEditable"
        type="submit"
        class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
      >
        Save Details
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
      regions: [],
      employee_id: "",
      employee_status: "",
      first_name: "",
      middle_name: "",
      last_name: "",
      hired_date: "",
      birthdate: "",
      contact_number: "",
      email: "",
      account_associate: "",
      account_type: "",
      employment_status: "",
      updated_by: "",
      updated_at: "",
      hired_month: "",
      region: "",
      site: "",
      lob: "",
      team_name: "",
      project_code: "",
      compliance_poc: "",
      workday_id: "",
      ro_feedback: "",
      per_findings: "",
      completion: "",
      contract_findings: "",
      contract_remarks: "",
      contract_status: "",
      nbi_final_status: null,
      nbi_validity_date: "",
      nbi_submitted_date: "",
      nbi_printed_date: "",
      nbi_remarks: "",
      nbi_file_name: null,
      nbi_proof: null,
      dt_final_status: "",
      dt_results_date: "",
      dt_transaction_date: "",
      dt_endorsed_date: "",
      dt_remarks: "",
      dt_file_name: null,
      dt_proof: null, // Used for the proof file or image data
      isEmployeeInfo: true,
      isWorkdayInfo: false,
      isProgramInfo: false,
      isNbiInfo: true,
      isDtInfo: true,
      isEditable: false,
    };
  },

  watch: {
    hired_date(newValue) {
      if (newValue) {
        // Convert the date string to a Date object
        const date = new Date(newValue);
        // Extract the month and map it to the corresponding short name
        const months = [
          "JAN",
          "FEB",
          "MAR",
          "APR",
          "MAY",
          "JUN",
          "JUL",
          "AUG",
          "SEP",
          "OCT",
          "NOV",
          "DEC",
        ];
        this.hired_month = months[date.getMonth()];
      } else {
        this.hired_month = "Select one"; // Reset if no date is selected
      }
    },
  },
  mounted() {
    this.fetchEmployeeData();
    this.getSites();
    this.getRegion();
  },
  methods: {
    uploadNbiImage(event) {
      const file = event.target.files[0];
      if (file) {
        this.nbi_proof = file; // Store the file in dt_proof

        const reader = new FileReader();
        reader.onload = () => {
          this.nbi_file_name = reader.result; // Preview the image
        };
        reader.readAsDataURL(file); // Preview file
      }
    },
    uploadDtImage(event) {
      const file = event.target.files[0];
      if (file) {
        this.dt_proof = file; // Store the file in dt_proof

        const reader = new FileReader();
        reader.onload = () => {
          this.dt_file_name = reader.result; // Preview the image
        };
        reader.readAsDataURL(file); // Preview file
      }
    },
    async getRegion() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/regions", {
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
    async getSites() {
      try {
        const token = this.$store.state.token; // Retrieve the token from Vuex store
        const response = await axios.get("http://127.0.0.1:8000/api/sites", {
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
    async fetchEmployeeData() {
      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/get/employees/${this.$route.params.id}`
        );
        const data = response.data.data;

        // Populate the form fields with API response data
        this.employee_id = data.employee_id || "";
        this.employee_status = data.employee_status || "";
        this.first_name = data.first_name || "";
        this.middle_name = data.middle_name || "";
        this.last_name = data.last_name || "";
        this.hired_date = data.hired_date || "";
        this.birthdate = data.birthdate || "";
        this.contact_number = data.contact_number || "";
        this.email = data.email || "";
        this.account_associate = data.account_associate || "";
        this.account_type = data.account_type || "";
        this.employment_status = data.employment_status || "";
        this.updated_by = data.updated_by || "";
        this.updated_at = data.updated_at || "";
        this.hired_month = data.hired_month || "";
        this.region = data.region || "";
        this.site = data.site || "";
        this.lob = data.lob || "";
        this.team_name = data.team_name || "";
        this.project_code = data.project_code || "";
        this.compliance_poc = data.compliance_poc || "";
        this.workday_id = data.workday_id || "";
        this.ro_feedback = data.ro_feedback || "";
        this.per_findings = data.per_findings || "";
        this.completion = data.completion || "";
        this.contract_findings = data.contract_findings || "";
        this.contract_remarks = data.contract_remarks || "";
        this.contract_status = data.contract_status || "";
        this.nbi_final_status = data.nbi_final_status;
        this.nbi_validity_date = data.nbi_validity_date;
        this.nbi_submitted_date = data.nbi_submitted_date;
        this.nbi_printed_date = data.nbi_printed_date;
        this.nbi_remarks = data.nbi_remarks;
        this.nbi_file_name = data.nbi_file_name;
        this.dt_final_status = data.dt_final_status || "";
        this.dt_results_date = data.dt_results_date || "";
        this.dt_transaction_date = data.dt_transaction_date || "";
        this.dt_endorsed_date = data.dt_endorsed_date || "";
        this.dt_remarks = data.dt_remarks || "";
        this.dt_file_name = data.dt_file_name || null;
      } catch (error) {
        console.error("Error fetching NBI data:", error);
      }
    },
    toggleEmployeeInfo() {
      this.isEmployeeInfo = !this.isEmployeeInfo;
    },

    toggleWorkdayInfo() {
      this.isWorkdayInfo = !this.isWorkdayInfo;
    },
    toggleProgramInfo() {
      this.isProgramInfo = !this.isProgramInfo;
    },
    toggleNbiInfo() {
      this.isNbiInfo = !this.isNbiInfo;
    },
    toggleDtInfo() {
      this.isDtInfo = !this.isDtInfo;
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
.sticky-save-button {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: white; /* Optional: Add background color */
  padding: 1rem;
  text-align: right;
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1); /* Optional: Add shadow for better visibility */
  z-index: 1000; /* Ensure it stays on top of other content */
}
</style>
