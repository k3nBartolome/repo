<template>
  <div class="p-4">
    <!-- Section 1 -->
    <div class="mb-4">
      <div class="flex items-center justify-between mb-2">
        <span class="text-lg font-semibold">Employee Details</span>
        <button @click="toggleSection1" class="text-blue-500 underline">
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
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Last Name</label>
            <input
              v-model="last_name"
              type="text"
              class="p-2 mt-1 border rounded"
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
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Contact#</label>
            <input
              v-model="contact_number"
              type="text"
              class="p-2 mt-1 border rounded"
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Employee ID</label>
            <input
              v-model="employee_id"
              type="text"
              class="p-2 mt-1 border rounded"
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Hired Date</label>
            <input
              v-model="hired_date"
              type="date"
              class="p-2 mt-1 border rounded"
            />
          </div>
        </div>
        <div class="grid grid-cols-4 gap-4">
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Hired Month</label>
            <input
              v-model="hired_month"
              type="email"
              class="p-2 mt-1 border rounded"
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Employee Status</label>
            <input
              v-model="employee_status"
              type="text"
              class="p-2 mt-1 border rounded"
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

    <!-- Section 2 -->
    <div class="mb-4">
      <div class="flex items-center justify-between mb-2">
        <span class="text-lg font-semibold">Programs</span>
        <button @click="toggleSection2" class="text-blue-500 underline">
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
        <button @click="toggleSection3" class="text-blue-500 underline">
          {{ isSection3Visible ? "Hide" : "Show" }}
        </button>
      </div>
      <div v-if="isSection3Visible" class="p-4 border rounded-lg bg-gray-50">
        <div class="mb-4">
          <div class="flex items-center justify-between mb-2">
            <span class="text-lg font-semibold">NBI</span>
            <button @click="toggleNBI" class="text-blue-500 underline">
              {{ isNBIVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isNBIVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-4 gap-4">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">NBI </label>
                <input type="file" class="p-2 mt-1 border rounded" />
              </div>
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
            <button @click="toggleDT" class="text-blue-500 underline">
              {{ isDTVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isDTVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-4 gap-4">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">DT Proof</label>
                <input type="file" class="p-2 mt-1 border rounded" />
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
                  v-model="dt_result_date"
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
            <button @click="togglePHIC" class="text-blue-500 underline">
              {{ isPHICVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isPHICVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-4 gap-4">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">PHIC Proof</label>
                <input type="file" class="p-2 mt-1 border rounded" />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">type of Proof</label>
                <input
                  v-model="type_of_proof"
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
            <button @click="toggleSSS" class="text-blue-500 underline">
              {{ isSSSVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isSSSVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-4 gap-4">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">SSS Proof</label>
                <input type="file" class="p-2 mt-1 border rounded" />
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
                  v-model="sss_proof"
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
            <button @click="toggleHDMF" class="text-blue-500 underline">
              {{ isHDMFVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isHDMFVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-4 gap-4">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">HDMF Proof</label>
                <input type="file" class="p-2 mt-1 border rounded" />
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
                  v-model="hdmf_proof"
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
            <button @click="toggleTIN" class="text-blue-500 underline">
              {{ isTINVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isTINVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-4 gap-4">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">TIN Proof</label>
                <input type="file" class="p-2 mt-1 border rounded" />
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
                  v-model="tin_proof"
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
            <button @click="toggleBGC" class="text-blue-500 underline">
              {{ isBGCVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isBGCVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-5 gap-5">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">BGC Proof</label>
                <input type="file" class="p-2 mt-1 border rounded" />
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
            <button @click="togglePEME" class="text-blue-500 underline">
              {{ isPEMEVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isPEMEVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-3 gap3">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">PEME Proof</label>
                <input type="file" class="p-2 mt-1 border rounded" />
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
            <button @click="toggleBIR" class="text-blue-500 underline">
              {{ isBIRVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isBIRVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-5 gap-5">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">1902 Proof</label>
                <input type="file" class="p-2 mt-1 border rounded" />
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
                <label class="block text-sm font-medium">1902 Attachment</label>
                <input
                  v-model="attachment_1902"
                  type="text"
                  class="p-2 mt-1 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">2316 Proof</label>
                <input type="file" class="p-2 mt-1 border rounded" />
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
            <button @click="toggleNSO" class="text-blue-500 underline">
              {{ isNSOVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isNSOVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-6 gap-6">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">NSO Proof</label>
                <input type="file" class="p-2 mt-1 border rounded" />
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
                <input type="file" class="p-2 mt-1 border rounded" />
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
                <input type="file" class="p-2 mt-1 border rounded" />
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
            <button @click="toggleVaccine" class="text-blue-500 underline">
              {{ isVaccineVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isVaccineVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-6 gap-6">
              <div class="flex flex-col">
                <label class="block text-sm font-medium"
                  >Vaccination Proof</label
                >
                <input type="file" class="p-2 mt-1 border rounded" />
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
                <input type="file" class="p-2 mt-1 border rounded" />
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
            <button @click="togglePhoto" class="text-blue-500 underline">
              {{ isPhotoVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isPhotoVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-6 gap-6">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">With 2x2 Photo</label>
                <input
                  v-model="vaccination_card"
                  type="text"
                  class="p-2 mt-1 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">2x2 Photo</label>
                <input type="file" class="p-2 mt-1 border rounded" />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">1st valid ID</label>
                <input
                  v-model="vaccination_card"
                  type="text"
                  class="p-2 mt-1 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium"
                  >1st Valid ID Proof</label
                >
                <input type="file" class="p-2 mt-1 border rounded" />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">2nd valid ID</label>
                <input
                  v-model="vaccination_card"
                  type="text"
                  class="p-2 mt-1 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium"
                  >2nd Valid ID Proof</label
                >
                <input type="file" class="p-2 mt-1 border rounded" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      //employee
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
      nbi_file_path: "",
      dt_results: "",
      dt_transaction_date: "",
      dt_result_date: "",
      dt_file_name: "",
      dt_file_path: "",
      peme_status: "",
      peme_remarks: "",
      peme_file_name: "",
      peme_file_path: "",
      bgc: "",
      bgc_remarks: "",
      bgc_endorsed_date: "",
      bgc_received_date: "",
      bgc_file_name: "",
      bgc_file_path: "",
      vendor: "",
      sss_number: "",
      sss_remarks: "",
      sss_file_name: "",
      sss_file_path: "",
      phic_number: "",
      phic_remarks: "",
      phic_proof: "",
      phic_file_name: "",
      phic_file_path: "",
      hdmf_number: "",
      hdmf_remarks: "",
      hdmf_proof: "",
      hdmf_file_name: "",
      hdmf_file_path: "",
      tin: "",
      tin_remarks: "",
      tin_proof: "",
      tin_file_name: "",
      tin_file_path: "",
      form_1902: "",
      attachment_1902: "",
      form_1902_file_name: "",
      form_1902_file_path: "",
      health_certificate: "",
      health_certificate_file_name: "",
      health_certificate_file_path: "",
      vaccination_card: "",
      first_dose_date: "",
      second_dose_date: "",
      vaccination_card_file_name: "",
      vaccination_card_file_path: "",
      signed_bank_form: "",
      type_of_first_valid_id: "",
      type_of_second_valid_id: "",
      two_by_two: "",
      two_by_two_file_name: "",
      two_by_two_file_path: "",
      form_2316: "",
      form_2316_file_name: "",
      form_2316_file_path: "",
      nso_birth_certificate: "",
      nso_birth_certificate_file_name: "",
      nso_birth_certificate_file_path: "",
      dependents_nso_birth_certificate: "",
      dependents_nso_birth_certificate_file_name: "",
      dependents_nso_birth_certificate_file_path: "",
      marriage_certificate: "",
      marriage_certificate_file_name: "",
      marriage_certificate_file_path: "",
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
    };
  },
  methods: {
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
  },
};
</script>

<style scoped>
/* Additional styling if needed */
</style>
