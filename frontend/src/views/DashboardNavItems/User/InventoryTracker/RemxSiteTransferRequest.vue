<template>
  <div class="py-0">
    <div class="px-1 py-0 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
      <div
        class="fixed inset-0 z-50 flex items-center justify-center modal"
        v-if="showModal"
      >
        <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
        <div class="max-w-sm p-4 bg-white rounded shadow-lg modal-content">
          <header class="px-4 py-2 border-b-2 border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Receive Request</h2>
          </header>
          <button
            @click="showModal = false"
            class="absolute top-0 right-0 m-4 text-gray-600 hover:text-gray-800"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
          <span v-if="successMessage" class="text-green-500">{{
            successMessage
          }}</span>
          <form
            @submit.prevent="receivedRequest(receivedId)"
            class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-1"
          >
            <div class="col-span-1">
              <label class="block"
                >Receive Request
                <select
                  v-model="received_status"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                >
                  <option value="complete">Complete</option>
                  <option value="partial">Partial</option>
                </select>
              </label>
            </div>
            <div class="col-span-1" v-if="received_status === 'partial'">
              <label class="block">
                Quantity Received
                <input
                  type="text"
                  v-model="received_quantity"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
                <p
                  v-if="errors.received_quantity"
                  class="mt-1 text-xs text-red-500"
                >
                  {{ errors.received_quantity }}
                </p>
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                <input type="file" @change="handleFileChange" />
                <img :src="previewImage" v-if="previewImage" alt="Preview" />
                <p v-if="errors.file_name" class="mt-1 text-xs text-red-500">
                  {{ errors.file_name }}
                </p>
              </label>
            </div>

            <div class="flex justify-end mt-4">
              <button
                type="submit"
                class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
              >
                Submit
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="py-0">
    <div class="pl-8 pr-8">
      <div class="scroll">
        <div class="w-2/3 mx-auto datatable-container">
          <DataTable
            :data="inventory"
            :columns="columns"
            class="table divide-y divide-gray-200 table-auto table-striped"
            :options="{
              responsive: false,
              autoWidth: false,
              pageLength: 10,
              lengthChange: true,
              ordering: true,
              scrollX: true,
              dom: 'frtip',
              language: {
                search: 'Search',
                zeroRecords: 'No data available',
                info: 'Showing from _START_ to _END_ of _TOTAL_ records',
                infoFiltered: '(Filtered from MAX records)',
                paginate: {
                  first: 'First',
                  previous: 'Prev',
                  next: 'Next',
                  last: 'Last',
                },
              },
            }"
          >
            <thead class="truncate">
              <tr></tr>
            </thead>
          </DataTable>
        </div>
      </div>
    </div>
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
      sites: [],
      inventory: [],
      received_status: "",
      received_quantity: "",
      showModal: false,
      errors: {},
      successMessage: "",
      siteRequestId: null,
      selectedFile: null,
      previewImage: null,
      columns: [
        {
          title: "No",
          render: function (data, type, row, meta) {
            return meta.row + 1;
          },
        },
        {
          data: "id",
          title: "Actions",
          orderable: false,
          searchable: false,
          render: function (data) {
            const isUser = this.isUser;
            const isRemx = this.isRemx;
            const isSourcing = this.isSourcing;

            return `
            ${
              isUser || isRemx || isSourcing
                ? `<button class="w-20 text-xs btn btn-primary" data-id="${data}" onclick="window.vm.openModalForReceived(${data})">Received</button>`
                : ""
            }
          `;
          }.bind(this),
        },
        { data: "site.name", title: "Site" },
        {
          data: null,
          title: "Item Name",
          render: (data, type, row) => {
            if (row.site_inventory && row.site_inventory.item_name) {
              return row.site_inventory.item_name;
            } else if (row.item && row.item.item_name) {
              return row.item.item_name;
            } else {
              return "N/A";
            }
          },
        },
        {
          data: null,
          title: "Budget Code",
          render: (data, type, row) => {
            if (row.site_inventory && row.site_inventory.budget_code) {
              return row.site_inventory.budget_code;
            } else if (row.item && row.item.budget_code) {
              return row.item.budget_code;
            } else {
              return "N/A";
            }
          },
        },
        { data: "quantity_approved", title: "Quantity Requested" },
        { data: "transferred_by.name", title: "Transferred By" },
        { data: "transferred_from.name", title: "Transferred From" },
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
    received_quantity(newValue) {
      const quantityApproved = this.inventory
        .find((item) => item.id === this.receivedId)
        ?.quantity_approved.toString();

      if (newValue === quantityApproved) {
        this.received_status = "complete";
      } else if (this.received_status === "complete") {
        this.received_status = "";
      }
    },
  },
  mounted() {
    window.vm = this;
    this.getInventory();
  },
  methods: {
    async handleFileChange(event) {
      const selectedFile = event.target.files[0];

      if (!selectedFile) {
        return;
      }

      this.selectedFile = selectedFile;

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
                  this.selectedFile = blob; // Update this.selectedFile with the resized blob
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
    openModalForReceived(id) {
      this.receivedId = id;
      this.showModal = true;
    },
    receivedRequest(id) {
      this.errors = {};
      if (this.received_status === "partial" && !this.received_quantity) {
        this.errors.received_quantity = "Quantity Received is required.";
      } else if (
        this.received_status === "partial" &&
        parseInt(this.received_quantity) >
          parseInt(
            this.inventory.find((item) => item.id === id).quantity_approved
          )
      ) {
        this.errors.received_quantity =
          "Quantity Received cannot exceed Quantity Requested.";
      }

      if (Object.keys(this.errors).length > 0) {
        return;
      }
      const formData = new FormData();
      formData.append("file_name", this.selectedFile);
      formData.append("received_by", this.$store.state.user_id);
      formData.append("received_quantity", this.received_quantity);
      formData.append("received_status", this.received_status);

      const config = {
        headers: {
          Authorization: `Bearer ${this.$store.state.token}`,
        },
      };

      axios
        .post(
          `http://127.0.0.1:8000/api/inventory/transferremx/${id}`,
          formData,
          config
        )
        .then((response) => {
          console.log(response.data.data);
          this.getInventory();
          this.successMessage = "Received successfully!";
          this.showModal = false;
          console.log("Received by:", this.$store.state.user_id);
          window.location.reload();
        })
        .catch((error) => {
          console.log(error.response.data.data);
          console.log("Received by:", this.$store.state.user_id);
        });
    },
    async getInventory() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/inventory/remxForTransfer",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.inventory = response.data.inventory;
          console.log(response.data.inventory);
        } else {
          console.log("Error fetching inventory");
        }
      } catch (error) {
        console.log(error);
      }
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
</style>
