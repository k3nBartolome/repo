<template>
  <div class="flex flex-col items-center p-4 bg-gray-100 min-h-screen">
    <!-- Profile Card -->
    <div
      class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md sm:max-w-xl lg:max-w-3xl"
    >
      <!-- Header -->
      <div class="flex items-center justify-between border-b pb-4">
        <!-- Employee Name and Position -->
        <div class="flex-1 text-left">
          <h2 class="text-xl font-bold text-gray-800">
            {{ employee_data.full_name }}
          </h2>
          <p class="text-sm text-gray-500">
            {{ employee_data.account_associate }}
          </p>
        </div>
        <!-- QR Code as Employee File -->
        <div class="ml-4">
          <a :href="employee_data.employee_qr_code_url" download>
            <img
              class="w-20 h-20 sm:w-24 sm:h-24 aspect-square object-cover rounded-lg border-4 border-blue-500"
              :src="employee_data.employee_qr_code_url"
              alt="QR Code Not Generated"
            />
          </a>
        </div>
      </div>

      <!-- Details Section -->
      <div class="mt-6 space-y-6">
        <!-- Personal Information -->
        <div>
          <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">
            Employee Information
          </h3>
          <div class="mt-2 space-y-2">
            <div class="flex justify-between flex-wrap">
              <span class="text-sm font-medium text-gray-600"
                >Employee ID:</span
              >
              <span class="text-sm text-gray-800">{{
                employee_data.employee_id
              }}</span>
            </div>
            <div class="flex justify-between flex-wrap">
              <span class="text-sm font-medium text-gray-600"
                >Contact Number:</span
              >
              <span class="text-sm text-gray-800">{{
                employee_data.contact_number
              }}</span>
            </div>
            <div class="flex justify-between flex-wrap">
              <span class="text-sm font-medium text-gray-600"
                >Email Address:</span
              >
              <span class="text-sm text-gray-800">{{
                employee_data.email
              }}</span>
            </div>
            <div class="flex justify-between flex-wrap">
              <span class="text-sm font-medium text-gray-600"
                >Date of Birth:</span
              >
              <span class="text-sm text-gray-800">{{
                employee_data.birthdate
              }}</span>
            </div>
            <div class="flex justify-between flex-wrap">
              <span class="text-sm font-medium text-gray-600">Hired Date:</span>
              <span class="text-sm text-gray-800">{{
                employee_data.hired_date
              }}</span>
            </div>
            <div class="flex justify-between flex-wrap">
              <span class="text-sm font-medium text-gray-600"
                >Employee Status:</span
              >
              <span class="text-sm text-gray-800">{{
                employee_data.status
              }}</span>
            </div>
            <div class="flex justify-between flex-wrap">
              <span class="text-sm font-medium text-gray-600"
                >Employment Status:</span
              >
              <span class="text-sm text-gray-800">{{
                employee_data.employment_status
              }}</span>
            </div>
          </div>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">
            Lob Information
          </h3>
          <div class="mt-2 space-y-2" v-if="lob_data && lob_data.length > 0">
            <div class="flex justify-between flex-wrap">
              <span class="text-sm font-medium text-gray-600">Region:</span>
              <span class="text-sm text-gray-800">{{
                lob_data[0].region
              }}</span>
            </div>
            <div class="flex justify-between flex-wrap">
              <span class="text-sm font-medium text-gray-600">Site:</span>
              <span class="text-sm text-gray-800">{{ lob_data[0].site }}</span>
            </div>
            <div class="flex justify-between flex-wrap">
              <span class="text-sm font-medium text-gray-600">Lob:</span>
              <span class="text-sm text-gray-800">{{ lob_data[0].lob }}</span>
            </div>
            <div class="flex justify-between flex-wrap">
              <span class="text-sm font-medium text-gray-600">Team Name:</span>
              <span class="text-sm text-gray-800">{{
                lob_data[0].team_name
              }}</span>
            </div>
            <div class="flex justify-between flex-wrap">
              <span class="text-sm font-medium text-gray-600"
                >Project Code:</span
              >
              <span class="text-sm text-gray-800">{{
                lob_data[0].project_code
              }}</span>
            </div>
          </div>
          <div v-else>
            <p class="text-gray-500">No data available.</p>
          </div>
        </div>

        <!-- NBI Details (Hidable Section) -->
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">NBI Details</h3>
            <button
              @click="toggleNbiDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showNbiDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showNbiDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">NBI File:</h4>
                <a
                  v-if="requirements_data[0]?.nbi_file_url"
                  :href="requirements_data[0]?.nbi_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.nbi_file_url"
                    alt="NBI File"
                  />
                </a>
                <span v-else>No NBI File Available</span>
              </div>

              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Status:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.nbi_final_status
                }}</span>
              </div>

              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Validity Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.nbi_validity_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Printed Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.nbi_printed_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Submitted Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.nbi_submitted_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.nbi_remarks
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.nbi_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.nbi_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">
              Drug Test Details
            </h3>
            <button
              @click="toggleDtDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showDtDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showDtDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">DT File:</h4>
                <a
                  v-if="requirements_data[0]?.dt_file_url"
                  :href="requirements_data[0]?.dt_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.dt_file_url"
                    alt="DT File"
                  />
                </a>
                <span v-else>No DT File Available</span>
              </div>

              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Status:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dt_final_status
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Results Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dt_results_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Transaction Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dt_transaction_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Endorsed Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dt_endorsed_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dt_remarks
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dt_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dt_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>
        <!-- PEME Details -->
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">PEME Details</h3>
            <button
              @click="togglePemeDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showPemeDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showPemeDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">PEME File:</h4>
                <a
                  v-if="requirements_data[0]?.peme_file_url"
                  :href="requirements_data[0]?.peme_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.peme_file_url"
                    alt="PEME File"
                  />
                </a>
                <span v-else>No PEME File Available</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.peme_remarks
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Endorsed Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.peme_endorsed_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Results Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.peme_results_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Transaction Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.peme_transaction_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Final Status:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.peme_final_status
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.peme_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.peme_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>
        <!-- SSS Details -->
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">SSS Details</h3>
            <button
              @click="toggleSssDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showSssDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showSssDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">SSS File:</h4>
                <a
                  v-if="requirements_data[0]?.sss_file_url"
                  :href="requirements_data[0]?.sss_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.sss_file_url"
                    alt="SSS File"
                  />
                </a>
                <span v-else>No SSS File Available</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >SSS Number:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sss_number
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Proof Submitted Type:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sss_proof_submitted_type
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sss_remarks
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Final Status:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sss_final_status
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Submitted Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sss_submitted_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sss_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sss_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>

        <!-- PHIC Details -->
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">PHIC Details</h3>
            <button
              @click="togglePhicDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showPhicDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showPhicDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">PHIC File:</h4>
                <a
                  v-if="requirements_data[0]?.phic_file_url"
                  :href="requirements_data[0]?.phic_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.phic_file_url"
                    alt="PHIC File"
                  />
                </a>
                <span v-else>No PHIC File Available</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >PHIC Number:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.phic_number
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Proof Submitted Type:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.phic_proof_submitted_type
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.phic_remarks
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Final Status:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.phic_final_status
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Submitted Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.phic_submitted_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.phic_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.phic_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>

        <!-- Pag-IBIG Details -->
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">
              Pag-IBIG Details
            </h3>
            <button
              @click="togglePagibigDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showPagibigDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showPagibigDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">
                  Pag-IBIG File:
                </h4>
                <a
                  v-if="requirements_data[0]?.pagibig_file_url"
                  :href="requirements_data[0]?.pagibig_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.pagibig_file_url"
                    alt="Pag-IBIG File"
                  />
                </a>
                <span v-else>No Pag-IBIG File Available</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Pag-IBIG Number:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.pagibig_number
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Proof Submitted Type:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.pagibig_proof_submitted_type
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.pagibig_remarks
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Final Status:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.pagibig_final_status
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Submitted Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.pagibig_submitted_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.pagibig_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.pagibig_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>

        <!-- TIN Details -->
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">TIN Details</h3>
            <button
              @click="toggleTinDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showTinDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showTinDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">TIN File:</h4>
                <a
                  v-if="requirements_data[0]?.tin_file_url"
                  :href="requirements_data[0]?.tin_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.tin_file_url"
                    alt="TIN File"
                  />
                </a>
                <span v-else>No TIN File Available</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >TIN Number:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.tin_number
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Proof Submitted Type:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.tin_proof_submitted_type
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.tin_remarks
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Final Status:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.tin_final_status
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Submitted Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.tin_submitted_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.tin_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.tin_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>
        <!-- Health Certificate Details -->
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">
              Health Certificate Details
            </h3>
            <button
              @click="toggleHealthCertificateDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showHealthCertificateDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showHealthCertificateDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">
                  Health Certificate File:
                </h4>
                <a
                  v-if="requirements_data[0]?.health_certificate_file_url"
                  :href="requirements_data[0]?.health_certificate_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.health_certificate_file_url"
                    alt="Health Certificate File"
                  />
                </a>
                <span v-else>No Health Certificate File Available</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Final Status:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.health_certificate_final_status
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Submitted Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.health_certificate_submitted_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Validity Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.health_certificate_validity_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.health_certificate_remarks
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.health_certificate_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.health_certificate_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>

        <!-- Occupational Permit Details -->
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">
              Occupational Permit Details
            </h3>
            <button
              @click="toggleOccupationalPermitDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showOccupationalPermitDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showOccupationalPermitDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">
                  Occupational Permit File:
                </h4>
                <a
                  v-if="requirements_data[0]?.occupational_permit_file_url"
                  :href="requirements_data[0]?.occupational_permit_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.occupational_permit_file_url"
                    alt="Occupational Permit File"
                  />
                </a>
                <span v-else>No Occupational Permit File Available</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Final Status:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.occupational_permit_final_status
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Submitted Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.occupational_permit_submitted_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Validity Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.occupational_permit_validity_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.occupational_permit_remarks
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.occupational_permit_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.occupational_permit_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>

        <!-- OFAC Details -->
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">OFAC Details</h3>
            <button
              @click="toggleOfacDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showOfacDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showOfacDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">OFAC File:</h4>
                <a
                  v-if="requirements_data[0]?.ofac_file_url"
                  :href="requirements_data[0]?.ofac_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.ofac_file_url"
                    alt="OFAC File"
                  />
                </a>
                <span v-else>No OFAC File Available</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Final Status:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.ofac_final_status
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Checked Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.ofac_checked_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.ofac_remarks
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.ofac_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.ofac_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>

        <!-- SAM Details -->
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">SAM Details</h3>
            <button
              @click="toggleSamDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showSamDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showSamDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">SAM File:</h4>
                <a
                  v-if="requirements_data[0]?.sam_file_url"
                  :href="requirements_data[0]?.sam_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.sam_file_url"
                    alt="SAM File"
                  />
                </a>
                <span v-else>No SAM File Available</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Final Status:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sam_final_status
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Checked Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sam_checked_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sam_remarks
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sam_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sam_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>

        <!-- OIG Details -->
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">OIG Details</h3>
            <button
              @click="toggleOigDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showOigDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showOigDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">OIG File:</h4>
                <a
                  v-if="requirements_data[0]?.oig_file_url"
                  :href="requirements_data[0]?.oig_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.oig_file_url"
                    alt="OIG File"
                  />
                </a>
                <span v-else>No OIG File Available</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Final Status:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.oig_final_status
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Checked Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.oig_checked_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.oig_remarks
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.oig_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.oig_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>

        <!-- CIBI Details -->
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">CIBI Details</h3>
            <button
              @click="toggleCibiDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showCibiDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showCibiDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">CIBI File:</h4>
                <a
                  v-if="requirements_data[0]?.cibi_file_url"
                  :href="requirements_data[0]?.cibi_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.cibi_file_url"
                    alt="CIBI File"
                  />
                </a>
                <span v-else>No CIBI File Available</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Final Status:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.cibi_final_status
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Checked Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.cibi_checked_date
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.cibi_remarks
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.cibi_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between flex-wrap">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.cibi_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">BGC Details</h3>
            <button
              @click="toggleBGCDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showBGCDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showBGCDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">BGC File:</h4>
                <a
                  v-if="requirements_data[0]?.bgc_file_url"
                  :href="requirements_data[0]?.bgc_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.bgc_file_url"
                    alt="BGC File"
                  />
                </a>
                <span v-else>No BGC File Available</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Endorsed Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.bgc_endorsed_date
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Results Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.bgc_results_date
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Final Status:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.bgc_final_status
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.bgc_remarks
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.bgc_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.bgc_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">
              Birth Certificate Details
            </h3>
            <button
              @click="toggleBCDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showBCDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showBCDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">
                  Birth Certificate File:
                </h4>
                <a
                  v-if="requirements_data[0]?.bc_file_url"
                  :href="requirements_data[0]?.bc_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.bc_file_url"
                    alt="Birth Certificate File"
                  />
                </a>
                <span v-else>No Birth Certificate File Available</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Submitted Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.bc_submitted_date
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Proof Type:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.bc_proof_type
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.bc_remarks
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.bc_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.bc_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">
              Dependent Birth Certificate Details
            </h3>
            <button
              @click="toggleDBCDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showDBCDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showDBCDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">
                  Dependent Birth Certificate File:
                </h4>
                <a
                  v-if="requirements_data[0]?.dbc_file_url"
                  :href="requirements_data[0]?.dbc_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.dbc_file_url"
                    alt="Dependent Birth Certificate File"
                  />
                </a>
                <span v-else
                  >No Dependent Birth Certificate File Available</span
                >
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Submitted Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dbc_submitted_date
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Proof Type:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dbc_proof_type
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dbc_remarks
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dbc_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dbc_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">
              Dependent Birth Certificate Details
            </h3>
            <button
              @click="toggleDBCDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showDBCDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showDBCDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">
                  Dependent Birth Certificate File:
                </h4>
                <a
                  v-if="requirements_data[0]?.dbc_file_url"
                  :href="requirements_data[0]?.dbc_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.dbc_file_url"
                    alt="Dependent Birth Certificate File"
                  />
                </a>
                <span v-else
                  >No Dependent Birth Certificate File Available</span
                >
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Submitted Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dbc_submitted_date
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Proof Type:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dbc_proof_type
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dbc_remarks
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dbc_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.dbc_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">
              Marriage Certificate Details
            </h3>
            <button
              @click="toggleMCDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showMCDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showMCDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">
                  Marriage Certificate File:
                </h4>
                <a
                  v-if="requirements_data[0]?.mc_file_url"
                  :href="requirements_data[0]?.mc_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.mc_file_url"
                    alt="Marriage Certificate File"
                  />
                </a>
                <span v-else>No Marriage Certificate File Available</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Submitted Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.mc_submitted_date
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Proof Type:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.mc_proof_type
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.mc_remarks
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.mc_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.mc_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">
              Scholastic Record Details
            </h3>
            <button
              @click="toggleSRDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showSRDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showSRDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">
                  Scholastic Record File:
                </h4>
                <a
                  v-if="requirements_data[0]?.sr_file_url"
                  :href="requirements_data[0]?.sr_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.sr_file_url"
                    alt="Scholastic Record File"
                  />
                </a>
                <span v-else>No Scholastic Record File Available</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Submitted Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sr_submitted_date
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Proof Type:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sr_proof_type
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sr_remarks
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sr_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sr_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">
              Previous Employment Details
            </h3>
            <button
              @click="togglePEDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showPEDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showPEDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">
                  Previous Employment File:
                </h4>
                <a
                  v-if="requirements_data[0]?.pe_file_url"
                  :href="requirements_data[0]?.pe_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.pe_file_url"
                    alt="Previous Employment File"
                  />
                </a>
                <span v-else>No Previous Employment File Available</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Submitted Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.pe_submitted_date
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Proof Type:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.pe_proof_type
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.pe_remarks
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.pe_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.pe_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>
        <div>
          <div class="flex items-center justify-between border-b pb-2">
            <h3 class="text-lg font-semibold text-gray-700">
              Supporting Documents Details
            </h3>
            <button
              @click="toggleSDDetails"
              class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
            >
              {{ showSDDetails ? "Hide" : "Show" }}
            </button>
          </div>
          <transition name="fade">
            <div v-if="showSDDetails" class="mt-2 space-y-2">
              <div>
                <h4 class="text-sm font-medium text-gray-600">
                  Supporting Documents File:
                </h4>
                <a
                  v-if="requirements_data[0]?.sd_file_url"
                  :href="requirements_data[0]?.sd_file_url"
                  download
                >
                  <img
                    class="w-full h-48 sm:h-64 rounded-lg mt-2 border border-gray-300"
                    :src="requirements_data[0]?.sd_file_url"
                    alt="Supporting Documents File"
                  />
                </a>
                <span v-else>No Supporting Documents File Available</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Submitted Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sd_submitted_date
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Proof Type:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sd_proof_type
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600">Remarks:</span>
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sd_remarks
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Last Updated Date:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sd_last_updated_at
                }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600"
                  >Updated By:</span
                >
                <span class="text-sm text-gray-800">{{
                  requirements_data[0]?.sd_updated_by
                }}</span>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      employee_data: {},
      lob_data: {},
      requirements_data: {},
      showNbiDetails: false,
      showDtDetails: false,
      showPemeDetails: false,
      showSssDetails: false,
      showPhicDetails: false,
      showTinDetails: false,
      showPagibigDetails: false,
      showHealthCertificateDetails: false,
      showOccupationalPermitDetails: false,
      showOfacDetails: false,
      showSamDetails: false,
      showOigDetails: false,
      showCibiDetails: false,
      showBGCDetails: false,
      showBCDetails: false,
      showDBCDetails: false,
      showMCDetails: false,
      showSRDetails: false,
      showPEDetails: false,
      showSDDetails: false,
    };
  },
  mounted() {
    this.fetchEmployeeData();
  },
  methods: {
    async fetchEmployeeData() {
      try {
        const response = await axios.get(
          `http://10.109.2.112:8000/api/show/employees/${this.$route.params.id}`
        );
        if (response.status === 200) {
          this.employee_data = response.data.employee_data || {};
          this.requirements_data = response.data.requirements_data || {};
          this.lob_data = response.data.lob_data || {};
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
    toggleNbiDetails() {
      this.showNbiDetails = !this.showNbiDetails;
    },
    toggleDtDetails() {
      this.showDtDetails = !this.showDtDetails;
    },
    togglePemeDetails() {
      this.showPemeDetails = !this.showPemeDetails;
    },
    toggleSssDetails() {
      this.showSssDetails = !this.showSssDetails;
    },
    togglePhicDetails() {
      this.showPhicDetails = !this.showPhicDetails;
    },
    togglePagibigDetails() {
      this.showPagibigDetails = !this.showPagibigDetails;
    },
    toggleTinDetails() {
      this.showTinDetails = !this.showTinDetails;
    },
    toggleHealthCertificateDetails() {
      this.showHealthCertificateDetails = !this.showHealthCertificateDetails;
    },
    toggleOccupationalPermitDetails() {
      this.showOccupationalPermitDetails = !this.showOccupationalPermitDetails;
    },
    toggleOfacDetails() {
      this.showOfacDetails = !this.showOfacDetails;
    },
    toggleSamDetails() {
      this.showSamDetails = !this.showSamDetails;
    },
    toggleOigDetails() {
      this.showOigDetails = !this.showOigDetails;
    },
    toggleCibiDetails() {
      this.showCibiDetails = !this.showCibiDetails;
    },
    toggleBGCDetails() {
      this.showBGCDetails = !this.showBGCDetails;
    },
    toggleBCDetails() {
      this.showBCDetails = !this.showBCDetails;
    },
    toggleDBCDetails() {
      this.showDBCDetails = !this.showDBCDetails;
    },
    toggleMCDetails() {
      this.showMCDetails = !this.showMCDetails;
    },
    toggleSRDetails() {
      this.showSRDetails = !this.showSRDetails;
    },
    togglePEDetails() {
      this.showPEDetails = !this.showPEDetails;
    },
    toggleSDDetails() {
      this.showSDDetails = !this.showSDDetails;
    },
  },
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
