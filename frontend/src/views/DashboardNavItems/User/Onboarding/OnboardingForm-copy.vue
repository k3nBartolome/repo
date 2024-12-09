<template>
  <div class="container">
    <button @click="startScanning" class="scanner-button">
      Start Scanning
    </button>

    <!-- QR Scanner (conditionally rendered) -->
    <div v-if="scanning" class="scanner-overlay">
      <!-- Close button at the top of scanner overlay -->
      <button @click="closeScanner" class="close-scanner-button">Close</button>
      <video ref="video" class="scanner-video"></video>
      <!-- Scan button below the video -->
      <button @click="performScan" class="scan-button">Scan</button>
    </div>

    <!-- Table structure -->
    <div class="table-wrapper">
      <table class="employee-table">
        <thead>
          <tr>
            <th class="truncate">Action</th>
            <th class="truncate">Employee ID</th>
            <th class="truncate">Last Name</th>
            <th class="truncate">First Name</th>
            <th class="truncate">Middle Name</th>
            <th class="truncate">Date of Birth</th>
            <th class="truncate">Contact Number</th>
            <th class="truncate">Email</th>
            <th class="truncate">Hired Date</th>
            <th class="truncate">Hired Month</th>
            <th class="truncate">Employee Status</th>
            <th class="truncate">Employment Status</th>
            <th class="truncate">Account Associate</th>
            <th class="truncate">Added By</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="employee in employees" :key="employee.id">
            <td class="truncate">
              <button
                class="text-white truncate bg-blue-400"
                @click="goToUpdate(employee.id)"
              >
                Update
              </button>
            </td>
            <td class="truncate">{{ employee.employee_id }}</td>
            <td class="truncate">{{ employee.last_name }}</td>
            <td class="truncate">{{ employee.first_name }}</td>
            <td class="truncate">{{ employee.middle_name }}</td>
            <td class="truncate">{{ employee.birthdate }}</td>
            <td class="truncate">{{ employee.contact_number }}</td>
            <td class="truncate">{{ employee.email }}</td>
            <td class="truncate">{{ employee.hired_date }}</td>
            <td class="truncate">{{ employee.hired_month }}</td>
            <td class="truncate">{{ employee.employee_status }}</td>
            <td class="truncate">{{ employee.employment_status }}</td>
            <td class="truncate">{{ employee.account_associate }}</td>
            <td class="truncate">{{ employee.employee_added_by }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination Controls (hidden during scanning) -->
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

export default {
  data() {
    return {
      employees: [],
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
      qrReader: null,
      cameraId: null,
    };
  },
  mounted() {
    this.getEmployees();
    this.qrReader = new BrowserMultiFormatReader();
  },
  methods: {
    async getEmployees(url = "https://10.109.2.112/api/employees") {
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
    goToPage(url) {
      if (url) this.getEmployees(url);
    },
    goToUpdate(employeeId) {
      this.$router.push({
        name: "OnboardingUpdateForm",
        params: { id: employeeId },
      });
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
              this.performScan(result.text); // Call performScan method instead of navigateToUpdateForm directly
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

    async performScan(scannedId) {
      if (this.qrReader) {
        try {
          // Call checkEmployeeExists to verify employee
          await this.checkEmployeeExists(scannedId);
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

    async checkEmployeeExists(scannedId) {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          `https://10.109.2.112/api/employees/${scannedId}`,
          {
            headers: { Authorization: `Bearer ${token}` },
          }
        );

        if (response.status === 200 && response.data.employee) {
          // If the employee exists, navigate to the update form
          this.navigateToUpdateForm(scannedId);
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

    navigateToUpdateForm(scannedValue) {
      // Perform the navigation to the update form only after confirming the employee exists
      this.$router.push({
        name: "OnboardingUpdateForm",
        params: { id: scannedValue },
      });
    },
  },
};
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-top: 10px;
  padding-left: 2px;
  padding-right: 2px;
}

.table-wrapper {
  overflow-x: auto;
  width: 100%;
}

.employee-table {
  width: 100%;
  max-width: 1200px;
  margin: 20px auto;
  border-collapse: collapse;
  text-align: left;
}

.employee-table th,
.employee-table td {
  padding: 12px;
  border: 1px solid #ddd;
}

.employee-table th {
  background-color: #f4f4f4;
  font-weight: bold;
}

.scanner-button {
  margin-top: 20px;
  padding: 10px 20px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.scanner-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.scanner-video {
  width: 80%;
  max-width: 400px;
  border: 2px solid white;
  border-radius: 8px;
}

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

.scan-button {
  margin-top: 10px;
  padding: 10px 20px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.pagination {
  display: flex;
  gap: 10px;
  margin-top: 20px;
}
</style>
