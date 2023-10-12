<template>
  <div class="py-0`">
    <div class="px-0 py-1 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
      <div
        class="fixed inset-0 z-50 flex items-center justify-center modal"
        v-if="showModal"
      >
        <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
        <div class="max-w-sm p-4 bg-white rounded shadow-lg modal-content">
          <header class="px-4 py-2 border-b-2 border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Transfer Supply</h2>
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
          <div class="modal-scrollable-content">
            <form
              @submit.prevent="transferItems"
              class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-1"
            >
              <div class="col-span-1">
                <label class="block">
                  Site
                  <select
                    v-model="sites_selected"
                    class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                  >
                    <option disabled value="" selected>Please select one</option>
                    <option v-for="site in sites" :key="site.id" :value="site.id">
                      {{ site.name }}
                    </option>
                  </select>
                  <p v-if="errors.sites_selected" class="mt-1 text-xs text-red-500">
                    {{ errors.sites_selected }}
                  </p>
                </label>
              </div>
              <div class="col-span-1 hidden">
                <label class="block">
                  Site
                  <select
                    v-model="sites1_selected"
                    class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                  >
                    <option disabled value="" selected>Please select one</option>
                    <option v-for="site in sites" :key="site.id" :value="site.id">
                      {{ site.name }}
                    </option>
                  </select>
                  <p v-if="errors.sites_selected" class="mt-1 text-xs text-red-500">
                    {{ errors.sites_selected }}
                  </p>
                </label>
              </div>
              <div class="col-span-1 hidden">
                <label class="block">
                  Item Name
                  <select
                    @change="onItemSelected"
                    v-model="items_selected"
                    class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                  >
                    <option disabled value="" selected>Please select one</option>
                    <option v-for="item in items" :key="item.id" :value="item.id">
                      {{ item.item_name }}
                    </option>
                  </select>
                  <p v-if="errors.items_selected" class="text-red-500 text-xs mt-1">
                    {{ errors.items_selected }}
                  </p>
                </label>
              </div>
              <div class="col-span-1">
                <label class="block">
                  Quantity Available
                  <input
                    readonly
                    type="number"
                    v-model="quantity"
                    class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                  />
                  <p v-if="errors.quantity" class="mt-1 text-xs text-red-500">
                    {{ errors.quantity }}
                  </p>
                </label>
              </div>
              <div class="col-span-1">
                <label class="block">
                  Quantity to be Transfer
                  <input
                    type="number"
                    v-model="transferred_quantity"
                    class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                  />
                  <p v-if="errors.transferred_quantity" class="mt-1 text-xs text-red-500">
                    {{ errors.transferred_quantity }}
                  </p>
                </label>
              </div>
              <div class="flex justify-between mt-4">
                <button
                  @click="showModal = false"
                  type="button"
                  class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600"
                >
                  Cancel
                </button>
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
  </div>
  <div class="py-0">
    <div class="pl-8 pr-8">
      <div class="scroll">
        <div class="w-2/3 mx-auto datatable-container">
          <DataTable
            :data="items"
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
      items: [],
      sites_selected: "",
      sites1_selected: "",
      items_selected: "",
      quantity: "",
      transferred_quantity: "",
      showModal: false,
      errors: {},
      columns: [
        { data: "id", title: "ID" },
        {
          data: "id",
          title: "Actions",
          orderable: false,
          searchable: false,
          render: function (data) {
            const isUser = this.isUser;
            const isRemx = this.isRemx;

            return `
            ${
              isUser || isRemx
                ? `
                    <button class="w-20 text-xs btn btn-primary" data-id="${data}" onclick="window.vm.openModalForTransfer(${data})">Transfer</button>`
                : ""
            }
          `;
          }.bind(this),
        },
        { data: "site.name", title: "Site" },
        { data: "item_name", title: "Item" },
        {
          data: "quantity",
          title: "Quantity",
          render: function (data) {
            return data ? data : 0;
          },
        },
        { data: "cost", title: "Price" },
        {
          data: "total_cost",
          title: "Total Price",
          render: function (data, type, row) {
            const cost = row.cost || 0;
            const quantity = row.quantity || 0;
            const totalCost = cost * quantity;
            return totalCost.toFixed(2);
          },
        },
        { data: "budget_code", title: "Budget Code" },
        { data: "type", title: "Type" },
        { data: "category", title: "Category" },
        { data: "date_expiry", title: "Expiration Date" },
        {
          data: "date_received",
          title: "Received Date",
          render: (data) => (data ? data.slice(0, -3) : ""),
        },
        {
          data: "received_by.name",
          title: "Received By",
          render: (data, type, row) => {
            return row.received_by ? row.received_by.name : "N/A";
          },
        },
        {
          data: "transferred_by.name",
          title: "Transferred By",
          render: (data, type, row) => {
            return row.transferred_by ? row.transferred_by.name : "N/A";
          },
        },
      ],
    };
  },
  watch: {},
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
  mounted() {
    window.vm = this;
    this.getSites();
    this.getItems();
  },
  methods: {
    openModalForTransfer(id) {
      const clickedItem = this.items.find((item) => item.id === id);
      if (clickedItem) {
        this.quantity = clickedItem.quantity;
        this.sites1_selected = clickedItem.site_id;
        this.items_selected = clickedItem.id;
      }
      // Show the modal
      this.showModal = true;
    },
    async getItems() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://10.109.2.112:8081/api/siteinventoryall", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.items = response.data.items;
          console.log(response.data.items);
        } else {
          console.log("Error fetching items");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://10.109.2.112:8081/api/sites", {
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
    transferItems() {
      this.errors = {};
      if (!this.sites_selected) {
        this.errors.sites_selected = "Site is required.";
      }
      if (!this.transferred_quantity) {
        this.errors.transferred_quantity = "Quantity Request is required.";
      } else if (parseInt(this.transferred_quantity) > parseInt(this.quantity)) {
        this.errors.transferred_quantity =
          "Quantity Request cannot exceed available quantity.";
      }
      if (Object.keys(this.errors).length > 0) {
        return;
      }
      const formData = {
        inventory_item_id: this.items_selected,
        site_id: this.sites_selected,
        quantity_approved: this.transferred_quantity,
        transferred_by: this.$store.state.user_id,
        transferred_to: this.sites_selected,
        transferred_from: this.sites1_selected,
      };
      axios
        .post("http://10.109.2.112:8081/api/transfer", formData, {
          headers: {
            Authorization: `Bearer ${this.$store.state.token}`,
          },
        })
        .then((response) => {
          if (response && response.data && response.data.Request) {
            console.log(response.data.Request);
            // ... rest of your code
          } else {
            console.error("Response or Request property is undefined.");
          }
        })
        .catch((error) => {
          if (error.response && error.response.data) {
            console.log(error.response.data);
          } else {
            console.error("Error response or error.response.data is undefined.");
          }
        });
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
/* Updated Radio Button Styles */
input[type="radio"] {
  /* Hide the default radio button */
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
  /* Add custom styling for the checked state */
  border-color: #3b71ca; /* Blue color for checked state */
}

input[type="radio"]:checked::before {
  /* Add the blue dot inside the checked radio button */
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: #3b71ca; /* Blue color for the dot */
}

/* Optional: Increase the size of the radio button and the blue dot */
input[type="radio"] {
  width: 20px;
  height: 20px;
}

input[type="radio"]:checked::before {
  width: 10px;
  height: 10px;
}
</style>
