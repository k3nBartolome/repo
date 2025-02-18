<template>
  <div class="container p-4 mx-auto">
    <h2 class="mb-4 text-xl font-semibold text-center">OVERALL PEME RESULT</h2>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Final Status</label>
        <select v-model="peme_final_status" class="w-full p-2 mt-1 border rounded"
        @change="updatePemeData">
          <option disabled>Please select one</option>
          <option value="COMPLETE">COMPLETE</option>
          <option value="INCOMPLETE">INCOMPLETE</option>
          <option value="BLANK">BLANK</option>
        </select>
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Transaction Date</label>
        <input
          v-model="peme_transaction_date"
          @change="updatePemeData"
          type="date"
          class="w-full p-2 mt-1 border rounded"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Result Date</label>
        <input
          v-model="peme_results_date"
          @change="updatePemeData"
          type="date"
          class="w-full p-2 mt-1 border rounded"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Endorsed Date</label>
        <input
          v-model="peme_endorsed_date"
          @change="updatePemeData"
          type="date"
          class="w-full p-2 mt-1 border rounded"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Remarks</label>
        <select v-model="peme_remarks" class="w-full p-2 mt-1 border rounded">
          <option disabled>Please select one</option>
          <option value="FIT TO WORK">FIT TO WORK</option>
            <option value="UNFIT TO WORK">UNFIT TO WORK</option>
          <option value="INCOMPLETE - PENDING DT">INCOMPLETE - PENDING DT</option>
          <option value="INCOMPLETE - PENDING CHEST XRAY">
            INCOMPLETE - PENDING CHEST XRAY
          </option>
          <option value="FOR OB CLEARANCE/ PENDING SPUTUM TEST">
            FOR OB CLEARANCE/ PENDING SPUTUM TEST
          </option>
          <option value="FOR CARDIO CLEARANCE">FOR CARDIO CLEARANCE</option>
          <option value="FOR PULMO CLEARANCE">FOR PULMO CLEARANCE</option>
          <option value="FOR ORTHO CLEARANCE">FOR ORTHO CLEARANCE</option>
          <option value="FOR OPTHA CLEARANCE">FOR OPTHA CLEARANCE</option>
          <option value="FOR ENDO CLEARANCE">FOR ENDO CLEARANCE</option>
          <option value="FOR CLINIC'S VALIDATION">FOR CLINIC'S VALIDATION</option>
          <option value="FOR MEDICAL LEAVE">FOR MEDICAL LEAVE</option>
        </select>
      </div>
    </div>
    <div class="flex flex-col">
      <label class="block text-sm font-medium">PEME PROOF</label>
      <input type="file" @change="uploadImage" class="w-full p-2 mt-1 border rounded" />
    </div>
    <!-- <div class="flex flex-col justify-between mt-4 sm:flex-row">
      <button
        @click="chooseUpload"
        class="w-full p-2 mb-2 border rounded btn sm:w-1/2 lg:w-1/4 sm:mb-0"
      >
        Upload Image
      </button>
      <button
        @click="chooseCapture"
        class="w-full p-2 border rounded btn sm:w-1/2 lg:w-1/4"
      >
        Capture Image
      </button>
    </div> -->
    <div v-if="peme_file_name" class="mt-4">
      <div class="flex flex-col items-center">
        <img
          :src="peme_file_name"
          alt="Preview Image"
          class="object-cover w-full h-48 border rounded-lg sm:w-3/4 lg:w-1/2"
          @load="updatePemeData"
        />
      </div>
    </div>
    <div v-if="showCapture" class="mt-4">
      <!-- <div class="flex flex-col items-center">
        <video
          ref="video"
          class="object-cover w-full h-48 border rounded sm:w-3/4 lg:w-1/2"
          autoplay
        ></video>
        <button
          @click="captureImage"
          class="w-full p-2 mt-2 border rounded btn sm:w-1/2 lg:w-1/4"
        >
          Capture
        </button>
      </div> -->

      <div v-if="capturedImage" class="flex flex-col items-center mt-4">
        <img
          :src="capturedImage"
          alt="Captured Image"
          class="object-cover w-full h-48 border rounded-lg sm:w-3/4 lg:w-1/2"
        />
        <!-- <button
          @click="recaptureImage"
          class="w-full p-2 mt-2 border rounded btn sm:w-1/2 lg:w-1/4"
        >
          Recapture
        </button> -->
      </div>
    </div>

    <div class="mt-4">
      <button
        @click="submitForm"
        class="w-full p-2 mx-auto border rounded btn sm:w-1/2 lg:w-1/4"
      >
        Submit
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      showUpload: false,
      showCapture: false,
      capturedImage: null,
      peme_file_name: null,
      peme_remarks: "",
      peme_endorsed_date: "",
      peme_results_date: "",
      peme_transaction_date: "",
      peme_final_status: "",
      peme_proof: null, // Used for the proof file or image data
      isSubmitting: false, // Tracks form submission status
    };
  },
  mounted() {
    this.fetchPemeData();
  },
  methods: {
    async fetchPemeData() {
      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/get/peme/requirement/${this.$route.params.id}`
        );
        const data = response.data.data;

        // Populate the form fields with API response data
        this.peme_final_status = data.peme_final_status;
        this.peme_transaction_date = data.peme_transaction_date;
        this.peme_results_date = data.peme_results_date;
        this.peme_endorsed_date = data.peme_endorsed_date;
        this.peme_remarks = data.peme_remarks;
        this.peme_file_name = data.peme_file_name;
      } catch (error) {
        console.error("Error fetching NBI data:", error);
      }
    },
    updatePemeData() {
      console.log("Updating NBI Data...");
      console.log({
        peme_final_status: this.peme_final_status,
        peme_validity_date: this.peme_validity_date,
        peme_submitted_date: this.peme_submitted_date,
        peme_printed_date: this.peme_printed_date,
        peme_remarks: this.peme_remarks,
      });
    },
    async submitForm() {
      this.isSubmitting = true;

      // Check if 'peme_final_status' is selected
      if (!this.peme_final_status) {
        this.peme_final_status = "NO STATUS"; // or any default string or null
      }

      // Prepare form data
      const formData = new FormData();
      formData.append("peme_final_status", this.peme_final_status);
      formData.append("peme_results_date", this.peme_results_date);
      formData.append("peme_transaction_date", this.peme_transaction_date);
      formData.append("peme_endorsed_date", this.peme_endorsed_date);
      formData.append("peme_remarks", this.peme_remarks);
      formData.append("peme_updated_by", this.$store.state.user_id);
      // Append the actual file (peme_proof) for upload
      if (this.peme_proof) {
        formData.append("peme_proof", this.peme_proof); // append file here
      }

      try {
        const apiUrl = `http://127.0.0.1:8000/api/update/peme/requirement/${this.$route.params.id}`;

        // Submit the form data to the API
        const response = await axios.post(apiUrl, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });

        // Handle success
        console.log("Form submitted successfully", response.data);
      } catch (error) {
        // Handle error
        console.error(
          "Error submitting form",
          error.response ? error.response.data : error.message
        );
        alert("An error occurred while submitting the form.");
      } finally {
        // Reset submitting state
        this.isSubmitting = false;

        // Show success alert and navigate with reload after form submission
        alert("Form submitted successfully!");

        // Redirect to OnboardingUpdateSelection and reload the page
        this.$router
          .push({
            name: "OnboardingUpdateSelection",
            params: { id: this.$route.params.id },
          })
          .then(() => {
            window.location.reload(); // Reloads the page after navigation
          });
      }
    },
    uploadImage(event) {
      const file = event.target.files[0];
      if (file) {
        this.peme_proof = file; // Store the file in peme_proof

        const reader = new FileReader();
        reader.onload = () => {
          this.peme_file_name = reader.result; // Preview the image
        };
        reader.readAsDataURL(file); // Preview file
      }
    },

    resizeImage(file) {
      const img = new Image();
      const reader = new FileReader();
      reader.onload = (e) => {
        img.src = e.target.result;
      };
      reader.readAsDataURL(file);

      img.onload = () => {
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");
        const maxWidth = 1024;
        const maxHeight = 1024;
        let width = img.width;
        let height = img.height;

        if (width > height) {
          if (width > maxWidth) {
            height = (height * maxWidth) / width;
            width = maxWidth;
          }
        } else {
          if (height > maxHeight) {
            width = (width * maxHeight) / height;
            height = maxHeight;
          }
        }

        canvas.width = width;
        canvas.height = height;
        ctx.drawImage(img, 0, 0, width, height);

        const dataUrl = canvas.toDataURL("image/jpeg", 0.7); // Compress image to 70% quality
        const compressedFile = this.dataURLtoBlob(dataUrl);

        if (compressedFile.size > this.maxSize) {
          alert("Image is still too large, please upload a smaller image.");
          return;
        }

        this.peme_proof = compressedFile;
        this.peme_file_name = dataUrl;
      };
    },
    chooseUpload() {
      this.showUpload = true;
      this.showCapture = false;
      this.peme_proof = null;
      this.capturedImage = null;
    },
    chooseCapture() {
      this.showCapture = true;
      this.showUpload = false;
      this.peme_proof = null;
      this.capturedImage = null;
      this.startCamera();
    },
    async startCamera() {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({
          video: true,
        });
        this.$refs.video.srcObject = stream;
        this.videoStream = stream;
      } catch (error) {
        console.error("Error accessing camera:", error);
      }
    },
    captureImage() {
      const canvas = document.createElement("canvas");
      const context = canvas.getContext("2d");
      const video = this.$refs.video;

      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;

      context.drawImage(video, 0, 0, canvas.width, canvas.height);
      this.peme_proof = canvas.toDataURL("image/png");
    },
    recaptureImage() {
      this.capturedImage = null;
      this.peme_proof = null;
    },

    dataURLtoBlob(dataURL) {
      const byteString = atob(dataURL.split(",")[1]);
      const mimeString = dataURL.split(",")[0].split(":")[1].split(";")[0];
      const ab = new ArrayBuffer(byteString.length);
      const ia = new Uint8Array(ab);
      for (let i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
      }
      return new Blob([ab], { type: mimeString });
    },

    beforeUnmount() {
      if (this.videoStream) {
        const tracks = this.videoStream.getTracks();
        tracks.forEach((track) => track.stop());
      }
    },
  },
};
</script>

<style scoped>
.btn {
  background-color: #3490dc;
  color: white;
  font-weight: bold;
  transition: background-color 0.3s ease;
}

.btn:hover {
  background-color: #2779bd;
}

.container {
  max-width: 800px;
  margin: auto;
}

.object-cover {
  object-fit: cover;
}
</style>
