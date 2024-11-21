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
      <table class="employee-table">
        <thead>
          <tr>
            <th>Action</th>
            <th>QR Code</th>
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
            <td>
              <button
                :disabled="employee.qr_code_url"
                @click="
                  generateQRCode(
                    employee.id,
                    employee.email,
                    employee.contact_number
                  )
                "
                class="generate-qr-button"
              >
                {{ employee.qr_code_url ? "QR Generated" : "Generate QR" }}
              </button>
            </td>

            <td>
              <div v-if="employee.qr_code_url">
                <img
                  :src="employee.qr_code_url"
                  alt="QR Code"
                  class="qr-code"
                />
                <a
                  :href="employee.qr_code_url"
                  :download="'qr_code_' + employee.id + '.png'"
                  class="download-link"
                >
                  Download QR
                </a>
              </div>
            </td>

            <td>{{ employee.id }}</td>
            <td>{{ employee.last_name }}</td>
            <td>{{ employee.first_name }}</td>
            <td>{{ employee.middle_name }}</td>
            <td>{{ employee.birthdate }}</td>
            <td>{{ employee.contact_number }}</td>
            <td>{{ employee.email }}</td>
            <td>{{ employee.hired_date }}</td>
            <td>{{ employee.hired_month }}</td>
            <td>{{ employee.employee_status }}</td>
            <td>{{ employee.employment_status }}</td>
            <td>{{ employee.account_associate }}</td>
            <td>{{ employee.employee_added_by }}</td>
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
      employees: [],
      scannedValue: "",
      extractedId: "",
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
  mounted() {
    this.getEmployees();
    this.qrReader = new BrowserMultiFormatReader();
  },
  methods: {
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
          `https://10.236.102.139/api/employees/${employeeId}/save-qr-code`,
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
          }
        }
      } catch (error) {
        console.error("Error generating and saving QR code:", error);
      }
    },

    // Fetch employees from the backend
    async getEmployees(url = "https://10.236.102.139/api/employees") {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(url, {
          headers: { Authorization: `Bearer ${token}` },
        });

        if (response.status === 200) {
          this.employees = response.data.employees;
          this.pagination = response.data.pagination;
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
          `https://10.236.102.139/api/employees/${employeeId}`,
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
  },
};
</script>
<style scoped>
/* General container styling */
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;
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
  height: auto;
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
