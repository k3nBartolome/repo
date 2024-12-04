<template>
  <div
    class="flex items-start justify-center min-h-screen px-12 py-8 font-serifs"
  >
    <form @submit.prevent="editNormalItem" class="w-full max-w-md mx-auto mt-0">
      <div class="space-y-0">
        <!-- Awardee Name -->
        <div>
          <label class="block">
            Awardee Name
            <input
              type="text"
              v-model="awardee_name"
              class="block w-full mt-1 rounded border border-neutral-300 px-2 py-[0.17rem] text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
            />
            <p v-if="errors.awardee_name" class="mt-1 text-xs text-red-500">
              {{ errors.awardee_name }}
            </p>
          </label>
        </div>
        <!-- Awardee HRID -->
        <div>
          <label class="block">
            Awardee HRID
            <input
              type="text"
              v-model="awardee_hrid"
              class="block w-full mt-1 rounded border border-neutral-300 px-2 py-[0.17rem] text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
            />
            <p v-if="errors.awardee_hrid" class="mt-1 text-xs text-red-500">
              {{ errors.awardee_hrid }}
            </p>
          </label>
        </div>
        <!-- Remarks -->
        <div>
          <label class="block">
            Remarks
            <textarea
              v-model="remarks"
              class="block w-full mt-1 rounded border border-neutral-300 px-2 py-[0.17rem] text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
            ></textarea>
            <p v-if="errors.remarks" class="mt-1 text-xs text-red-500">
              {{ errors.remarks }}
            </p>
          </label>
        </div>
        <!-- File Upload -->
        <div>
          <label class="block">
            <input type="file" @change="handleFileChange" />
            <img :src="previewImage" v-if="previewImage" alt="Preview" />
            <p v-if="errors.file_name" class="mt-1 text-xs text-red-500">
              {{ errors.file_name }}
            </p>
          </label>
        </div>
        <!-- Submit Button -->
        <div>
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
// eslint-disable-next-line no-unused-vars
import pdfFonts from "pdfmake/build/vfs_fonts";
import "datatables.net-responsive-bs5";
// eslint-disable-next-line no-unused-vars
import "bootstrap/dist/css/bootstrap.css";
DataTable.use(DataTableLib);
DataTable.use(ButtonsHtml5);
export default {
  data() {
    return {
      selectedFile: null,
      previewImage: null,
      sites: [],
      errors: {},
      remarks: "",
      awardee_name: "",
      awardee_hrid: "",
      awarded_quantity: "",
      budget_code: "",
      loading: false,
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
  },
  mounted() {
    window.vm = this;
    this.getSites();
    this.getAwarded();
  },
  methods: {
    async getAwarded() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://10.109.2.112:8000/api/awarded/" + this.$route.params.id,
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
          this.selectedFile = releasedObj.path;
          this.previewImage = releasedObj.image_path;
          console.log(releasedObj);
        } else {
          console.log("Error fetching items");
        }
      } catch (error) {
        console.log(error);
      }
    },

    async editNormalItem() {
      this.errors = {};

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

      formData.append("awardee_name", this.awardee_name);
      formData.append("awardee_hrid", this.awardee_hrid);
      formData.append("remarks", this.remarks);
      if (this.selectedFile) {
        formData.append("file_name", this.selectedFile);
      }
      try {
        const response = await axios.post(
          "http://10.109.2.112:8000/api/award/" + this.$route.params.id,
          formData,
          {
            headers: {
              Authorization: `Bearer ${this.$store.state.token}`,
              "Content-Type": "multipart/form-data",
            },
          }
        );

        console.log("Awarded:", response.data.Award);

        this.$router.push("/award_manager/premium", () => {
          location.reload();
        });
      } catch (error) {
        console.error("Error updating awarded item:", error.response.data);
        console.log("Awardee Name:", this.awardee_name);
        console.log("Awardee HRID:", this.awardee_hrid);
        console.log("Remarks:", this.remarks);
      }
    },

    async handleFileChange(event) {
      const selectedFile = event.target.files[0];

      if (!selectedFile) {
        return;
      }
      this.selectedFile = selectedFile;
      const maxSizeInBytes = 2 * 1024 * 1024;
      if (selectedFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();

          reader.onload = (event) => {
            image.src = event.target.result;

            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;
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
        const response = await axios.get("http://10.109.2.112:8000/api/sites", {
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
      this.awardee_name = "";
      this.awardee_hrid = "";
      this.remarks = "";
      this.selectedFile = null;
    },
  },
};
</script>
