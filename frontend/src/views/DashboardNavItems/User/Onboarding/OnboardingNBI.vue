<template>
  <div class="container mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4 text-center">NBI Upload</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <!-- NBI Proof Image Upload -->

      <!-- Validity Date -->
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Validity Date</label>
        <input
          v-model="nbi_validity_date"
          type="date"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>
      <!-- Printed Date -->
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Printed Date</label>
        <input
          v-model="nbi_printed_date"
          type="date"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>
      <!-- Remarks -->
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Remarks</label>
        <input
          v-model="nbi_remarks"
          type="text"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>
    </div>
    <div class="flex flex-col">
      <label class="block text-sm font-medium">NBI Proof</label>
      <input
        type="file"
        @change="uploadImage"
        class="p-2 mt-1 border rounded w-full"
      />
    </div>
    <div class="flex flex-col sm:flex-row justify-between mt-4">
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
    </div>

    <!-- Image Preview (For Both Upload and Capture) -->
    <div v-if="nbi_proof" class="mt-4">
      <div class="flex flex-col items-center">
        <img
          :src="nbi_proof"
          alt="Preview Image"
          class="object-cover w-full sm:w-3/4 lg:w-1/2 h-48 border rounded-lg"
        />
      </div>
    </div>

    <!-- Image Capture Section -->
    <div v-if="showCapture" class="mt-4">
      <div class="flex flex-col items-center">
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
      </div>

      <div v-if="capturedImage" class="mt-4 flex flex-col items-center">
        <img
          :src="capturedImage"
          alt="Captured Image"
          class="object-cover w-full sm:w-3/4 lg:w-1/2 h-48 border rounded-lg"
        />
        <button
          @click="recaptureImage"
          class="btn p-2 border rounded mt-2 w-full sm:w-1/2 lg:w-1/4"
        >
          Recapture
        </button>
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
export default {
  data() {
    return {
      showUpload: false,
      showCapture: false,
      nbi_proof: null, // To store the uploaded or captured image
      capturedImage: null, // Store captured image if camera is used
      nbi_validity_date: "",
      nbi_printed_date: "",
      nbi_remarks: "",
      videoStream: null,
    };
  },
  methods: {
    // Handle file input upload
    uploadImage(event) {
      const file = event.target.files[0];
      const reader = new FileReader();
      reader.onload = () => {
        this.nbi_proof = reader.result; // Set the uploaded file as the proof
      };
      if (file) {
        reader.readAsDataURL(file);
      }
    },

    // Show the upload form and hide capture
    chooseUpload() {
      this.showUpload = true;
      this.showCapture = false;
      this.nbi_proof = null; // Clear any previously captured image
      this.capturedImage = null; // Clear captured image
    },

    // Show the capture form and hide upload
    chooseCapture() {
      this.showCapture = true;
      this.showUpload = false;
      this.nbi_proof = null; // Clear any previously uploaded image
      this.capturedImage = null; // Clear captured image
      this.startCamera();
    },

    // Start the camera for image capture
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

    // Capture the image from the video feed
    captureImage() {
      const canvas = document.createElement("canvas");
      const context = canvas.getContext("2d");
      const video = this.$refs.video;

      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;

      context.drawImage(video, 0, 0, canvas.width, canvas.height);
      this.nbi_proof = canvas.toDataURL("image/png"); // Store captured image in nbi_proof
    },

    // Recapture the image
    recaptureImage() {
      this.capturedImage = null;
      this.nbi_proof = null;
    },

    // Submit the form
    submitForm() {
      const formData = new FormData();
      formData.append("nbi_validity_date", this.nbi_validity_date);
      formData.append("nbi_printed_date", this.nbi_printed_date);
      formData.append("nbi_remarks", this.nbi_remarks);

      if (this.nbi_proof) {
        const file = this.dataURLtoBlob(this.nbi_proof);
        formData.append("nbi_image", file);
      }

      console.log("Form submitted", formData);
    },

    // Convert base64 image to Blob
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
      // Stop the camera if it was started
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
