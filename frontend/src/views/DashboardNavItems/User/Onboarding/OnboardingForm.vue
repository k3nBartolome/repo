<template>
  <div class="container">
    <button @click="startScanning" class="scanner-button">
      Start Scanning
    </button>
    <div v-if="scanning" class="scanner-overlay">
      <button @click="closeScanner" class="close-scanner-button">Close</button>
      <video ref="video" class="scanner-video"></video>
      <button @click="performScan" class="scan-button">Scan</button>
    </div>

    <div class="table-wrapper">
      <div class="mb-4 md:flex md:space-x-2 md:items-center">
        <!-- Employee Status -->
        <div class="relative w-full md:w-1/4">
          <label
            for="employee_status"
            class="block text-sm font-medium text-gray-700 truncate"
            >Employee Status</label
          >
          <select
            v-model="employee_status"
            id="employee_status"
            class="w-full p-2 border rounded-lg"
          >
            <option value="" disabled selected>Select Employee Status</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
            <option value="Separated">Separated</option>
          </select>
        </div>

        <!-- Employment Status -->
        <div class="w-full md:w-1/4">
          <label
            for="employment_status"
            class="block text-sm font-medium text-gray-700 truncate"
            >Employment Status</label
          >
          <select
            v-model="employment_status"
            id="employment_status"
            class="w-full p-2 border rounded-lg"
          >
            <option value="" disabled selected>Select Employment Status</option>
            <option value="Hired">Hired</option>
            <option value="To be Hired">To be Hired</option>
          </select>
        </div>

        <!-- Hired Date From -->
        <div class="w-full md:w-1/4 relative">
          <label
            for="hired_date_from"
            class="block text-sm font-medium text-gray-700 truncate"
            >Hired Date From</label
          >
          <input
            v-model="hired_date_from"
            type="date"
            id="hired_date_from"
            class="w-full p-2 border rounded-lg"
            @input="updatehired_date_from"
          />
        </div>

        <!-- Hired Date To -->
        <div class="w-full md:w-1/4 relative">
          <label
            for="hired_date_to"
            class="block text-sm font-medium text-gray-700 truncate"
            >Hired Date To</label
          >
          <input
            v-model="hired_date_to"
            type="date"
            id="hired_date_to"
            class="w-full p-2 border rounded-lg truncate"
            @input="updateFilterEndDate"
          />
        </div>

        <div class="w-full md:w-1/4">
          <button
            @click="getEmployees"
            class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg"
          >
            Filter
          </button>
        </div>
        <div class="w-full md:w-1/4">
          <button
            @click="exportToExcel"
            class="w-full px-4 py-2 text-white bg-green-600 rounded-lg truncate"
          >
            Export Data
          </button>
        </div>
      </div>
      <table class="employee-table">
        <thead>
          <tr>
            <th colspan="2" class="text-center">Action</th>
            <th>Generate</th>
            <th>QR Code</th>
            <th>Site</th>
            <th>Employee ID</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Date of Birth</th>
            <th>Contact Number</th>
            <th>Email</th>
            <th>Hired Date</th>
            <th>Hired Month</th>
            <th>Employee Status</th>
            <th>Employment Status</th>
            <th>Account Associate</th>
            <th>Added By</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="employee in employees" :key="employee.id">
            <td class="truncate">
              <button
                class="truncate bg-blue-500 text-white px-4 py-2 text-sm rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 transition duration-200"
                @click="goToProfile(employee.id)"
              >
                View
              </button>
            </td>
            <td class="truncate">
              <button
                class="truncate bg-blue-500 text-white px-4 py-2 text-sm rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 transition duration-200"
                @click="goToUpdate(employee.id)"
              >
                Update
              </button>
            </td>
            <td>
              <button
                :disabled="employee.employee_qr_code_url"
                @click="
                  generateQRCode(
                    employee.id,
                    employee.employee_email,
                    employee.employee_contact_number
                  )
                "
                class="generate-qr-button"
              >
                {{ employee.employee_qr_code_url ? "QR Generated" : "Generate QR" }}
              </button>
            </td>

            <td>
              <div v-if="employee.employee_qr_code_url">
                <img
                  :src="employee.employee_qr_code_url"
                  alt="QR Code"
                  class="qr-code"
                />
                <a
                  :href="employee.employee_qr_code_url"
                  :download="'qr_code_' + employee.id + '.png'"
                  class="download-link"
                >
                  Download QR
                </a>
              </div>
            </td>
             <td>{{ employee.site }}</td>
            <td>{{ employee.employee_id }}</td>
            <td>{{ employee.employee_last_name }}</td>
            <td>{{ employee.employee_first_name }}</td>
            <td>{{ employee.employee_middle_name }}</td>
            <td>{{ employee.employee_birth_date }}</td>
            <td>{{ employee.employee_contact_number }}</td>
            <td>{{ employee.employee_email }}</td>
            <td>{{ employee.employee_hired_date }}</td>
            <td>{{ employee.employee_hired_month }}</td>
            <td>{{ employee.employee_employee_status }}</td>
            <td>{{ employee.employee_employment_status }}</td>
            <td>{{ employee.employee_position }}</td>
           <td>{{ employee.employee_added_by ? employee.employee_added_by : 'N/A' }}</td>

          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="!scanning" class="pagination">
      <button
        @click="goToPage(pagination.first_page)"
        :disabled="!pagination.prev_page"
      >
        First
      </button>
      <button
        @click="goToPage(pagination.prev_page)"
        :disabled="!pagination.prev_page"
      >
        Previous
      </button>
      <span>
        Page {{ pagination.current_page }} of {{ pagination.total_pages }}
      </span>
      <button
        @click="goToPage(pagination.next_page)"
        :disabled="!pagination.next_page"
      >
        Next
      </button>
      <button
        @click="goToPage(pagination.last_page)"
        :disabled="!pagination.next_page"
      >
        Last
      </button>
    </div>
  </div>
</template>

<script>
import { BrowserMultiFormatReader } from "@zxing/library";
import axios from "axios";
import QRCode from "qrcode"; // Import the QRCode library

export default {
  data() {
    return {
      employees: {},
      scannedValue: "",
      extractedId: "",
      employee_status: "",
      employment_status: "",
      filterSite: "",
      hired_date_from: "",
      hired_date_to: "",
      filterContact: "",
      employee_statusError: "",
      filterContactError: "",
      pagination: {
        current_page: 1,
        total: 0,
        first_page: null,
        last_page: null,
        next_page: null,
        prev_page: null,
        per_page: 10,
        total_pages: 1,
      },
      scanning: false,
      qrVisibility: {},
      qrReader: null,
      cameraId: null,
      qrCodeData: "",
      selectedEmployee: null,
    };
  },
  computed: {
    formattedFilterDate() {
      return this.filterDate
        ? new Date(this.filterDate).toLocaleDateString("en-CA")
        : "";
    },
  },
  mounted() {
    this.getEmployees();
    this.qrReader = new BrowserMultiFormatReader();
  },
  methods: {
    goToProfile(employeeId) {
      this.$router.push({
        name: "OnboardingEmployeeProfile",
        params: { id: employeeId },
      });
    },
     goToUpdate(employeeId) {
      this.$router.push({
        name: "OnboardingUpdateSelection",
        params: { id: employeeId },
      });
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
    if (
      !employeeContactNumber ||
      typeof employeeContactNumber !== "string"
    ) {
      console.error(
        "Invalid employee contact number:",
        employeeContactNumber
      );
      return;
    }

    // Format QR code data
    const qrData = `${employeeId},${employeeEmail},${employeeContactNumber}`;

    // Generate QR code
    const qrCodeCanvas = await QRCode.toCanvas(qrData);

    // Convert canvas to Blob directly
    qrCodeCanvas.toBlob((blob) => {
      const file = new File([blob], `qr_code_${employeeId}.png`, {
        type: "image/png",
      });

      // Prepare FormData for upload
      const formData = new FormData();
      formData.append("qr_code", file);

      // Make API request
      const token = this.$store.state.token;
      axios
        .post(
          `https://10.109.2.112/api/employees/${employeeId}/save-qr-code`,
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
              Authorization: `Bearer ${token}`,
            },
          }
        )
        .then((response) => {
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
        })
        .catch((error) => {
          console.error("Error generating and saving QR code:", error);
        });
    });
  } catch (error) {
    console.error("Error generating QR code:", error);
  }
},

    goToPage(page) {
      if (!page || page < 1 || page > this.pagination.total_pages) {
        console.warn("Invalid page navigation:", page);
        return;
      }
      this.pagination.current_page = page; // Update the current page
      this.getEmployees(); // Fetch data for the new page with filters
    },

    async getEmployees() {
      try {
        const token = this.$store.state.token;

        const params = {
          employee_status: this.employee_status,
          employment_status: this.employment_status,
          hired_date_from: this.hired_date_from,
          hired_date_to: this.hired_date_to,
          page: this.pagination.current_page,
        };

        console.log("Fetching employees with params:", params);

        const response = await axios.get(
        `https://10.109.2.112/api/employees_data/${this.$store.state.site_id}`,
          {
            params,
            headers: { Authorization: `Bearer ${token}` },
          }
        );

        if (response.status === 200) {
          this.employees = response.data.employees|| {};
          this.pagination = response.data.pagination|| {};
          console.log(
            "Updated employees and pagination:",
            this.employees,
            this.pagination
          );
        } else {
          console.error("Failed to fetch employees:", response.statusText);
        }
      } catch (error) {
        console.error("Error fetching employees:", error);
      }
    },
    async startScanning() {
      try {
        this.scanning = true;

        // Request camera devices and select the back camera
        const devices = await navigator.mediaDevices.enumerateDevices();
        const videoDevices = devices.filter(
          (device) => device.kind === "videoinput"
        );

        if (videoDevices.length > 1) {
          this.cameraId = videoDevices[1].deviceId; // Default to the second camera (typically the rear one)
        } else if (videoDevices.length === 1) {
          this.cameraId = videoDevices[0].deviceId;
        } else {
          console.error("No camera found on this device.");
          this.closeScanner();
          return;
        }

        // Initialize scanner with the selected camera
        await this.qrReader.decodeFromVideoDevice(
          this.cameraId,
          this.$refs.video,
          (result, err) => {
            if (result) {
              this.performScan(result.text); // Pass the scanned QR code data
              this.closeScanner();
            } else if (err) {
              console.error("QR code scanning error:", err);
            }
          }
        );
      } catch (error) {
        console.error("Camera initialization failed:", error);
        this.closeScanner();
      }
    },

    async performScan(scannedData) {
      if (this.qrReader) {
        try {
          // Extract the employeeId from the scanned data
          const employeeId = scannedData.split(",")[0].trim(); // Get the first part of the QR data

          // Call checkEmployeeExists with the extracted employeeId
          await this.checkEmployeeExists(employeeId);
        } catch (error) {
          console.error("Error during scanning:", error);
          this.$alert("Error during scanning. Please try again.");
        }
      }
    },

    closeScanner() {
      this.scanning = false;
      if (this.qrReader) {
        this.qrReader.reset();
      }
    },

    async checkEmployeeExists(employeeId) {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          `https://10.109.2.112/api/employees/${employeeId}`,
          {
            headers: { Authorization: `Bearer ${token}` },
          }
        );

        if (response.status === 200 && response.data.employee) {
          // If the employee exists, navigate to the update form
          this.navigateToUpdateForm(employeeId);
        } else {
          // If the employee doesn't exist, show an error message
          alert("Employee not found.");
        }
      } catch (error) {
        if (error.response && error.response.status === 404) {
          // Handle the case where employee is not found
          alert("Employee not found.");
        } else {
          // Generic error handling
          console.error("Error fetching employee:", error);
          alert("An error occurred. Please try again.");
        }
      }
    },

    navigateToUpdateForm(employeeId) {
      this.$router.push({
        name: "OnboardingUpdateSelection",
        params: { id: employeeId },
      });
    },
    updatehired_date_from(event) {
      this.hired_date_from = event.target.value;
    },

    updateFilterEndDate(event) {
      this.hired_date_to = event.target.value;
    },
    async exportToExcel() {
      //this.exportLoading = true; // Set export loading to true before making the request
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
         `https://10.109.2.112/api/employees_export/${this.$store.state.site_id}`,
          {
            params: {
              employee_status: this.employee_status,
              employment_status: this.employment_status,
              hired_date_from: this.hired_date_from,
              hired_date_to: this.hired_date_to,
            },
            headers: {
              Authorization: `Bearer ${token}`,
            },
            responseType: "blob",
          }
        );

        const blob = new Blob([response.data], {
          type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        });
        const url = window.URL.createObjectURL(blob);

        const link = document.createElement("a");
        link.href = url;
        link.download = "employees_data.xlsx";
        link.click();
      } catch (error) {
        console.error("Error exporting filtered data to Excel", error);
      } finally {
        this.exportLoading = false; // Set export loading to false when the request completes
      }
    },
  },
};
</script>
<style scoped>
/* General container styling */
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 2px;
}
.data-controls {
  border: 1px solid #ddd;
  padding: 20px;
  border-radius: 5px;
}

.filters {
  margin-bottom: 20px;
}

.filters label {
  margin-right: 10px;
}

.filter-button {
  padding: 5px 15px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.export-button {
  margin-bottom: 10px;
  padding: 10px 20px;
  background-color: #10c328;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

/* Scanner button */
.scanner-button {
  margin-bottom: 20px;
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.scanner-button:hover {
  background-color: #0056b3;
}

/* Scanner overlay */
.scanner-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.8);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

/* Close scanner button */
.close-scanner-button {
  position: absolute;
  top: 10px;
  right: 10px;
  padding: 8px 12px;
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

/* Scan button */
.scan-button {
  margin-top: 10px;
  padding: 10px 20px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
}

/* Table wrapper for horizontal scrolling */
.table-wrapper {
  overflow-x: auto; /* Enables horizontal scrolling when table is too wide */
  width: 100%;
}

/* Employee table styling */
.employee-table {
  width: 100%;
  border-collapse: collapse;
  table-layout: auto; /* Ensures the table adjusts its width based on content */
}

/* Table headers and cell styling */
.employee-table th,
.employee-table td {
  padding: 6px;
  border: 1px solid #ddd;
  text-align: left;
  white-space: nowrap; /* Prevents text from wrapping in cells */
}

/* Table header background */
.employee-table th {
  background-color: #f4f4f4;
  font-weight: bold;
}

/* QR code image styling */
.qr-code {
  max-width: 50px; /* Ensure QR codes aren't too large */
  height: 50px;
}

/* Base styling for the QR generate button */
.generate-qr-button {
  padding: 8px 16px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

/* Hover effect when the button is enabled */
.generate-qr-button:not(:disabled):hover {
  background-color: #0056b3;
}

/* Styling when the QR code is generated (button is disabled) */
.generate-qr-button:disabled {
  background-color: #6c757d; /* Gray color when disabled */
  cursor: not-allowed; /* Make cursor show as not-allowed */
}

/* Optional: Add styles for the button when it's disabled */
.generate-qr-button:disabled:hover {
  background-color: #6c757d; /* No hover effect when disabled */
}

/* Pagination styles */
.pagination {
  display: flex;
  justify-content: center;
  gap: 10px;
}

.pagination button {
  padding: 5px 10px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 3px;
  cursor: pointer;
}

.pagination button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.pagination span {
  display: flex;
  align-items: center;
  font-size: 14px;
}

/* Ensure table cells stay horizontal */
.employee-table tr {
  display: table-row;
}

/* Ensures no wrapping in table rows, keeping cells horizontal */
.employee-table td {
  display: table-cell;
  padding: 10px;
  border-bottom: 1px solid #ddd;
  white-space: nowrap; /* Prevents text from wrapping */
}

.employee-table td::before {
  content: attr(data-label); /* Label the data */
  font-weight: bold;
  margin-right: 5px;
}
.download-link {
  display: block;
  margin-top: 5px;
  color: #007bff;
  text-decoration: none;
}

.download-link:hover {
  text-decoration: underline;
}
</style>
