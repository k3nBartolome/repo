<template>
  <div class="container p-4 mx-auto">
    <h2 class="mb-4 text-xl font-semibold text-center">Drug Test</h2>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Final Status</label>
        <select
          v-model="dt_final_status"
           @change="updateDTData"
          class="w-full p-2 mt-1 border rounded"
        >
          <option disabled>Please select one</option>
          <option value="NEGATIVE">NEGATIVE</option>
          <option value="POSITIVE - FOR CONFIRMATORY">
            POSITIVE - FOR CONFIRMATORY
          </option>
          <option value="(BLANK)">(BLANK)</option>
        </select>
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Transaction Date</label>
        <input
          v-model="dt_transaction_date"
           @change="updateDTData"
          type="date"
          class="w-full p-2 mt-1 border rounded"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Result Date</label>
        <input
          v-model="dt_results_date"
           @change="updateDTData"
          type="date"
          class="w-full p-2 mt-1 border rounded"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Endorsed Date</label>
        <input
          v-model="dt_endorsed_date"
           @change="updateDTData"
          type="date"
          class="w-full p-2 mt-1 border rounded"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Remarks</label>
        <input
          v-model="dt_remarks"
          type="text"
           @input="updateDTData"
          class="w-full p-2 mt-1 border rounded"
        />
      </div>
    </div>
    <div class="flex flex-col">
      <label class="block text-sm font-medium">DT Proof</label>
      <input
        type="file"
        @change="uploadImage"
        class="w-full p-2 mt-1 border rounded"
      />
    </div>
    <div v-if="dt_file_name" class="mt-4">
      <div class="flex flex-col items-center">
        <img
          :src="dt_file_name"
          alt="Preview Image"
          class="object-cover w-full h-48 border rounded-lg sm:w-3/4 lg:w-1/2"
           @load="updateDTData"
        />
      </div>
    </div>
    <div v-if="showCapture" class="mt-4">
      <div v-if="capturedImage" class="flex flex-col items-center mt-4">
        <img
          :src="capturedImage"
          alt="Captured Image"
          class="object-cover w-full h-48 border rounded-lg sm:w-3/4 lg:w-1/2"
        />
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
      dt_final_status: "",
      dt_results_date: "",
      dt_transaction_date: "",
      dt_endorsed_date: "",
      dt_remarks: "",
      dt_file_name: null,
      videoStream: null,
      dt_proof: null, // Used for the proof file or image data
      isSubmitting: false, // Tracks form submission status
    };
  },
   mounted() {
    this.getDT(); // Fetch data on component load
  },
  methods: {
    
     async getDT() {
      try {
        const apiUrl = `https://10.109.2.112/api/get/dt/requirement/${this.$route.params.id}`;
        const response = await axios.get(apiUrl);
        const data = response.data.data;

        this.dt_final_status = data.dt_final_status || "";
        this.dt_results_date = data.dt_results_date || "";
        this.dt_transaction_date = data.dt_transaction_date || "";
        this.dt_endorsed_date = data.dt_endorsed_date || "";
        this.dt_remarks = data.dt_remarks || "";
        this.dt_file_name = data.dt_file_name || null; // Assuming proof_url is provided
      } catch (error) {
        console.error("Error fetching DT data:", error.response || error.message);
        alert("Failed to load drug test information.");
      }
    },
    updateDTData() {
    console.log("Updating Drug Test Data...");
    console.log({
      dt_final_status: this.dt_final_status,
      dt_transaction_date: this.dt_transaction_date,
      dt_results_date: this.dt_results_date,
      dt_endorsed_date: this.dt_endorsed_date,
      dt_remarks: this.dt_remarks,
      dt_file_name: this.dt_file_name, // Image filename or data if uploaded
    });
  },
    async submitForm() {
      this.isSubmitting = true;

      // Check if 'dt_final_status' is selected
      if (!this.dt_final_status) {
        this.dt_final_status = "NO STATUS"; // or any default string or null
      }

      // Prepare form data
      const formData = new FormData();
      formData.append("dt_final_status", this.dt_final_status);
      formData.append("dt_results_date", this.dt_results_date);
      formData.append("dt_transaction_date", this.dt_transaction_date);
      formData.append("dt_endorsed_date", this.dt_endorsed_date);
      formData.append("dt_remarks", this.dt_remarks);
      formData.append("dt_updated_by", this.$store.state.user_id);
      // Append the actual file (dt_proof) for upload
      if (this.dt_proof) {
        formData.append("dt_proof", this.dt_proof); // append file here
      }

      try {
        const apiUrl = `https://10.109.2.112/api/update/dt/requirement/${this.$route.params.id}`;

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
        this.dt_proof = file; // Store the file in dt_proof

        const reader = new FileReader();
        reader.onload = () => {
          this.dt_file_name = reader.result; // Preview the image
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

        this.dt_proof = compressedFile;
        this.dt_file_name = dataUrl;
      };
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
    chooseUpload() {
      this.showUpload = true;
      this.showCapture = false;
      this.dt_proof = null;
      this.capturedImage = null;
    },
    chooseCapture() {
      this.showCapture = true;
      this.showUpload = false;
      this.dt_proof = null;
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
      this.dt_proof = canvas.toDataURL("image/png");
    },
    recaptureImage() {
      this.capturedImage = null;
      this.dt_proof = null;
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
