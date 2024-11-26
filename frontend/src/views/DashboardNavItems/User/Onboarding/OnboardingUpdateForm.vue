<template>
  <div class="p-4">
    <form @submit.prevent="updateEmployee">
      <div class="mb-4">
        <div class="flex items-center justify-between mb-2">
          <span class="text-lg font-semibold">Employee Details</span>
          <button
            type="button"
            @click="toggleSection1"
            class="text-blue-500 underline"
          >
            {{ isSection1Visible ? "Hide" : "Show" }}
          </button>
        </div>

        <div v-if="isSection1Visible" class="p-4 border rounded-lg bg-gray-50">
          <div class="grid grid-cols-4 gap-4">
            <div class="flex flex-col">
              <label class="block text-sm font-medium">First Name</label>
              <input
                v-model="first_name"
                type="text"
                class="p-2 mt-1 border rounded"
                readonly
              />
            </div>
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Last Name</label>
              <input
                v-model="last_name"
                type="text"
                class="p-2 mt-1 border rounded"
                readonly
              />
            </div>
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Middle Name</label>
              <input
                v-model="middle_name"
                type="text"
                class="p-2 mt-1 border rounded"
              />
            </div>
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Date of Birth</label>
              <input
                v-model="birthdate"
                type="date"
                class="p-2 mt-1 border rounded"
                readonly
              />
            </div>
          </div>

          <div class="grid grid-cols-4 gap-4">
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Email</label>
              <input
                v-model="email"
                type="email"
                class="p-2 mt-1 border rounded"
                readonly
              />
            </div>
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Contact#</label>
              <input
                v-model="contact_number"
                type="text"
                class="p-2 mt-1 border rounded"
                readonly
              />
            </div>
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Employee ID</label>
              <input
                v-model="employee_id"
                type="text"
                class="p-2 mt-1 border rounded"
                readonly
              />
            </div>
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Hired Date</label>
              <input
                v-model="hired_date"
                type="date"
                class="p-2 mt-1 border rounded"
                readonly
              />
            </div>
          </div>

          <div class="grid grid-cols-4 gap-4">
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Hired Month</label>
              <input
                v-model="hired_month"
                type="text"
                class="p-2 mt-1 border rounded"
                required
              />
            </div>
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Employee Status</label>
              <input
                v-model="employee_status"
                type="text"
                class="p-2 mt-1 border rounded"
                readonly
              />
            </div>
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Account Associate</label>
              <input
                v-model="account_associate"
                type="text"
                class="p-2 mt-1 border rounded"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="mb-4">
        <div class="flex items-center justify-between mb-2">
          <span class="text-lg font-semibold">Programs</span>
          <button
            type="button"
            @click="toggleSection2"
            class="text-blue-500 underline"
          >
            {{ isSection2Visible ? "Hide" : "Show" }}
          </button>
        </div>

        <div v-if="isSection2Visible" class="p-4 border rounded-lg bg-gray-50">
          <div class="grid grid-cols-5 gap-5">
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Region</label>
              <input
                v-model="region"
                type="email"
                class="p-2 mt-1 border rounded"
              />
            </div>
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Site</label>
              <input
                v-model="site"
                type="email"
                class="p-2 mt-1 border rounded"
              />
            </div>
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Lob</label>
              <input v-model="lob" type="tel" class="p-2 mt-1 border rounded" />
            </div>
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Team Name</label>
              <input
                v-model="team_name"
                type="text"
                class="p-2 mt-1 border rounded"
              />
            </div>
            <div class="flex flex-col">
              <label class="block text-sm font-medium">Project Code</label>
              <input
                v-model="project_code"
                type="text"
                class="p-2 mt-1 border rounded"
              />
            </div>
          </div>
        </div>
      </div>

      <div class="mb-4">
        <div class="flex items-center justify-between mb-2">
          <span class="text-lg font-semibold">Requirements</span>
          <button
            type="button"
            @click="toggleSection3"
            class="text-blue-500 underline"
          >
            {{ isSection3Visible ? "Hide" : "Show" }}
          </button>
        </div>
        <div v-if="isSection3Visible" class="p-4 border rounded-lg bg-gray-50">
          <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-lg font-semibold">NBI</span>
              <button
                type="button"
                @click="toggleNBI"
                class="text-blue-500 underline"
              >
                {{ isNBIVisible ? "Hide" : "Show" }}
              </button>
            </div>

            <div v-if="isNBIVisible" class="p-4 border rounded-lg bg-gray-50">
              <div class="grid grid-cols-4 gap-4">
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">NBI </label>
                  <input
                    type="file"
                    @change="nbiFile"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <!-- <div class="flex flex-col">
                  <img
                    :src="previewNBI"
                    v-if="previewNBI"
                    alt="Image Preview"
                    class="object-cover w-20 h-20 border-2 rounded-lg"
                  />
                </div> -->
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">Validity Date</label>
                  <input
                    v-model="nbi_validity_date"
                    type="date"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">Printed Date</label>
                  <input
                    v-model="nbi_printed_date"
                    type="date"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">Remarks</label>
                  <input
                    v-model="nbi_remarks"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
              </div>
            </div>
          </div>

          <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-lg font-semibold">DT</span>
              <button
                type="button"
                @click="toggleDT"
                class="text-blue-500 underline"
              >
                {{ isDTVisible ? "Hide" : "Show" }}
              </button>
            </div>

            <div v-if="isDTVisible" class="p-4 border rounded-lg bg-gray-50">
              <div class="grid grid-cols-4 gap-4">
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">DT Proof</label>
                  <input
                    type="file"
                    @change="handleDT"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium"
                    >Transaction Date</label
                  >
                  <input
                    v-model="dt_transaction_date"
                    type="date"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">Result Date</label>
                  <input
                    v-model="dt_results_date"
                    type="date"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">Result</label>
                  <input
                    v-model="dt_results"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-lg font-semibold">PHIC</span>
              <button
                type="button"
                @click="togglePHIC"
                class="text-blue-500 underline"
              >
                {{ isPHICVisible ? "Hide" : "Show" }}
              </button>
            </div>

            <div v-if="isPHICVisible" class="p-4 border rounded-lg bg-gray-50">
              <div class="grid grid-cols-4 gap-4">
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">PHIC Proof</label>
                  <input
                    type="file"
                    @change="handlePHIC"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">type of Proof</label>
                  <input
                    v-model="phic_file_name"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">PHIC#</label>
                  <input
                    v-model="phic_number"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">PHIC Remarks</label>
                  <input
                    v-model="phic_remarks"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-lg font-semibold">SSS</span>
              <button
                type="button"
                @click="toggleSSS"
                class="text-blue-500 underline"
              >
                {{ isSSSVisible ? "Hide" : "Show" }}
              </button>
            </div>

            <div v-if="isSSSVisible" class="p-4 border rounded-lg bg-gray-50">
              <div class="grid grid-cols-4 gap-4">
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">SSS Proof</label>
                  <input
                    type="file"
                    @change="handleSSS"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">SSS #</label>
                  <input
                    v-model="sss_number"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">SSS Remarks</label>
                  <input
                    v-model="sss_remarks"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">Proof type</label>
                  <input
                    v-model="sss"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-lg font-semibold">HDMF</span>
              <button
                type="button"
                @click="toggleHDMF"
                class="text-blue-500 underline"
              >
                {{ isHDMFVisible ? "Hide" : "Show" }}
              </button>
            </div>

            <div v-if="isHDMFVisible" class="p-4 border rounded-lg bg-gray-50">
              <div class="grid grid-cols-4 gap-4">
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">HDMF Proof</label>
                  <input
                    type="file"
                    @change="handleHDMF"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">HDMF #</label>
                  <input
                    v-model="hdmf_number"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">HDMF Remarks</label>
                  <input
                    v-model="hdmf_remarks"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">Proof type</label>
                  <input
                    v-model="hdmf_file_name"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-lg font-semibold">TIN</span>
              <button
                type="button"
                @click="toggleTIN"
                class="text-blue-500 underline"
              >
                {{ isTINVisible ? "Hide" : "Show" }}
              </button>
            </div>

            <div v-if="isTINVisible" class="p-4 border rounded-lg bg-gray-50">
              <div class="grid grid-cols-4 gap-4">
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">TIN Proof</label>
                  <input
                    type="file"
                    @change="handleTIN"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">TIN #</label>
                  <input
                    v-model="tin"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">TIN Remarks</label>
                  <input
                    v-model="tin_remarks"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">Proof type</label>
                  <input
                    v-model="tin_file_name"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-lg font-semibold">BGC</span>
              <button
                type="button"
                @click="toggleBGC"
                class="text-blue-500 underline"
              >
                {{ isBGCVisible ? "Hide" : "Show" }}
              </button>
            </div>

            <div v-if="isBGCVisible" class="p-4 border rounded-lg bg-gray-50">
              <div class="grid grid-cols-5 gap-5">
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">BGC Proof</label>
                  <input
                    type="file"
                    @change="handleBGC"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">BGC</label>
                  <input
                    v-model="bgc"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">BGC Remarks</label>
                  <input
                    v-model="bgc_remarks"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">Endorsed Date</label>
                  <input
                    v-model="bgc_endorsed_date"
                    type="date"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">Received Date</label>
                  <input
                    v-model="bgc_received_date"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-lg font-semibold">PEME</span>
              <button
                type="button"
                @click="togglePEME"
                class="text-blue-500 underline"
              >
                {{ isPEMEVisible ? "Hide" : "Show" }}
              </button>
            </div>

            <div v-if="isPEMEVisible" class="p-4 border rounded-lg bg-gray-50">
              <div class="grid grid-cols-3 gap3">
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">PEME Proof</label>
                  <input
                    type="file"
                    @change="handlePEME"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">PEME</label>
                  <input
                    v-model="peme_status"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">PEME Remarks</label>
                  <input
                    v-model="peme_remarks"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-lg font-semibold">BIR Forms</span>
              <button
                type="button"
                @click="toggleBIR"
                class="text-blue-500 underline"
              >
                {{ isBIRVisible ? "Hide" : "Show" }}
              </button>
            </div>

            <div v-if="isBIRVisible" class="p-4 border rounded-lg bg-gray-50">
              <div class="grid grid-cols-5 gap-5">
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">1902 Proof</label>
                  <input
                    type="file"
                    @change="handleform1902"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">1902 Form</label>
                  <input
                    v-model="form_1902"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium"
                    >1902 Attachment</label
                  >
                  <input
                    v-model="attachment_1902"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">2316 Proof</label>
                  <input
                    type="file"
                    @change="handleform1902"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">2316 Form</label>
                  <input
                    v-model="form_2316"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-lg font-semibold"
                >NSO and Marriage Certificate</span
              >
              <button
                type="button"
                @click="toggleNSO"
                class="text-blue-500 underline"
              >
                {{ isNSOVisible ? "Hide" : "Show" }}
              </button>
            </div>

            <div v-if="isNSOVisible" class="p-4 border rounded-lg bg-gray-50">
              <div class="grid grid-cols-6 gap-6">
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">NSO Proof</label>
                  <input
                    type="file"
                    @change="handleNSO"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">NSO</label>
                  <input
                    v-model="nso_birth_certificate"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium"
                    >Dependent NSO Proof</label
                  >
                  <input
                    type="file"
                    @change="handleDNSO"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">Dependent NSO</label>
                  <input
                    v-model="dependents_nso_birth_certificate"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium"
                    >Marriage Certificate Proof</label
                  >
                  <input
                    type="file"
                    @change="handleMC"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium"
                    >Marriage Certificate</label
                  >
                  <input
                    v-model="marriage_certificate"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-lg font-semibold"
                >Vaccination and Health Certificate</span
              >
              <button
                type="button"
                @click="toggleVaccine"
                class="text-blue-500 underline"
              >
                {{ isVaccineVisible ? "Hide" : "Show" }}
              </button>
            </div>

            <div
              v-if="isVaccineVisible"
              class="p-4 border rounded-lg bg-gray-50"
            >
              <div class="grid grid-cols-6 gap-6">
                <div class="flex flex-col">
                  <label class="block text-sm font-medium"
                    >Vaccination Proof</label
                  >
                  <input
                    type="file"
                    @change="handleVP"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium"
                    >Vaccination Card</label
                  >
                  <input
                    v-model="vaccination_card"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">1st Dose Date</label>
                  <input
                    v-model="first_dose_date"
                    type="date"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">2nd Dose Date</label>
                  <input
                    v-model="second_dose_date"
                    type="date"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium"
                    >Health Certificate Proof</label
                  >
                  <input
                    type="file"
                    @change="handleHC"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium"
                    >Health Certificate</label
                  >
                  <input
                    v-model="health_certificate"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-lg font-semibold">Picture and Valid IDs</span>
              <button
                type="button"
                @click="togglePhoto"
                class="text-blue-500 underline"
              >
                {{ isPhotoVisible ? "Hide" : "Show" }}
              </button>
            </div>

            <div v-if="isPhotoVisible" class="p-4 border rounded-lg bg-gray-50">
              <div class="grid grid-cols-6 gap-6">
                <div class="flex flex-col">
                  <label class="block text-sm font-medium"
                    >With 2x2 Photo</label
                  >
                  <input
                    v-model="two_by_two"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">2x2 Photo</label>
                  <input
                    type="file"
                    @change="handleTWO"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">1st valid ID</label>
                  <input
                    v-model="type_of_first_valid_id"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium"
                    >1st Valid ID Proof</label
                  >
                  <input
                    type="file"
                    @change="handleFIRST"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium">2nd valid ID</label>
                  <input
                    v-model="type_of_second_valid_id"
                    type="text"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
                <div class="flex flex-col">
                  <label class="block text-sm font-medium"
                    >2nd Valid ID Proof</label
                  >
                  <input
                    type="file"
                    @change="handleSECOND"
                    class="p-2 mt-1 border rounded"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-4 text-right">
        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded">
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
      //employee
      employee: null,
      employee_id: "",
      last_name: "",
      first_name: "",
      middle_name: "",
      employee_status: "",
      hired_date: "",
      hired_month: "",
      birthdate: "",
      contact_number: "",
      email: "",
      account_associate: "",
      employee_added_by: "",
      //lob
      region: "",
      site: "",
      lob: "",
      team_name: "",
      project_code: "",
      //requirements
      nbi: "",
      nbi_remarks: "",
      nbi_validity_date: "",
      nbi_printed_date: "",
      nbi_file_name: "",
      nbi_file_path: null,
      dt_results: "",
      dt_transaction_date: "",
      dt_results_date: "",
      dt_file_name: "",
      dt_file_path: null,
      peme_status: "",
      peme_remarks: "",
      peme_file_name: "",
      peme_file_path: null,
      bgc: "",
      bgc_remarks: "",
      bgc_endorsed_date: "",
      bgc_received_date: "",
      bgc_file_name: "",
      bgc_file_path: null,
      vendor: "",
      sss: "",
      sss_number: "",
      sss_remarks: "",
      sss_file_name: "",
      sss_file_path: null,
      phic_number: "",
      phic_remarks: "",
      phic_file_name: "",
      phic_file_path: null,
      hdmf_number: "",
      hdmf_remarks: "",
      hdmf_file_name: "",
      hdmf_file_path: null,
      tin: "",
      tin_remarks: "",
      tin_file_name: "",
      tin_file_path: null,
      form_1902: "",
      attachment_1902: "",
      form_1902_file_name: "",
      form_1902_file_path: null,
      health_certificate: "",
      health_certificate_file_name: "",
      health_certificate_file_path: null,
      vaccination_card: "",
      first_dose_date: "",
      second_dose_date: "",
      vaccination_card_file_name: "",
      vaccination_card_file_path: null,
      signed_bank_form: "",
      type_of_first_valid_id: "",
      type_of_second_valid_id: "",
      type_of_first_valid_id_file_name: "",
      type_of_second_valid_id_file_name: "",
      type_of_first_valid_id_file_path: null,
      type_of_second_valid_id_file_path: null,
      two_by_two: "",
      two_by_two_file_name: "",
      two_by_two_file_path: null,
      form_2316: "",
      form_2316_file_name: "",
      form_2316_file_path: null,
      nso_birth_certificate: "",
      nso_birth_certificate_file_name: "",
      nso_birth_certificate_file_path: null,
      dependents_nso_birth_certificate: "",
      dependents_nso_birth_certificate_file_name: "",
      dependents_nso_birth_certificate_file_path: null,
      marriage_certificate: "",
      marriage_certificate_file_name: "",
      marriage_certificate_file_path: null,
      cibi: "",
      cibi_search_date: "",
      ofac: "",
      sam: "",
      oig: "",
      month_milestone: "",
      week_ending: "",
      fifteenth_day_deadline: "",
      end_of_product_training: "",
      past_due: "",
      on_track: "",
      nbi_dt: "",
      job_offer_letter: "",
      interview_form_compliance: "",
      tmp_notes: "",
      tmp_ii: "",
      tmp_id: "",
      tmp_ov: "",
      tmp_status: "",
      jo_hr_contract_compliance: "",
      data_privacy_form: "",
      undertaking_non_employment_agreement: "",
      addendum_att: "",
      addendum_language_assessment: "",
      social_media_policy: "",
      contract_tmp_remarks: "",
      endorsed_by_hs: "",
      endorsed_to_compliance: "",
      return_to_hs_with_findings: "",
      last_received_from_hs_with_findings: "",
      status_201: "",
      compliance_remarks: "",
      with_findings: "",
      transmittal_to_act_hris_email_subject_sent: "",
      act_hris_remarks: "",
      // Visibility states for each section
      isSection1Visible: true,
      isSection2Visible: false,
      isSection3Visible: true,
      isNBIVisible: false,
      isSSSVisible: false,
      isPHICVisible: false,
      isHDMFVisible: false,
      isTINVisible: false,
      isBGCVisible: false,
      isDTVisible: false,
      isPEMEVisible: false,
      isBIRVisible: false,
      isNSOVisible: false,
      isVaccineVisible: false,
      isPhotoVisible: false,
      nbiFile: null,
      previewNBI: null,
      dtFile: null,
      previewDT: null,
      phicFile: null,
      previewPHIC: null,
      sssFile: null,
      previewSSS: null,
      hdmfFile: null,
      previewHDMF: null,
      tinFile: null,
      previewTIN: null,
      bgcFile: null,
      previewBGC: null,
      pemeFile: null,
      previewPEME: null,
      form1902File: null,
      previewform1902: null,
      form2316File: null,
      previewform2316: null,
      nsoFile: null,
      previewNSO: null,
      dnsoFile: null,
      previewDNSO: null,
      vpFile: null,
      previewVP: null,
      hcFile: null,
      previewHC: null,
      twoFile: null,
      previewTWO: null,
      firstFile: null,
      previewFIRST: null,
      secondFile: null,
      previewSecond: null,
      mcFile: null,
      previewMC: null,
    };
  },
  mounted() {
    this.getEmployee();
  },
  methods: {
    async getEmployee() {
      try {
        const token = this.$store.state.token;
        const headers = {
          Authorization: `Bearer ${token}`,
        };

        const response = await axios.get(
          `https://10.236.103.190/api/employees/${this.$route.params.id}`,
          { headers }
        );

        if (response.status === 200) {
          const EmpObj = response.data.employee; // Now accessing 'employee' from the response

          // Update the data fields
          this.employee_id = EmpObj.employee_id;
          this.last_name = EmpObj.last_name;
          this.first_name = EmpObj.first_name;
          this.middle_name = EmpObj.middle_name;
          this.employee_status = EmpObj.employee_status;
          this.hired_date = EmpObj.hired_date;
          this.hired_month = EmpObj.hired_month;
          this.birthdate = EmpObj.birthdate;
          this.contact_number = EmpObj.contact_number;
          this.email = EmpObj.email;
          this.account_associate = EmpObj.account_associate;

          console.log(EmpObj); // Log to check the structure of the response
        } else {
          console.log("Error fetching employee data");
        }
      } catch (error) {
        console.log(error);
      }
    },

    toggleSection1() {
      this.isSection1Visible = !this.isSection1Visible;
    },
    toggleSection2() {
      this.isSection2Visible = !this.isSection2Visible;
    },
    toggleSection3() {
      this.isSection3Visible = !this.isSection3Visible;
    },
    toggleNBI() {
      this.isNBIVisible = !this.isNBIVisible;
    },
    toggleSSS() {
      this.isSSSVisible = !this.isSSSVisible;
    },
    togglePHIC() {
      this.isPHICVisible = !this.isPHICVisible;
    },
    toggleHDMF() {
      this.isHDMFVisible = !this.isHDMFVisible;
    },
    toggleTIN() {
      this.isTINVisible = !this.isTINVisible;
    },
    toggleBGC() {
      this.isBGCVisible = !this.isBGCVisible;
    },
    toggleDT() {
      this.isDTVisible = !this.isDTVisible;
    },
    togglePEME() {
      this.isPEMEVisible = !this.isPEMEVisible;
    },
    toggleBIR() {
      this.isBIRVisible = !this.isBIRVisible;
    },
    toggleNSO() {
      this.isNSOVisible = !this.isNSOVisible;
    },
    toggleVaccine() {
      this.isVaccineVisible = !this.isVaccineVisible;
    },
    togglePhoto() {
      this.isPhotoVisible = !this.isPhotoVisible;
    },
    async handleNBI(event) {
      const nbiFile = event.target.files[0];
      if (!nbiFile) {
        return;
      }
      this.nbiFile = nbiFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (nbiFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.nbiFile = blob;
                  this.previewNBI = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewNBI);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(nbiFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewNBI = URL.createObjectURL(nbiFile);

        console.log("Preview Image URL:", this.previewNBI);
      }
    },
    async handleDT(event) {
      const dtFile = event.target.files[0];
      if (!dtFile) {
        return;
      }
      this.dtFile = dtFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (dtFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.dtFile = blob;
                  this.previewDT = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewDT);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(dtFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewDT = URL.createObjectURL(dtFile);

        console.log("Preview Image URL:", this.previewDT);
      }
    },
    async handlePHIC(event) {
      const phicFile = event.target.files[0];
      if (!phicFile) {
        return;
      }
      this.phicFile = phicFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (phicFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.phicFile = blob;
                  this.previewPHIC = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewPHIC);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(phicFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewPHIC = URL.createObjectURL(phicFile);

        console.log("Preview Image URL:", this.previewPHIC);
      }
    },
    async handleSSS(event) {
      const sssFile = event.target.files[0];
      if (!sssFile) {
        return;
      }
      this.sssFile = sssFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (sssFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.sssFile = blob;
                  this.previewSSS = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewSSS);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(sssFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewSSS = URL.createObjectURL(sssFile);

        console.log("Preview Image URL:", this.previewSSS);
      }
    },
    async handleHDMF(event) {
      const hdmfFile = event.target.files[0];
      if (!hdmfFile) {
        return;
      }
      this.hdmfFile = hdmfFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (hdmfFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.hdmfFile = blob;
                  this.previewHDMF = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewHDMF);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(hdmfFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewHDMF = URL.createObjectURL(hdmfFile);

        console.log("Preview Image URL:", this.previewHDMF);
      }
    },
    async handleTIN(event) {
      const tinFile = event.target.files[0];
      if (!tinFile) {
        return;
      }
      this.tinFile = tinFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (tinFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.tinFile = blob;
                  this.previewTIN = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewTIN);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(tinFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewTIN = URL.createObjectURL(tinFile);

        console.log("Preview Image URL:", this.previewTIN);
      }
    },
    async handleBGC(event) {
      const bgcFile = event.target.files[0];
      if (!bgcFile) {
        return;
      }
      this.bgcFile = bgcFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (bgcFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.bgcFile = blob;
                  this.previewBGC = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewBGC);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(bgcFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewBGC = URL.createObjectURL(bgcFile);

        console.log("Preview Image URL:", this.previewBGC);
      }
    },
    async handlePEME(event) {
      const pemeFile = event.target.files[0];
      if (!pemeFile) {
        return;
      }
      this.pemeFile = pemeFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (pemeFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.pemeFile = blob;
                  this.previewPEME = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewPEME);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(pemeFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewPEME = URL.createObjectURL(pemeFile);

        console.log("Preview Image URL:", this.previewPEME);
      }
    },
    async handleform1902(event) {
      const form1902File = event.target.files[0];
      if (!form1902File) {
        return;
      }
      this.form1902File = form1902File;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (form1902File.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.form1902File = blob;
                  this.previewform1902 = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewform1902);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(form1902File);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewform1902 = URL.createObjectURL(form1902File);

        console.log("Preview Image URL:", this.previewform1902);
      }
    },
    async handleform2316(event) {
      const form2316File = event.target.files[0];
      if (!form2316File) {
        return;
      }
      this.form2316File = form2316File;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (form2316File.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.form2316File = blob;
                  this.previewform2316 = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewform2316);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(form2316File);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewform2316 = URL.createObjectURL(form2316File);

        console.log("Preview Image URL:", this.previewform2316);
      }
    },
    async handleNSO(event) {
      const nsoFile = event.target.files[0];
      if (!nsoFile) {
        return;
      }
      this.nsoFile = nsoFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (nsoFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.nsoFile = blob;
                  this.previewNSO = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewNSO);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(nsoFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewNSO = URL.createObjectURL(nsoFile);

        console.log("Preview Image URL:", this.previewNSO);
      }
    },
    async handleDNSO(event) {
      const dnsoFile = event.target.files[0];
      if (!dnsoFile) {
        return;
      }
      this.dnsoFile = dnsoFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (dnsoFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.dnsoFile = blob;
                  this.previewDNSO = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewDNSO);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(dnsoFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewDNSO = URL.createObjectURL(dnsoFile);

        console.log("Preview Image URL:", this.previewDNSO);
      }
    },
    async handleMC(event) {
      const mcFile = event.target.files[0];
      if (!mcFile) {
        return;
      }
      this.mcFile = mcFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (mcFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.mcFile = blob;
                  this.previewMC = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewMC);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(mcFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewMC = URL.createObjectURL(mcFile);

        console.log("Preview Image URL:", this.previewMC);
      }
    },
    async handleVP(event) {
      const vpFile = event.target.files[0];
      if (!vpFile) {
        return;
      }
      this.vpFile = vpFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (vpFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.vpFile = blob;
                  this.previewVP = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewVP);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(vpFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewVP = URL.createObjectURL(vpFile);

        console.log("Preview Image URL:", this.previewVP);
      }
    },
    async handleHC(event) {
      const hcFile = event.target.files[0];
      if (!hcFile) {
        return;
      }
      this.hcFile = hcFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (hcFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.hcFile = blob;
                  this.previewHC = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewHC);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(hcFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewHC = URL.createObjectURL(hcFile);

        console.log("Preview Image URL:", this.previewHC);
      }
    },
    async handleTWO(event) {
      const twoFile = event.target.files[0];
      if (!twoFile) {
        return;
      }
      this.twoFile = twoFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (twoFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.twoFile = blob;
                  this.previewTWO = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewTWO);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(twoFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewTWO = URL.createObjectURL(twoFile);

        console.log("Preview Image URL:", this.previewTWO);
      }
    },
    async handleFIRST(event) {
      const firstFile = event.target.files[0];
      if (!firstFile) {
        return;
      }
      this.firstFile = firstFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (firstFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.firstFile = blob;
                  this.previewFIRST = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewFIRST);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(firstFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewFIRST = URL.createObjectURL(firstFile);

        console.log("Preview Image URL:", this.previewFIRST);
      }
    },
    async handleSECOND(event) {
      const secondFile = event.target.files[0];
      if (!secondFile) {
        return;
      }
      this.secondFile = secondFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (secondFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();
          reader.onload = (event) => {
            image.src = event.target.result;
            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }
              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);
              canvas.toBlob(
                async (blob) => {
                  this.secondFile = blob;
                  this.previewSECOND = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewSECOND);
                },
                "image/jpeg",
                quality
              );
            };
          };
          reader.readAsDataURL(secondFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        this.previewSECOND = URL.createObjectURL(secondFile);

        console.log("Preview Image URL:", this.previewSECOND);
      }
    },
    async compressBlob(blob, maxSize) {
      const image = new Image();
      const reader = new FileReader();
      const maxQuality = 0.8;

      const compressedBlob = await new Promise((resolve) => {
        reader.onload = (event) => {
          image.src = event.target.result;

          image.onload = () => {
            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d");

            let newWidth = image.width;
            let newHeight = image.height;

            if (image.size > maxSize) {
              const scaleFactor = Math.sqrt(image.size / maxSize);
              newWidth = Math.floor(image.width / scaleFactor);
              newHeight = Math.floor(image.height / scaleFactor);
            }

            canvas.width = newWidth;
            canvas.height = newHeight;

            ctx.drawImage(image, 0, 0, newWidth, newHeight);

            canvas.toBlob(resolve, "image/jpeg", maxQuality);
          };
        };
        reader.readAsDataURL(blob);
      });

      return compressedBlob;
    },
  },
};
</script>

<style scoped>
img {
  object-fit: cover;
}
</style>
