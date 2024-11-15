<template>
  <div class="py-0">
    <div class="pl-2 pr-2">
      <div class="flex flex-wrap mb-2">
        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
          <div class="p-2 bg-green-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-1 text-left">
                <h5 class="text-white">No. of Items</h5>
                <h3 class="text-3xl text-white">
                  {{ filteredTotalSupply }}<span class="text-green-400"></span>
                </h3>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
          <div class="p-2 bg-purple-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-1 text-left">
                <h5 class="text-white">Quantity Total</h5>
                <h3 class="text-3xl text-white">
                  {{ filteredTotalOriginalQuantity }}
                </h3>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
          <div class="p-2 bg-blue-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-1 text-left">
                <h5 class="text-white">Dispatched</h5>
                <h3 class="text-3xl text-white">
                  {{ filteredTotalDispatched }}
                </h3>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
          <div class="p-2 bg-orange-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-1 text-left">
                <h5 class="text-white">Remaining</h5>
                <h3 class="text-3xl text-white">
                  {{ filteredTotalRemaining }}
                </h3>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
          <div class="p-2 bg-red-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-1 text-left">
                <h5 class="text-white">Premium Item</h5>
                <h3 class="text-3xl text-white">
                  {{ filteredTotalPremium }}
                </h3>
              </div>
            </div>
          </div>
        </div>

        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
          <div class="p-2 bg-yellow-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-1 text-left">
                <h5 class="text-white">Normal Item</h5>
                <h3 class="text-3xl text-white">
                  {{ filteredTotalNormal }}
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="scroll">
        <div class="w-2/3 mx-auto datatable-container">
          <div class="flex items-center col-span-6 space-x-2 md:col-span-4">
            <div class="flex items-center space-x-2">
              <label class="block font-semibold">
                Site
                <select
                  v-model="sites_selected"
                  class="block w-full mt-1 bg-white border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                  @change="getPrograms"
                >
                  <option disabled value="" selected>Please select one</option>
                  <option v-for="site in sites" :key="site.id" :value="site.id">
                    {{ site.name }}
                  </option>
                </select>
              </label>
              <button
                @click="resetFilter"
                class="h-8 px-3 mt-4 font-semibold text-white transition-colors duration-300 bg-gray-500 rounded hover:bg-gray-600"
              >
                Reset
              </button>
            </div>
            <button
              @click="exportToExcel"
              class="h-8 px-3 mt-4 font-semibold text-white transition-colors duration-300 bg-green-600 rounded hover:bg-green-600"
            >
              Export
            </button>
          </div>
          <DataTable
            :data="filteredItemsWithNonZeroQuantity"
            :columns="columns"
            class="table divide-y divide-gray-200 table-auto table-striped"
            :options="{
              responsive: false,
              autoWidth: false,
              pageLength: 10,
              lengthChange: true,
              ordering: true,
              scrollX: true,
              dom: 'frtlip',
              buttons: ['excel', 'csv'],
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
import { utils, writeFile } from "xlsx";

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
      filteredTotalSupply: 0,
      filteredTotalDispatched: 0,
      filteredTotalRemaining: 0,
      filteredTotalOriginalQuantity: 0,
      filteredTotalNormal: 0,
      filteredTotalPremium: 0,
      total: 0,
      columns: [
        {
          title: "No",
          render: function (data, type, row, meta) {
            return meta.row + 1;
          },
        },
        { data: "site_name", title: "Site" },
        { data: "item_name", title: "Item" },
        { data: "quantity", title: "Quantity" },
        { data: "original_quantity", title: "Original Quantity" },
        { data: "cost", title: "Price" },
        { data: "total_cost", title: "Total Price" },
        { data: "budget_code", title: "Budget Code" },
        { data: "type", title: "Type" },
        { data: "category", title: "Category" },
        { data: "date_expiry", title: "Expiration Date" },
        {
          data: "date_added",
          title: "Added Date",
          render: (data) => (data ? data.slice(0, -3) : ""),
        },

        { data: "created_by", title: "Added By" },
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
      let filteredData = [...this.items];

      if (this.sites_selected) {
        filteredData = filteredData.filter((item) => {
          return item.site.id === this.sites_selected;
        });
      }
      return filteredData;
    },
    filteredItemsWithNonZeroQuantity() {
      return this.filteredItems.filter((item) => item.quantity !== 0);
    },
  },
  watch: {
    sites_selected: "getItems",
  },
  mounted() {
    window.vm = this;
    this.getSites();
    this.getItems();
  },
  methods: {
    resetFilter() {
      this.sites_selected = "";
    },
    generateExcelData(data) {
      const customHeaders = [
        "ID",
        "Site",
        "Item Name",
        "Available",
        "Original Quantity",
        "Cost",
        "Total Cost",
        "Type",
        "Category",
        "Expiration Date",
        "Added By",
        "Date Added",
        "Budget Code",
      ];

      const excelData = [
        customHeaders,
        ...data.map((item) => [
          item.id,
          item.site.name,
          item.item_name,
          item.quantity,
          item.original_quantity,
          item.cost,
          item.total_cost,
          item.type,
          item.category,
          item.date_expiry,
          item.created_by ? item.created_by.name : "N/A",
          item.date_added,
          item.budget_code,
        ]),
      ];

      return excelData;
    },

    exportToExcel() {
      const filteredData = this.filteredItems;

      if (this.sites_selected) {
        const excelData = this.generateExcelData(filteredData);
        const worksheet = utils.aoa_to_sheet(excelData);
        const workbook = utils.book_new();
        utils.book_append_sheet(workbook, worksheet, "Supply Data");
        writeFile(workbook, "supply_export.xlsx");
      } else {
        const excelData = this.generateExcelData(this.items);
        const worksheet = utils.aoa_to_sheet(excelData);
        const workbook = utils.book_new();
        utils.book_append_sheet(workbook, worksheet, "Supply Data");
        writeFile(workbook, "supply_export.xlsx");
      }
    },
    async getSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("https://10.236.103.168/api/sites", {
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
    async getItems() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "https://10.236.103.168/api/itemsboth",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.items = response.data.items;
          this.totalItems = this.items.length;

          const filteredData = this.items.filter((item) => item.quantity > 0);
          this.filteredTotalSupply = filteredData.length;
          this.filteredTotalOriginalQuantity = this.calculateSum(
            filteredData,
            "original_quantity"
          );
          this.filteredTotalRemaining = this.calculateSum(
            filteredData,
            "quantity"
          );
          this.filteredTotalNormal = this.calculateSumByCategory(
            filteredData,
            "Normal"
          );
          this.filteredTotalPremium = this.calculateSumByCategory(
            filteredData,
            "Premium"
          );

          this.filteredTotalDispatched =
            this.filteredTotalRemaining - this.filteredTotalOriginalQuantity;

          this.filteredTotalDispatched = Math.abs(this.filteredTotalDispatched);
        } else {
          console.log("Error fetching items");
        }
      } catch (error) {
        console.log(error);
      }
    },

    calculateSum(data, property) {
      return data.reduce((sum, item) => (Number(item[property]) || 0) + sum, 0);
    },

    calculateSumByCategory(data, category) {
      return data.reduce((sum, item) => {
        if (item.category === category) {
          return (Number(item.quantity) || 0) + sum;
        }
        return sum;
      }, 0);
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
