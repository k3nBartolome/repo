<template>
  <div class="container mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4 text-center">PHIC</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Final Status</label>
        <select
          v-model="phic_final_status"
          class="p-2 mt-1 border rounded w-full"
        >
          <option disabled>Please select one</option>
          <option value="YES">YES</option>
          <option value="NO">NO</option>
          <option value="PENDING PROOF">PENDING PROOF</option>
        </select>
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">PHIC #</label>
        <input
          v-model="phic_number"
          type="text"
          class="p-2 mt-1 border rounded w-full"
          @input="formatPhicNumber"
        />
        <small class="text-sm text-gray-500 mt-1">
          Format: 01-23456789-01
        </small>
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Proof Submitted Type</label>
        <input
          v-model="phic_proof_submitted_type"
          type="text"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Submitted Date</label>
        <input
          v-model="phic_submitted_date"
          type="date"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Remarks</label>
        <input
          v-model="phic_remarks"
          type="text"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>
    </div>
    <div class="flex flex-col">
      <label class="block text-sm font-medium">PHIC PROOF</label>
      <input
        type="file"
        @change="uploadImage"
        class="p-2 mt-1 border rounded w-full"
      />
    </div>
    <!-- <div class="flex flex-col sm:flex-row justify-between mt-4">
      <button
        @click="chooseUpload"
        class="btn p-2 border rounded w-full sm:w-1/2 lg:w-1/4 mb-2 sm:mb-0"
      >
        Upload Image
      </button>
      <button
        @click="chooseCapture"
        class="btn p-2 border rounded w-full sm:w-1/2 lg:w-1/4"
      >
        Capture Image
      </button>
    </div> -->
    <div v-if="phic_file_name" class="mt-4">
      <div class="flex flex-col items-center">
        <img
          :src="phic_file_name"
          alt="Preview Image"
          class="object-cover w-full sm:w-3/4 lg:w-1/2 h-48 border rounded-lg"
        />
      </div>
    </div>
    <div v-if="showCapture" class="mt-4">
      <!-- <div class="flex flex-col items-center">
        <video
          ref="video"
          class="w-full sm:w-3/4 lg:w-1/2 h-48 object-cover border rounded"
          autoplay
        ></video>
        <button
          @click="captureImage"
          class="btn p-2 border rounded mt-2 w-full sm:w-1/2 lg:w-1/4"
        >
          Capture
        </button>
      </div> -->

      <div v-if="capturedImage" class="mt-4 flex flex-col items-center">
        <img
          :src="capturedImage"
          alt="Captured Image"
          class="object-cover w-full sm:w-3/4 lg:w-1/2 h-48 border rounded-lg"
        />
        <!-- <button
          @click="recaptureImage"
          class="btn p-2 border rounded mt-2 w-full sm:w-1/2 lg:w-1/4"
        >
          Recapture
        </button> -->
      </div>
    </div>

    <div class="mt-4">
      <button
        @click="submitForm"
        class="btn p-2 border rounded w-full sm:w-1/2 lg:w-1/4 mx-auto"
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
      phic_submitted_date: "",
      phic_final_status: "",
      phic_proof_submitted_type: "",
      phic_remarks: "",
      phic_number: "",
      phic_file_name: null,
      videoStream: null,
      phic_proof: null, // Used for the proof file or image data
      isSubmitting: false, // Tracks form submission status
      phic_number_error: "",
    };
  },
  methods: {
    formatPhicNumber() {
      let numericValue = this.phic_number.replace(/\D/g, ""); // Remove all non-numeric characters
      if (numericValue.length > 12) {
        numericValue = numericValue.slice(0, 12); // Limit to 12 digits
      }

      // Format as 01-23456789-01
      const parts = [
        numericValue.slice(0, 2),
        numericValue.slice(2, 10),
        numericValue.slice(10, 12),
      ];
      this.phic_number = parts.filter((part) => part).join("-");
    },
    validatePhicNumber() {
      // Regular expression for PHIC #
      const phicPattern = /^\d{2}-\d{8}-\d{2}$/;
      if (!phicPattern.test(this.phic_number)) {
        this.phic_number_error =
          "PHIC # must follow the format 01-23456789-01.";
      } else {
        this.phic_number_error = "";
      }
    },
    async submitForm() {
      this.isSubmitting = true;
      this.validatePhicNumber();
      if (this.phic_number_error) {
        alert("Please correct the PHIC # before submitting.");
        return;
      }
      // Check if 'phic_final_status' is selected
      if (!this.phic_final_status) {
        this.phic_final_status = "NO STATUS"; // or any default string or null
      }

      // Prepare form data
      const formData = new FormData();
      formData.append("phic_final_status", this.phic_final_status);
      formData.append("phic_submitted_date", this.phic_submitted_date);
      formData.append("phic_number", this.phic_number);
      formData.append(
        "phic_proof_submitted_type",
        this.phic_proof_submitted_type
      );
      formData.append("phic_remarks", this.phic_remarks);
      formData.append("phic_updated_by", this.$store.state.user_id);
      // Append the actual file (phic_proof) for upload
      if (this.phic_proof) {
        formData.append("phic_proof", this.phic_proof); // append file here
      }

      try {
        const apiUrl = `https://10.236.103.190/api/update/phic/requirement/${this.$route.params.id}`;

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
        this.phic_proof = file; // Store the file in phic_proof

        const reader = new FileReader();
        reader.onload = () => {
          this.phic_file_name = reader.result; // Preview the image
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

        this.phic_proof = compressedFile;
        this.phic_file_name = dataUrl;
      };
    },
    chooseUpload() {
      this.showUpload = true;
      this.showCapture = false;
      this.phic_proof = null;
      this.capturedImage = null;
    },
    chooseCapture() {
      this.showCapture = true;
      this.showUpload = false;
      this.phic_proof = null;
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
      this.phic_proof = canvas.toDataURL("image/png");
    },
    recaptureImage() {
      this.capturedImage = null;
      this.phic_proof = null;
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
