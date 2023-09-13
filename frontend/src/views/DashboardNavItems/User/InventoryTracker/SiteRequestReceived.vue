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
                <p v-if="errors.received_quantity" class="text-red-500 text-xs mt-1">
                  {{ errors.received_quantity }}
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
              columnDefs: [
      {
        targets: [8], // Index of the column to hide (zero-based index)
        visible: false, // Set to true to show, false to hide
      },
    ],
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
      siteRequestId: null,
      columns: [
  { data: "id", title: "ID" },
  {
    data: null,
    title: "Actions",
    orderable: false,
    searchable: false,
    render: function (data) {
      //const isUser = this.isUser;
      //const isRemx = this.isRemx;
      //const isSourcing = this.isSourcing;
      const requestedById = data.requested_by ? data.requested_by.id : null;

      //console.log("Requested By ID:", requestedById);

      return `
        ${
          requestedById ===  this.$store.state.user_id
            ? `<button class="w-20 text-xs btn btn-primary" data-id="${data.id}" onclick="window.vm.openModalForReceived(${data.id})">Received</button>`
            : ""
        }
      `;
    }.bind(this),
  },
  { data: "site.name", title: "Site" },
  { data: "item.item_name", title: "Item Name" },
  { data: "item.budget_code", title: "Budget Code" },
  { data: "quantity_approved", title: "Quantity Requested" },
  { data: "status", title: "Approval Status" },
  { data: "requested_by.name", title: "Requested By" },
  { data: "requested_by.id", title: "" },
  { data: "approved_by.name", title: "Approved By" },
],

    };
  },
  computed: {
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
      const quantityApproved = this.inventory.find(item => item.id === this.receivedId)?.quantity_approved.toString();

      if (newValue === quantityApproved) {
        this.received_status = 'complete';
      } else if (this.received_status === 'complete') {
        this.received_status = '';
      }
    },
  },
  mounted() {
    window.vm = this;
    this.getInventory();
  },
  methods: {
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
          parseInt(this.inventory.find((item) => item.id === id).quantity_approved)
      ) {
        this.errors.received_quantity =
          "Quantity Received cannot exceed Quantity Requested.";
      }

      if (Object.keys(this.errors).length > 0) {
        return;
      }
      const form = {
        received_by: this.$store.state.user_id,
        received_quantity: this.received_quantity,
        received_status: this.received_status,
      };

      const config = {
        headers: {
          Authorization: `Bearer ${this.$store.state.token}`,
        },
      };

      axios
        .put(`http://127.0.0.1:8000/api/inventory/received/${id}`, form, config)
        .then((response) => {
          console.log(response.data.data);
          this.getInventory();
         this.showModal= false;
         this.$router.push("/site_request_manager/received", () => {
          location.reload();
        });
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
    async getInventory() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/inventory/approved/pending",
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
