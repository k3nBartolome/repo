<template>
  <div class="container mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4 text-center">NBI Upload</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Final Status</label>
        <select
          v-model="nbi_final_status"
          @change="updateNBIData"
          class="p-2 mt-1 border rounded w-full"
        >
          <option disabled>Please select one</option>
          <option value="YES">Yes</option>
          <option value="NO">No</option>
          <option value="NO - HIT RECEIPT">NO - HIT RECEIPT</option>
          <option value="QUALITY CONTROL">QUALITY CONTROL</option>
        </select>
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Validity Date</label>
        <input
          v-model="nbi_validity_date"
          type="date"
          @change="updateNBIData"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Printed Date</label>
        <input
          v-model="nbi_printed_date"
          type="date"
          @change="updateNBIData"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Submitted Date</label>
        <input
          v-model="nbi_submitted_date"
          type="date"
          @change="updateNBIData"
          class="p-2 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Remarks</label>
        <input
          v-model="nbi_remarks"
          type="text"
          @input="updateNBIData"
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
    <div v-if="nbi_file_name" class="mt-4">
      <div class="flex flex-col items-center">
        <img
          :src="nbi_file_name"
          alt="Preview Image"
          class="object-cover w-full sm:w-3/4 lg:w-1/2 h-48 border rounded-lg"
          @load="updateNBIData"
        />
      </div>
    </div>
    <button
      :disabled="isSubmitting"
      @click="submitForm"
      class="mt-4 bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
    >
      {{ isSubmitting ? "Submitting..." : "Submit NBI" }}
    </button>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      showUpload: false,
      showCapture: false,
      nbi_final_status: null,
      nbi_validity_date: "",
      nbi_submitted_date: "",
      nbi_printed_date: "",
      nbi_remarks: "",
      nbi_file_name: null, // Used to store file path (image preview)
      capturedImage: null, // Used to store captured image // Store video stream for capture
      nbi_proof: null, // Used for the proof file or image data
      isSubmitting: false, // Tracks form submission status
    };
  },
    mounted() {

    this.fetchNBIData();
  },
  methods: {
     async fetchNBIData() {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/get/nbi/requirement/${this.$route.params.id}`);
        const data = response.data.data;

        // Populate the form fields with API response data
        this.nbi_final_status = data.nbi_final_status;
        this.nbi_validity_date = data.nbi_validity_date;
        this.nbi_submitted_date = data.nbi_submitted_date;
        this.nbi_printed_date = data.nbi_printed_date;
        this.nbi_remarks = data.nbi_remarks;
        this.nbi_file_name = data.nbi_file_name;
      } catch (error) {
        console.error("Error fetching NBI data:", error);
      }
    },
    updateNBIData() {
      console.log("Updating NBI Data...");
      console.log({
        nbi_final_status: this.nbi_final_status,
        nbi_validity_date: this.nbi_validity_date,
        nbi_submitted_date: this.nbi_submitted_date,
        nbi_printed_date: this.nbi_printed_date,
        nbi_remarks: this.nbi_remarks,
      });
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
      this.capturedImage = canvas.toDataURL("image/png");
      this.nbi_proof = this.capturedImage; // Store the captured image data
    },
    recaptureImage() {
      this.capturedImage = null;
      this.nbi_proof = null;
    },
     uploadImage(event) {
      const file = event.target.files[0];
      if (file) {
        this.nbi_proof = file; // Store the file in dt_proof

        const reader = new FileReader();
        reader.onload = () => {
          this.nbi_file_name = reader.result; // Preview the image
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

        this.nbi_proof = compressedFile;
        this.nbi_file_name = dataUrl;
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

    async submitForm() {
        this.isSubmitting = true;

        if (!this.nbi_final_status) {
            this.nbi_final_status = "NO STATUS"; // Default status
        }

        const formData = new FormData();
        formData.append("nbi_final_status", this.nbi_final_status);
        formData.append("nbi_validity_date", this.nbi_validity_date || "");
        formData.append("nbi_printed_date", this.nbi_printed_date || "");
        formData.append("nbi_submitted_date", this.nbi_submitted_date || "");
        formData.append("nbi_remarks", this.nbi_remarks || "");
        formData.append("nbi_updated_by", this.$store.state.user_id);

        if (this.nbi_proof instanceof File) {
            formData.append("nbi_proof", this.nbi_proof);
        }

        try {
            const apiUrl = `http://127.0.0.1:8000/api/update/nbi/requirement/${this.$route.params.id}`;
            const response = await axios.post(apiUrl, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
            console.log("Form submitted successfully", response.data);
            alert("Form submitted successfully!");
            this.$router.push({
                name: "OnboardingUpdateSelection",
                params: { id: this.$route.params.id },
            });
        } catch (error) {
            console.error(
                "Error submitting form",
                error.response ? error.response.data : error.message
            );
            alert("An error occurred while submitting the form.");
        } finally {
            this.isSubmitting = false;
        }
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

.fixed {
  position: fixed;
}

.bg-gray-500 {
  background-color: rgba(0, 0, 0, 0.5);
}

.p-6 {
  padding: 1.5rem;
}

.rounded-lg {
  border-radius: 0.5rem;
}

.shadow-lg {
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}
</style>
