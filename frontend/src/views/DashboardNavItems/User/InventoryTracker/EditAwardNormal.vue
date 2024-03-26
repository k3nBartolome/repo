<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h2 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Program Manager
      </h2>
    </div>
  </header>
  <div class="px-12 py-8 font-serifs">
    <span v-if="successMessage" class="text-green-500">{{
      successMessage
    }}</span>
    <span v-if="errorMessage" class="text-red-500">{{ errorMessage }}</span>
    <form @submit.prevent="editProgram">
      <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <label class="block font-semibold"
            >Name<input
              type="text"
              v-model="name"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
          /></label>
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <label class="block font-semibold"
            >Description<input
              type="text"
              v-model="description"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
          /></label>
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <label class="block font-semibold"
            >Program Group<input
              type="text"
              v-model="program_group"
              class="w-full px-4 py-2 bg-white border rounded-lg"
          /></label>
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <label class="block font-semibold"
            >Program Type<select
              v-model="program_type"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
            >
              <option disabled value="" selected>Please select one</option>
              <option value="BAU" selected>BAU</option>
              <option value="B2" selected>B2</option>
              <option value="COMCAST" selected>COMCAST</option>
              <option value="DULY" selected>DULY</option>
              <option value="TEMU" selected>TEMU</option>
            </select></label
          >
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <label class="block font-semibold"
            >ID Creation<select
              v-model="id_creation"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
            >
              <option disabled value="" selected>Please select one</option>
              <option value="Yes" selected>Yes</option>
              <option value="No" selected>No</option>
            </select></label
          >
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <label class="block font-semibold"
            >Pre Emps<select
              v-model="pre_emps"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
            >
              <option disabled value="" selected>Please select one</option>
              <option value="Yes" selected>Yes</option>
              <option value="No" selected>No</option>
            </select></label
          >
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <label class="block font-semibold"
            >Site<select
              v-model="sites_selected"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
              @change="getSites"
            >
              <option disabled value="" selected>Please select one</option>
              <option v-for="site in sites" :key="site.id" :value="site.id">
                {{ site.name }}
              </option>
            </select></label
          >
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <label class="block font-semibold">
            <button
              type="submit"
              class="w-full px-4 py-2 mt-4 font-bold text-white bg-orange-500 rounded hover:bg-gray-600"
            >
              <i class="fa fa-building"></i> Save
            </button>
          </label>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import axios from "axios";
import DataTable from "datatables.net-vue3";
import DataTableLib from "datatables.net-bs5";
// eslint-disable-next-line no-unused-vars
import Buttons from "datatables.net-buttons-bs5";
import ButtonsHtml5 from "datatables.net-buttons/js/buttons.html5";
// eslint-disable-next-line no-unused-vars
import print from "datatables.net-buttons/js/buttons.print";
//import pdfmake from "pdfmake";
// eslint-disable-next-line no-unused-vars
import pdfFonts from "pdfmake/build/vfs_fonts";
import "datatables.net-responsive-bs5";
// eslint-disable-next-line no-unused-vars

import "bootstrap/dist/css/bootstrap.css";

DataTable.use(DataTableLib);
//DataTable.use(pdfmake);
DataTable.use(ButtonsHtml5);

export default {
  components: { DataTable },
  data() {
    return {
      selectedFile: null,
      previewImage: null,
      sites: [],
      award: [],
      site_items: [],
      sites_selected: "",
      item_name: "",
      quantity: "",
      errors: {},
      items_selected: "",
      remarks: "",
      awardee_name: "",
      awardee_hrid: "",
      awarded_quantity: "",
      budget_code: "",
      release:[],
      showModal: false,
      ShowEditModal: false,
      loading: false,
      editRequestId: null,
      columns: [
        { data: "id", title: "ID" },
        { data: "site.name", title: "Site" },
        { data: "items.item_name", title: "Item Name" },
        { data: "awarded_quantity", title: "Awarded Quantity" },
        { data: "awardee_hrid", title: "Awardee ID" },
        { data: "awardee_name", title: "Awardee Name" },
        { data: "released_by.name", title: "Released By" },
        { data: "date_released", title: "Date Released" },
        { data: "remarks", title: "Remarks" },
        {
          data: "image_path",
          title: "Image",
          render: (data, type) => {
            if (type === "display" && data) {
              return `<button onclick="window.vm.openImageModal('${data}')">
                <img src="${data}" alt="Image" width="50" height="50" loading="lazy"/>
              </button>`;
            }
            return "";
          },
        },
        {
          data: "id",
          title: "Actions",
          orderable: false,
          searchable: false,
          render: function (data) {
            return `<button class="btn btn-primary w-36" data-id="${data}" onclick="window.vm.openModalForEdit(${data})">Edit</button>
  `;
          },
        },
      ],
    };
  },
  computed: {
    imageSource() {
      return this.capturedImage ? this.capturedImage : this.selectedImage;
    },
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
    items_selected(newItemId) {
      const selectedItem = this.site_items.find(
        (site_items) => site_items.id === newItemId
      );
      if (selectedItem) {
        this.budget_code = selectedItem.budget_code;
        this.quantity = selectedItem.quantity;
      }
      
    },
    editRequestId: {
    immediate: true,
    handler(newVal) {
      if (newVal !== null) {
        this.getAwarded();
      }
    }
  },
    sites_selected: {
      immediate: true,
      handler() {
        this.getItems();
        this.budget_code = null;
        this.quantity = null;
        this.item_name = null;
      },
    },
  },
  mounted() {
    window.vm = this;
    this.getSites();
    this.getItems();
    this.getAward();
    this.getAwarded();

  },
  methods: {
    
    async openModalForEdit(id) {
  this.editRequestId = id;
  console.log("editRequestId after setting:", this.editRequestId); // Debugging
  await this.$nextTick(); // Ensure Vue updates the DOM before continuing
  this.ShowEditModal = true;
  console.log("ShowEditModal after setting:", this.ShowEditModal); // Debugging
  // Now get the data
  this.getAwarded();
},


async getAwarded() {
  try {
    const token = this.$store.state.token;
    const response = await axios.get(
      `http://127.0.0.1:8000/api/awarded/${this.editRequestId}`,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    );

    if (response.status === 200) {
      const releasedObj = response.data.item;
      this.awardee_name = releasedObj.awardee_name;
      this.awardee_hrid = releasedObj.awardee_hrid;
      this.remarks = releasedObj.remarks;

      // Construct the URL to the image
      const imageURL =  releasedObj.image_path;
      
      // Set the imageURL to previewImage
     
      this.previewImage = imageURL;

      console.log(releasedObj);
    } else {
      console.log("Error fetching items");
    }
  } catch (error) {
    console.log(error);
  }
},

async EditNormalItem() {
      this.errors = {};
      this.loading = true;
      if (!this.awardee_name) {
        this.errors.awardee_name = "Awardee Name is required.";
      }
      if (!this.awardee_hrid) {
        this.errors.awardee_hrid = "Awardee ID is required.";
      }
      if (!this.remarks) {
        this.errors.remarks = "Remarks is required.";
      }
      if (Object.keys(this.errors).length > 0) {
        return;
      }
      const formData = new FormData();
      formData.append("file_name", this.selectedFile);
      formData.append("awardee_name", this.awardee_name);
      formData.append("remarks", this.remarks);
      formData.append("awardee_hrid", this.awardee_hrid);

      try {
        const response = await axios.put(
          `http://127.0.0.1:8000/api/awarded/${this.editRequestId}`,
          formData,
          {
            headers: {
              Authorization: `Bearer ${this.$store.state.token}`,
              "Content-Type": "multipart/form-data",
            },
          }
        );

        console.log("Awarded:", response.data.Award);
        this.showModal = false;
        this.clearForm1();
        this.getAward();
      } catch (error) {
        console.error("Error awarding:", error.response.data);
      } finally {
        this.loading = false;
      }
    },
    clearForm1() {
      this.items_selected = "";
      this.awarded_quantity = "";
      this.sites_selected = "";
      this.awardee_name = "";
      this.awardee_hrid = "";
      this.remarks = "";
      this.selectedFile = null;
    },
    openImageModal(imageUrl) {
      const modal = document.querySelector(".image-modal");
      const enlargedImage = document.querySelector(".enlarged-image");

      enlargedImage.src = imageUrl;
      modal.style.display = "flex";
    },
    closeImageModal() {
      const modal = document.querySelector(".image-modal");

      modal.style.display = "none";
    },
    async handleFileChange(event) {
      const selectedFile = event.target.files[0];

      if (!selectedFile) {
        return;
      }

      const maxSizeInBytes = 2 * 1024 * 1024; // 2 MB threshold

      if (selectedFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();

          reader.onload = (event) => {
            image.src = event.target.result;

            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;

              // Calculate new dimensions to fit within maxWidth
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }

              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);

              canvas.toBlob(
                async (blob) => {
                  this.selectedFile = blob;
                  this.previewImage = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewImage);
                },
                "image/jpeg",
                quality
              );
            };
          };

          reader.readAsDataURL(selectedFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        // No need to resize for smaller images
        this.selectedFile = selectedFile;
        this.previewImage = URL.createObjectURL(selectedFile);

        console.log("Preview Image URL:", this.previewImage);
      }
    },
    async compressBlob(blob, maxSize) {
      const image = new Image();
      const reader = new FileReader();
      const maxQuality = 0.8;

      const compressedBlob = await new Promise((resolve) => {
        reader.onload = (event) => {
          image.src = event.target.result;

          image.onload = () => {
            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d");

            let newWidth = image.width;
            let newHeight = image.height;

            if (image.size > maxSize) {
              const scaleFactor = Math.sqrt(image.size / maxSize);
              newWidth = Math.floor(image.width / scaleFactor);
              newHeight = Math.floor(image.height / scaleFactor);
            }

            canvas.width = newWidth;
            canvas.height = newHeight;

            ctx.drawImage(image, 0, 0, newWidth, newHeight);

            canvas.toBlob(resolve, "image/jpeg", maxQuality);
          };
        };

        reader.readAsDataURL(blob);
      });

      return compressedBlob;
    },
    async getSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/sites", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.sites = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching sites");
        }
      } catch (error) {
        console.log(error);
      }
    },
    clearForm() {
      this.items_selected = "";
      this.awarded_quantity = "";
      this.sites_selected = "";
      this.awardee_name = "";
      this.awardee_hrid = "";
      this.remarks = "";
      this.selectedFile = null;
    },
  },
};
</script>
<style>
.table-responsive {
  overflow: auto;
}

.datatable-container {
  width: 100%;
}

.table {
  white-space: nowrap;
}

.table thead th {
  padding: 8px;
}

.table tbody td {
  padding: 8px;
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

.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  max-width: 100%;
  width: 100%;
  box-sizing: border-box;
}

.modal-content form {
  display: grid;
  grid-template-columns: 1fr;
}
.modal-scrollable-content {
  max-height: 70vh;
  overflow-y: auto;
}

input[type="radio"] {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  width: 16px;
  height: 16px;
  border: 2px solid #ccc;
  border-radius: 50%;
  outline: none;
  margin-right: 8px;
  cursor: pointer;
  position: relative;
}

input[type="radio"]:checked {
  border-color: #3b71ca;
}

input[type="radio"]:checked::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: #3b71ca;
}

input[type="radio"] {
  width: 20px;
  height: 20px;
}

input[type="radio"]:checked::before {
  width: 10px;
  height: 10px;
}
</style>
<style scoped>
/* Your loader styles here */
.loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* Ensure the loader is on top of other elements */
}

.loader-content {
  /* Style your loader content (SVG, text, etc.) */
  display: flex;
  align-items: center;
}

.icon {
  /* Style your SVG icon */
  height: 3rem; /* Adjust the size as needed */
  width: 3rem; /* Adjust the size as needed */
  animation: spin 1s linear infinite;
  stroke: rgba(107, 114, 128, 1);
}

.loading-text {
  /* Style your loading text */
  font-size: 1.5rem; /* Adjust the size as needed */
  line-height: 2rem; /* Adjust the size as needed */
  font-weight: 500;
  color: rgba(107, 114, 128, 1);
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
