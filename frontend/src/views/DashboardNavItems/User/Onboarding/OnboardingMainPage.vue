<template>
  <div class="py-0">
  <div class="px-1 py-0 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
    <div
      class="fixed inset-0 z-50 flex items-center justify-center modal"
      v-if="showModalAdd"
    >
      <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
      <div
        class="w-full max-w-lg p-4 bg-white rounded shadow-lg modal-content sm:max-w-3xl sm:p-6"
      >
        <header class="flex items-center justify-between px-4 py-2 border-b-2 border-gray-200">
          <h2 class="text-lg font-semibold text-gray-800">Add Employee</h2>
          <button
            @click="showModalAdd = false"
            class="text-gray-600 hover:text-gray-800"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
        </header>
        <form
          @submit.prevent="addEmployees"
          class="grid grid-cols-1 gap-4 px-4 py-2 sm:grid-cols-2"
        >
            <div class="col-span-1">
              <label class="block">
                Employee ID
                <input
                  type="text"
                  v-model="employee_id"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-black dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Workday ID
                <input
                  type="text"
                  v-model="workday_id"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-black dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                First Name
                <input
                  type="text"
                  v-model="first_name"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-black dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Middle Name
                <input
                  type="text"
                  v-model="middle_name"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-black dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Last Name
                <input
                  type="text"
                  v-model="last_name"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-black dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>

            <div class="col-span-1">
              <label class="block">
                Email
                <input
                  type="text"
                  v-model="email"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-black dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Contact
                <input
                  type="text"
                  v-model="contact_number"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-black dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Date of Birth
                <input
                  type="date"
                  v-model="birthdate"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-black dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Employee Status
                <select
                  v-model="employee_status"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-black dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                >
                  <option>Select one</option>
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                  <option value="Separated">Separated</option>
                </select>
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Hired Date
                <input
                  type="date"
                  v-model="hired_date"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-black dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Hired Month
                <select
                  disabled
                  v-model="hired_month"
                  class="block w-full whitespace-nowrap display:none rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal text-neutral-500 bg-neutral-100 cursor-not-allowed dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-400"
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
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Account Type
                <select
                  v-model="account_type"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-black dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                >
                  <option>Select one</option>
                  <option value="Agent">Agent</option>
                  <option value="Non-Agent">Non-Agent</option>
                </select>
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Position
                <input
                  type="text"
                  v-model="account_associate"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-black dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Employment Status
                <select
                  v-model="employment_status"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-black dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                >
                  <option>Select one</option>
                  <option value="Hired">Hired</option>
                  <option value="To be Hired">To be Hired</option>
                </select>
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Site
                <select
                  v-model="sites_selected"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-black dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                >
                  <option disabled value="">Please select Site</option>
                  <option v-for="site in sites" :key="site.id" :value="site.id">
                    {{ site.name }}
                  </option>
                </select>
              </label>
            </div>

            <div class="flex justify-end mt-4">
              <button
                type="submit"
                class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
              >
                Submit
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
   <div class="py-0">
    <div class="px-1 py-0 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
      <div
        class="fixed inset-0 z-50 flex items-center justify-center modal"
        v-if="showModalImport"
      >
        <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
        <div class="max-w-sm p-4 bg-white rounded shadow-lg modal-content">
          <header class="px-4 py-2 border-b-2 border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Import Employees</h2>
          </header>
          <button
            @click="showModalImport = false"
            class="absolute top-0 right-0 m-4 text-gray-600 hover:text-gray-800"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
          <form
            @submit.prevent="submitBulkEmployees"
            class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-1"
          >
            <div>
              <label for="images" class="drop-container" id="dropcontainer">
                <span class="drop-title">Drop files here</span>
                or
                <input type="file" @change="handleFileChange" />
              </label>
            </div>
            <div class="flex justify-end mt-4">
              <button
                type="submit"
                class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
              >
                Submit
              </button>
            </div>
          </form>

          <!-- Download Template Button -->
          <div class="flex justify-center mt-4">
            <button
              @click="downloadTemplate"
              class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600"
            >
              Download Excel Template
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="px-4 border border-solid border-1">
    <div class="flex items-baseline space-x-2">
      <!-- <div class="">
        <router-link to="/h&s/screening" class="link-button">
          <button
            class="font-sans font-semibold text-black text-2xs svg-button"
          >
            <svg
              viewBox="-8.16 -8.16 50.32 50.32"
              xmlns="http://www.w3.org/2000/svg"
              fill="#f2740d"
              stroke="#f2740d"
            >
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke="#CCCCCC"
                stroke-width="0.544"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <g fill="none" fill-rule="evenodd">
                  <g>
                    <path
                      d="M1 1.993c0-.55.45-.993.995-.993h17.01c.55 0 1.34.275 1.776.625l3.44 2.75c.43.345.78 1.065.78 1.622v26.006c0 .55-.447.997-1 .997H2c-.552 0-1-.452-1-.993V1.993z"
                      stroke="##f2740d"
                      stroke-width="1.224"
                    ></path>
                    <g fill="#f2740d">
                      <path d="M8 12h12v1H8z"></path>
                      <path d="M6 12h1v1H6z"></path>
                      <path d="M6 15h1v1H6z"></path>
                      <path d="M8 15h12v1H8z"></path>
                      <path d="M6 18h1v1H6z"></path>
                      <path d="M8 18h12v1H8z"></path>
                      <path d="M6 21h1v1H6z"></path>
                      <path d="M8 21h12v1H8z"></path>
                    </g>
                    <path fill="##f2740d" d="M18 2h1v6h-1z"></path>
                    <path fill="##f2740d" d="M18 7h6v1h-6z"></path>
                  </g>
                </g>
              </g>
            </svg>
            Dashboard
          </button>
        </router-link>
      </div> -->

      <div class="">
        <router-link to="/onboarding_dashboard" class="link-button">
          <button class="font-sans font-semibold text-black text-2xs svg-button">
            <svg
              viewBox="-8.16 -8.16 50.32 50.32"
              xmlns="http://www.w3.org/2000/svg"
              fill="#f2740d"
              stroke="#f2740d"
            >
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke="#CCCCCC"
                stroke-width="0.544"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <g fill="none" fill-rule="evenodd">
                  <g>
                    <path
                      d="M1 1.993c0-.55.45-.993.995-.993h17.01c.55 0 1.34.275 1.776.625l3.44 2.75c.43.345.78 1.065.78 1.622v26.006c0 .55-.447.997-1 .997H2c-.552 0-1-.452-1-.993V1.993z"
                      stroke="##f2740d"
                      stroke-width="1.224"
                    ></path>
                    <g fill="#f2740d">
                      <path d="M8 12h12v1H8z"></path>
                      <path d="M6 12h1v1H6z"></path>
                      <path d="M6 15h1v1H6z"></path>
                      <path d="M8 15h12v1H8z"></path>
                      <path d="M6 18h1v1H6z"></path>
                      <path d="M8 18h12v1H8z"></path>
                      <path d="M6 21h1v1H6z"></path>
                      <path d="M8 21h12v1H8z"></path>
                    </g>
                    <path fill="##f2740d" d="M18 2h1v6h-1z"></path>
                    <path fill="##f2740d" d="M18 7h6v1h-6z"></path>
                  </g>
                </g>
              </g></svg
            >Hired
          </button>
        </router-link>
      </div>
    </div>
  </div>
  <div class="grid grid-cols-10 grid-rows-1 gap-2 px-4 py-2">
    <div class="flex space-x-4">
      <button
        class="flex items-center justify-center px-8 py-1 text-sm text-white truncate bg-blue-500 rounded-lg md:rounded-full md:text-base md:px-10 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300"
        @click="showModalAdd = true"
      >
        Add
      </button>

      <button
        class="flex items-center justify-center px-8 py-1 text-sm text-white truncate bg-blue-500 rounded-lg md:rounded-full md:text-base md:px-10 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300"
        @click="showModalImport = true"
      >
        Bulk
      </button>
    </div>
  </div>
  <main class="flex flex-col min-h-screen border border-gray-300">
    <!-- Main Content -->
    <div class="flex flex-1 px-4 py-2 overflow-y-auto md:px-6 md:py-4">
      <div class="w-full py-6">
        <router-view />
      </div>
    </div>
  </main>
</template>
<script>
import QRCode from "qrcode"; // Import the QRCode library
import { BrowserMultiFormatReader } from "@zxing/library";
import axios from "axios";
export default {
  data() {
    return {
      errors: {},
      sites: [],
      showModalAdd: false,
      showModalImport: false,
      file: null,
      employee_id: "TBA",
      workday_id: "TBA",
      last_name: "",
      first_name: "",
      middle_name: "",
      employee_status: "",
      hired_date: "",
      hired_month: "",
      sites_selected: "",
      birthdate: "",
      contact_number: "",
      email: "",
      account_associate: "Account Associate",
      account_type: "",
      employee_added_by: "",
      employment_status: "",
    };
  },
  mounted() {
    this.$router.afterEach(() => {
      window.location.reload();
    });
    this.getSites();
    this.qrReader = new BrowserMultiFormatReader();
  },

  computed: {
    isUser() {
      const userRole = this.$store.state.role;
      return userRole === "user";
    },
    isRemx() {
      const userRole = this.$store.state.role;
      return userRole === "remx";
    },
    isBudget() {
      const userRole = this.$store.state.role;
      return userRole === "budget";
    },
    isSourcing() {
      const userRole = this.$store.state.role;
      return userRole === "sourcing";
    },
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
  async created() {
    // Initialize the selected site based on Vuex state
    this.sites_selected = this.$store.state.site_id || ""; // Default to an empty string if site_id is not set

    // Fetch sites from the API
    await this.getSites();
  },
  methods: {
 async downloadTemplate() {
  try {
    const token = this.$store.state.token; // Retrieve the token from Vuex store

    // Send the GET request to the download endpoint
    const response = await axios.get('http://127.0.0.1:8000/api/download-template', {
      headers: {
        Authorization: `Bearer ${token}`, // Include token in the header for authentication
      },
      responseType: 'blob', // Important to handle file download (binary data)
    });

    if (response.status === 200) {
      // Create a URL for the blob data
      const url = window.URL.createObjectURL(new Blob([response.data]));

      // Create a temporary link to trigger the download
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', 'template.xlsx'); // The filename for the downloaded file
      document.body.appendChild(link);
      link.click(); // Trigger the download

      // Clean up the URL object after the download
      window.URL.revokeObjectURL(url);

      console.log('Template downloaded successfully');
    } else {
      console.log('Error downloading the template');
    }
  } catch (error) {
    console.error('Error while downloading the template:', error);
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
    async generateQRCode(employeeId, employeeEmail, employeeContactNumber) {
      try {
        // Validate inputs
        if (
          !employeeId ||
          (typeof employeeId !== "string" && typeof employeeId !== "number")
        ) {
          console.error("Invalid employee ID:", employeeId);
          return;
        }
        if (!employeeEmail || typeof employeeEmail !== "string") {
          console.error("Invalid employee email:", employeeEmail);
          return;
        }
        if (!employeeContactNumber || typeof employeeContactNumber !== "string") {
          console.error("Invalid employee contact number:", employeeContactNumber);
          return;
        }

        // Format QR code data without the word "id"
        const qrData = `${employeeId},${employeeEmail},${employeeContactNumber}`;

        // Generate QR code
        const qrCodeCanvas = await QRCode.toCanvas(qrData);
        const qrCodeDataUrl = qrCodeCanvas.toDataURL("image/png");

        // Convert to Blob and File
        const byteCharacters = atob(qrCodeDataUrl.split(",")[1]);
        const byteArrays = [];
        for (let offset = 0; offset < byteCharacters.length; offset += 1024) {
          const byteArray = new Uint8Array(1024);
          for (let i = 0; i < 1024 && offset + i < byteCharacters.length; i++) {
            byteArray[i] = byteCharacters[offset + i].charCodeAt(0);
          }
          byteArrays.push(byteArray);
        }
        const blob = new Blob(byteArrays, { type: "image/png" });
        const file = new File([blob], `qr_code_${employeeId}.png`, {
          type: "image/png",
        });

        // Prepare FormData for upload
        const formData = new FormData();
        formData.append("qr_code", file);
        formData.append("employee_id", employeeId);

        // Make API request
        const token = this.$store.state.token;
        const response = await axios.post(
          `http://127.0.0.1:8000/api/employees/${employeeId}/save-qr-code`,
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          console.log("QR code saved successfully:", response.data);

          // Update employee object with the QR code path
          const employee = this.employees.find((emp) => emp.id === employeeId);
          if (employee) {
            employee.qr_code_path = response.data.qr_code_path;
            this.$router.push("/onboarding_dashboard", () => {
              location.reload();
            });
          }
        }
      } catch (error) {
        console.error("Error generating and saving QR code:", error);
      }
    },
    handleFileChange(event) {
      this.file = event.target.files[0]; // Set the file from the input
    },

    async submitBulkEmployees() {
      if (!this.file) {
        this.errorMessage = "Please select a file to upload.";
        return;
      }

      this.loading = true;
      this.errorMessage = "";
      this.successMessage = "";

      const formData = new FormData();
      formData.append("file", this.file);
      formData.append("employee_added_by", this.$store.state.user_id);

      try {
        const token = this.$store.state.token;
        const response = await axios.post(
          "http://127.0.0.1:8000/api/upload_employees_bulk",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
              Authorization: `Bearer ${token}`,
            },
          }
        );
        this.successMessage = response.data.success;
        this.file = null; // Clear the file after successful upload
      } catch (error) {
        if (error.response && error.response.data) {
          this.errorMessage = error.response.data.error || "Error uploading file";
        } else {
          this.errorMessage = "Error uploading file";
        }
        console.error("Upload error:", error); // Log error for debugging
      } finally {
        this.loading = false;
        this.showModalImport = false;
      }
    },

    addEmployees() {
      this.loading = true;
      const formData = {
        employee_id: this.employee_id,
        workday_id: this.workday_id,
        last_name: this.last_name,
        first_name: this.first_name,
        middle_name: this.middle_name,
        employee_status: this.employee_status,
        hired_date: this.hired_date,
        hired_month: this.hired_month,
        birthdate: this.birthdate,
        contact_number: this.contact_number,
        email: this.email,
        account_type: this.account_type,
        account_associate: this.account_associate,
        employment_status: this.employment_status,
        employee_added_by: this.$store.state.user_id,
        site_id: this.sites_selected,
      };
      const token = this.$store.state.token;
      const headers = {
        Authorization: `Bearer ${token}`,
      };

      axios
        .post("http://127.0.0.1:8000/api/upload_employees", formData, {
          headers,
        })
        .then((response) => {
          console.log(response.data);
          this.employee_id = "";
          this.last_name = "";
          this.first_name = "";
          this.middle_name = "";
          this.employee_status = "";
          this.hired_date = "";
          this.hired_month = "";
          this.birthdate = "";
          this.contact_number = "";
          this.email = "";
          this.account_type = "";
          this.account_associate = "";
          this.employee_added_by = "";
          this.employment_status = "";
          this.$router.push("/onboarding_dashboard", () => {
            location.reload();
          });
        })
        .catch((error) => {
          console.log(error.response.data.employee);
        })
        .finally(() => {
          this.loading = false;
          this.showModalAdd = false;
        });
    },

    isActiveTab(route) {
      return this.$route.path === route;
    },
  },
};
</script>
<style>
* {
  margin: 0;
  padding: 0;
  border: 0;
  outline: 0;
  font-size: 100%;
  vertical-align: baseline;
  background: transparent;
}
main {
  overflow-y: auto; /* Enable vertical scrolling when content overflows */
  border-left-width: 1px; /* Ensure left border is persistent */
  border-color: #ccc; /* Define a consistent border color */
}
@media (min-width: 576px) {
  .tab-button {
    padding: 1rem 0.75rem;
  }
}
@media (min-width: 768px) {
  .tab-button {
    padding: 1rem 0.5rem;
  }
}
.link-button {
  text-decoration: none;
}
.svg-button {
  display: flex;
  align-items: center;
  gap: 5px;
}

.svg-button svg {
  width: 1em;
  height: 1em;
  border: 1px solid #000;
}
.svg-button2 {
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 8px;
  height: 20px;
  width: auto;
}
.svg-button2 svg {
  width: 1em;
  height: 1em;
}
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
  display: flex;
  align-items: center;
  justify-content: center;

  overflow: auto; /* Allow scrolling if the content exceeds screen height */
}

.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 90%; /* Take 90% of the screen width on smaller screens */
  max-width: 600px; /* Limit the width on larger screens */
  max-height: 90vh; /* Prevent the modal from exceeding the viewport height */
  overflow-y: auto; /* Add vertical scroll if content overflows */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow for better contrast */
}

.drop-container:hover,
.drop-container.drag-active {
  background: #eee;
  border-color: #111;
}

.drop-container:hover .drop-title,
.drop-container.drag-active .drop-title {
  color: #222;
}

.drop-title {
  color: #444;
  font-size: 20px;
  font-weight: bold;
  text-align: center;
  transition: color 0.2s ease-in-out;
}
</style>
