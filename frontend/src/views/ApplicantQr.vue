<template>
  <div class="min-h-screen bg-gray-100">
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-center text-3xl font-semibold text-gray-800 mb-6">Applicant Attendance Checker</h1>

      <div class="flex justify-center">
        <button @click="startScanning" class="scanner-button">
          Start Scanning
        </button>
      </div>

      <div v-if="scanning" class="scanner-overlay">
        <button @click="closeScanner" class="close-scanner-button">Close</button>
        <video ref="video" class="scanner-video"></video>
        <div class="scanner-info">
          <p class="text-white text-xl">Scan a QR Code</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { BrowserMultiFormatReader } from "@zxing/library";

export default {
  data() {
    return {
      scanning: false,
      qrReader: null,
      cameraId: null,
      qrCodeData: "",
    };
  },
  mounted() {
    this.qrReader = new BrowserMultiFormatReader();
  },
  methods: {
    async startScanning() {
      try {
        this.scanning = true;

        // Request camera devices and select the back camera
        const devices = await navigator.mediaDevices.enumerateDevices();
        const videoDevices = devices.filter(
          (device) => device.kind === "videoinput"
        );

        if (videoDevices.length > 1) {
          this.cameraId = videoDevices[1].deviceId;
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
              this.performScan(result.text);
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
    performScan(scannedData) {
      try {
        console.log("Scanned Data:", scannedData); // Debugging: Check the scanned data

        // Extract site ID from the scanned data
        const match = scannedData.match(/site\s*=\s*(\d+)/); 
        if (match) {
          const siteId = match[1]; // Extract the ID value
          this.$router.push({ 
            name: "ApplicantAttendanceForm", 
            params: { id: siteId } // Pass the `id` parameter
          });
        } else {
          console.error("Site ID not found in QR code data.");
        }
      } catch (error) {
        console.error("Error processing QR code data:", error);
      }
    },
    closeScanner() {
      this.scanning = false;
      if (this.qrReader) {
        this.qrReader.reset();
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

/* Header styling */
h1 {
  color: #333;
  font-size: 2rem;
  font-weight: 600;
  margin-bottom: 20px;
}

/* Scanner button styling */
.scanner-button {
  padding: 12px 25px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 18px;
  transition: background-color 0.3s;
}

.scanner-button:hover {
  background-color: #0056b3;
}

/* Scanner overlay styling */
.scanner-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.scanner-info {
  margin-top: 20px;
  color: white;
  font-size: 20px;
  font-weight: bold;
}

/* Video styling */
.scanner-video {
  width: 100%;
  max-width: 400px;
  border-radius: 10px;
  border: 2px solid #fff;
  background-color: #000;
}

/* Close button styling */
.close-scanner-button {
  position: absolute;
  top: 10px;
  right: 10px;
  padding: 12px;
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.close-scanner-button:hover {
  background-color: #c82333;
}
</style>
