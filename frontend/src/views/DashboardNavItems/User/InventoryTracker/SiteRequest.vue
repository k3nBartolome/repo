<template>
  <header class="w-full">
    <div class="flex items-center w-full max-w-screen-xl sm:px-2 lg:px-2">
      <h1 class="pl-8 text-sm font-bold tracking-tight text-gray-900">
        <button
          v-if="isUser || isRemx || isSourcing"
          @click="showModal = true"
          class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
        >
          ADD Request
        </button>
      </h1>
    </div>
  </header>
  <div class="py-1">
    <div class="px-1 py-1 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
      <div
        class="fixed inset-0 z-50 flex items-center justify-center modal"
        v-if="showModal"
      >
        <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
        <div class="max-w-sm p-4 bg-white rounded shadow-lg modal-content">
          <header class="px-4 py-2 border-b-2 border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Add Request</h2>
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
            @submit.prevent="addRequest"
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
                <p v-if="errors.sites_selected" class="text-red-500 text-xs mt-1">
                  {{ errors.sites_selected }}
                </p>
              </label>
            </div>
            <div class="col-span-1">
              <label class="block mb-2"> Category </label>
              <div class="flex items-center">
                <div
                  class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]"
                >
                  <input
                    class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300"
                    type="radio"
                    v-model="category"
                    value="Normal"
                    checked
                  />
                  <label
                    class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                    >Normal Item</label
                  >
                </div>
                <div
                  class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]"
                >
                <input
                class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300"
                type="radio"
                v-model="category"
                value="Premium"
              />
                  <label
                    class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                    >Premium Item</label
                  >
                </div>
              </div>
            </div>
            <div class="col-span-1">
              <label class="block">
                Item Name
                <select
                  @change="onItemSelected"
                  v-model="items_selected"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                >
                  <option disabled value="" selected>Please select one</option>
                  <option
                    v-for="item in filteredItems"
                    :key="item.id"
                    :value="item.id"
                  >
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
                Budget Code
                <input
                  type="text"
                  v-model="budget_code"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Quantity Available
                <input
                  type="number"
                  readonly
                  v-model="quantity"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Quantity Request
                <input
                  type="number"
                  v-model="quantity_approved"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
                <p v-if="errors.quantity_approved" class="text-red-500 text-xs mt-1">
                  {{ errors.quantity_approved }}
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
  <div class="py-2">
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
      items: [],
      inventory: [],
      sites_selected: "",
      items_selected: "",
      category: "Normal",
      item_name: "",
      quantity: "",
      quantity_approved: "",
      budget_code: "",
      errors: {},
      showModal: false,
      columns: [
        { data: "id", title: "ID" },
        { data: "site.name", title: "Site" },
        { data: "item.item_name", title: "Item Name" },
        { data: "item.budget_code", title: "Budget Code" },
        { data: "quantity_approved", title: "Quantity Requested" },
        { data: "status", title: "Approval Status" },
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
    filteredItems() {
      if (this.category === "Normal") {
        return this.items.filter((item) => item.category === "Normal");
      } else if (this.category === "Premium") {
        return this.items.filter((item) => item.category === "Premium");
      } else {
        return this.items;
      }
    },
  },
  watch: {
    category() {
      this.getItems();
    },
    items_selected(newItemId) {
      const selectedItem = this.items.find((item) => item.id === newItemId);
      if (selectedItem) {
        this.budget_code = selectedItem.budget_code;
        this.quantity = selectedItem.quantity;
      }
    },
  },
  mounted() {
    window.vm = this;
    this.getSites();
    this.getItems();
    this.getInventory();
  },
  methods: {
    onItemSelected() {
      const selectedItem = this.items.find(
        (item) => item.id === this.items_selected
      );

      if (selectedItem) {
        this.budget_code = selectedItem.budget_code;
        this.quantity = selectedItem.quantity;
      }
    },
    async getItems() {
      if (!this.category) {
        return;
      }

      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          `http://127.0.0.1:8000/api/itemseparate?category=${this.category}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.items = response.data.items;
          console.log(response.data.items);
        } else {
          console.log("Error fetching items");
        }
      } catch (error) {
        console.error(error);
      }
    },

    async getInventory() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/inventory",
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
    addRequest() {
      this.errors = {};
      if (!this.sites_selected) {
        this.errors.sites_selected = "Site is required.";
      }
      if (!this.items_selected) {
        this.errors.items_selected = "Item Name is required.";
      }
      if (!this.quantity_approved) {
      this.errors.quantity_approved = "Quantity Request is required.";
    } else if (parseInt(this.quantity_approved) > parseInt(this.quantity)) {
      this.errors.quantity_approved = "Quantity Request cannot exceed available quantity.";
    }
      if (Object.keys(this.errors).length > 0) {
        return;
      }
      const formData = {
        item_id: this.items_selected,
        site_id: this.sites_selected,
        quantity_approved: this.quantity_approved,
        is_active: 1,
        requested_by: this.$store.state.user_id,
      };
      axios
        .post("http://127.0.0.1:8000/api/inventory", formData, {
          headers: {
            Authorization: `Bearer ${this.$store.state.token}`,
          },
        })
        .then((response) => {
          console.log(response.data);
          this.items_selected = "";
          this.quantity_approved = "";
          this.sites_selected = "";
          this.getItems();
          this.getInventory();
          this.showModal = false;
        })
        .catch((error) => {
          console.log(error.response.data);
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
  max-width: 400px;
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
