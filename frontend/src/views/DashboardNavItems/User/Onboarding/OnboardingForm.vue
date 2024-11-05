<template>
  <div class="p-4">
    <!-- Section 1 -->
    <div class="mb-4">
      <div class="flex justify-between items-center mb-2">
        <span class="font-semibold text-lg">Employee Details</span>
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
              class="mt-1 p-2 border rounded"
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Last Name</label>
            <input
              v-model="last_name"
              type="text"
              class="mt-1 p-2 border rounded"
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Middle Name</label>
            <input
              v-model="middle_name"
              type="text"
              class="mt-1 p-2 border rounded"
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Date of Birth</label>
            <input
              v-model="birthdate"
              type="date"
              class="mt-1 p-2 border rounded"
            />
          </div>
        </div>
        <div class="grid grid-cols-4 gap-4">
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Email</label>
            <input
              v-model="email"
              type="email"
              class="mt-1 p-2 border rounded"
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Contact#</label>
            <input
              v-model="contact_number"
              type="text"
              class="mt-1 p-2 border rounded"
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Employee ID</label>
            <input
              v-model="employee_id"
              type="text"
              class="mt-1 p-2 border rounded"
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Hired Date</label>
            <input
              v-model="hired_date"
              type="date"
              class="mt-1 p-2 border rounded"
            />
          </div>
        </div>
        <div class="grid grid-cols-4 gap-4">
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Hired Month</label>
            <input
              v-model="hired_month"
              type="email"
              class="mt-1 p-2 border rounded"
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Employee Status</label>
            <input
              v-model="employee_status"
              type="text"
              class="mt-1 p-2 border rounded"
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Account Associate</label>
            <input
              v-model="account_associate"
              type="text"
              class="mt-1 p-2 border rounded"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Section 2 -->
    <div class="mb-4">
      <div class="flex justify-between items-center mb-2">
        <span class="font-semibold text-lg">Programs</span>
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
              class="mt-1 p-2 border rounded"
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Site</label>
            <input
              v-model="site"
              type="email"
              class="mt-1 p-2 border rounded"
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Lob</label>
            <input v-model="lob" type="tel" class="mt-1 p-2 border rounded" />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Team Name</label>
            <input
              v-model="team_name"
              type="text"
              class="mt-1 p-2 border rounded"
            />
          </div>
          <div class="flex flex-col">
            <label class="block text-sm font-medium">Project Code</label>
            <input
              v-model="project_code"
              type="text"
              class="mt-1 p-2 border rounded"
            />
          </div>
        </div>
      </div>
    </div>

    <div class="mb-4">
      <div class="flex justify-between items-center mb-2">
        <span class="font-semibold text-lg">Requirements</span>
        <button @click="toggleSection3" class="text-blue-500 underline">
          {{ isSection3Visible ? "Hide" : "Show" }}
        </button>
      </div>
      <div v-if="isSection3Visible" class="p-4 border rounded-lg bg-gray-50">
        <div class="mb-4">
          <div class="flex justify-between items-center mb-2">
            <span class="font-semibold text-lg">NBI</span>
            <button @click="toggleNBI" class="text-blue-500 underline">
              {{ isNBIVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isNBIVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-5 gap-5">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Region</label>
                <input
                  v-model="region"
                  type="email"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Site</label>
                <input
                  v-model="site"
                  type="email"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Lob</label>
                <input
                  v-model="lob"
                  type="tel"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Team Name</label>
                <input
                  v-model="team_name"
                  type="text"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Project Code</label>
                <input
                  v-model="project_code"
                  type="text"
                  class="mt-1 p-2 border rounded"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="mb-4">
          <div class="flex justify-between items-center mb-2">
            <span class="font-semibold text-lg">PHIC</span>
            <button @click="togglePHIC" class="text-blue-500 underline">
              {{ isPHICVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isPHICVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-5 gap-5">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Region</label>
                <input
                  v-model="region"
                  type="email"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Site</label>
                <input
                  v-model="site"
                  type="email"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Lob</label>
                <input
                  v-model="lob"
                  type="tel"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Team Name</label>
                <input
                  v-model="team_name"
                  type="text"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Project Code</label>
                <input
                  v-model="project_code"
                  type="text"
                  class="mt-1 p-2 border rounded"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="mb-4">
          <div class="flex justify-between items-center mb-2">
            <span class="font-semibold text-lg">SSS</span>
            <button @click="toggleSSS" class="text-blue-500 underline">
              {{ isSSSVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isSSSVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-5 gap-5">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Region</label>
                <input
                  v-model="region"
                  type="email"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Site</label>
                <input
                  v-model="site"
                  type="email"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Lob</label>
                <input
                  v-model="lob"
                  type="tel"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Team Name</label>
                <input
                  v-model="team_name"
                  type="text"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Project Code</label>
                <input
                  v-model="project_code"
                  type="text"
                  class="mt-1 p-2 border rounded"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="mb-4">
          <div class="flex justify-between items-center mb-2">
            <span class="font-semibold text-lg">HDMF</span>
            <button @click="toggleHDMF" class="text-blue-500 underline">
              {{ isHDMFVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isHDMFVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-5 gap-5">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Region</label>
                <input
                  v-model="region"
                  type="email"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Site</label>
                <input
                  v-model="site"
                  type="email"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Lob</label>
                <input
                  v-model="lob"
                  type="tel"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Team Name</label>
                <input
                  v-model="team_name"
                  type="text"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Project Code</label>
                <input
                  v-model="project_code"
                  type="text"
                  class="mt-1 p-2 border rounded"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="mb-4">
          <div class="flex justify-between items-center mb-2">
            <span class="font-semibold text-lg">TIN</span>
            <button @click="toggleTIN" class="text-blue-500 underline">
              {{ isTINVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isTINVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-5 gap-5">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Region</label>
                <input
                  v-model="region"
                  type="email"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Site</label>
                <input
                  v-model="site"
                  type="email"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Lob</label>
                <input
                  v-model="lob"
                  type="tel"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Team Name</label>
                <input
                  v-model="team_name"
                  type="text"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Project Code</label>
                <input
                  v-model="project_code"
                  type="text"
                  class="mt-1 p-2 border rounded"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="mb-4">
          <div class="flex justify-between items-center mb-2">
            <span class="font-semibold text-lg">BGC</span>
            <button @click="toggleBGC" class="text-blue-500 underline">
              {{ isBGCVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isBGCVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-5 gap-5">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Region</label>
                <input
                  v-model="region"
                  type="email"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Site</label>
                <input
                  v-model="site"
                  type="email"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Lob</label>
                <input
                  v-model="lob"
                  type="tel"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Team Name</label>
                <input
                  v-model="team_name"
                  type="text"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Project Code</label>
                <input
                  v-model="project_code"
                  type="text"
                  class="mt-1 p-2 border rounded"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="mb-4">
          <div class="flex justify-between items-center mb-2">
            <span class="font-semibold text-lg">DT</span>
            <button @click="toggleDT" class="text-blue-500 underline">
              {{ isDTVisible ? "Hide" : "Show" }}
            </button>
          </div>
          <div v-if="isDTVisible" class="p-4 border rounded-lg bg-gray-50">
            <div class="grid grid-cols-5 gap-5">
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Region</label>
                <input
                  v-model="region"
                  type="email"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Site</label>
                <input
                  v-model="site"
                  type="email"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Lob</label>
                <input
                  v-model="lob"
                  type="tel"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Team Name</label>
                <input
                  v-model="team_name"
                  type="text"
                  class="mt-1 p-2 border rounded"
                />
              </div>
              <div class="flex flex-col">
                <label class="block text-sm font-medium">Project Code</label>
                <input
                  v-model="project_code"
                  type="text"
                  class="mt-1 p-2 border rounded"
                />
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
      // Visibility states for each section
      isSection1Visible: true,
      isSection2Visible: false,
      isSection3Visible: false,
      isNBIVisible: false,
      isSSSVisible: false,
      isPHICVisible: false,
      isHDMFVisible: false,
      isTINVisible: false,
      isBGCVisible: false,
      isDTVisible: false,
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
  },
};
</script>

<style scoped>
/* Additional styling if needed */
</style>
