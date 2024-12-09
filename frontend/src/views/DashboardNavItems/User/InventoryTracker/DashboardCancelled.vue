<template>
  <div class="py-0">
    <div class="pl-2 pr-2">
      <div class="flex flex-wrap mb-2">
        <div class="w-full px-1 py-3 sm:w-1/4 md:w-1/4 lg:w-1/4 xl:w-1/7">
          <div class="p-2 bg-green-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-1 text-left">
                <h5 class="text-white">No. of Released</h5>
                <h3 class="text-3xl text-white">
                  {{ filteredTotalReleased
                  }}<span class="text-green-400"></span>
                </h3>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/4 lg:w-1/4 xl:w-1/7">
          <div class="p-2 bg-blue-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-1 text-left">
                <h5 class="text-white">Released Quantity</h5>
                <h3 class="text-3xl text-white">{{ filteredTotalQuantity }}</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/4 lg:w-1/4 xl:w-1/7">
          <div class="p-2 bg-orange-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-1 text-left">
                <h5 class="text-white">Premium</h5>
                <h3 class="text-3xl text-white">{{ filteredTotalPremium }}</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/7">
          <div class="p-2 bg-purple-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-1 text-left">
                <h5 class="text-white">Normal</h5>
                <h3 class="text-3xl text-white">{{ filteredTotalNormal }}</h3>
              </div>
            </div>
          </div>
        </div>

        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/7">
          <div class="p-2 bg-red-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-1 text-left">
                <h5 class="text-white">Partial</h5>
                <h3 class="text-3xl text-white">
                  {{ filteredTotalPartialReceived }}
                </h3>
              </div>
            </div>
          </div>
        </div>
        <!--
        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/7">
          <div class="p-2 bg-yellow-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">

              <div class="flex-1 text-left">
                <h5 class="text-white">Complete</h5>
                <h3 class="text-3xl text-white">
                  {{ filteredTotalCompleteReceived }}
                </h3>
              </div>
            </div>
          </div>
        </div> -->
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
            :data="filteredItems"
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
import jszip from "jszip";
// eslint-disable-next-line no-unused-vars
import "bootstrap/dist/css/bootstrap.css";

DataTable.use(DataTableLib);
DataTable.use(jszip);
DataTable.use(ButtonsHtml5);

export default {
  components: { DataTable },
  data() {
    return {
      sites: [],
      award: [],
      sites_selected: "",
      filteredTotalReleased: 0,
      filteredTotalQuantity: 0,
      filteredTotalPremium: 0,
      filteredTotalNormal: 0,
      columns: [
        {
          title: "No",
          render: function (data, type, row, meta) {
            return meta.row + 1;
          },
        },
        { data: "site.name", title: "Site" },
        { data: "items.item_name", title: "Item Name" },
        { data: "awarded_quantity", title: "Released Quantity" },
        { data: "awardee_hrid", title: "Awardee ID" },
        { data: "awardee_name", title: "Awardee Name" },
        { data: "released_by.name", title: "Released By" },
        {
          data: "date_released",
          title: "Date Released",
          render: (data) => (data ? data.slice(0, -3) : ""),
        },
        { data: "remarks", title: "Remarks" },
        {
          data: "image_path",
          title: "Image",
          render: (data, type) => {
            if (type === "display" && data) {
              return `<img src="${data}" alt="Image" width="50" height="50" loading="lazy"/>`;
            }
            return "";
          },
        },
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
      let filteredData = [...this.award];

      if (this.sites_selected) {
        filteredData = filteredData.filter((item) => {
          return item.site.id === this.sites_selected;
        });
      }
      return filteredData;
    },
  },
  watch: {
    sites_selected: "getAward",
  },
  mounted() {
    window.vm = this;
    this.getSites();
    this.getAward();
  },
  methods: {
    generateExcelData(data) {
      const customHeaders = [
        "ID",
        "Site",
        "Awardee ID",
        "Awardee Name",
        "Date Released",
        "Item Name",
        "Released Quantity",
        "Category",
        "Type",
        "Released By",
      ];

      const excelData = [
        customHeaders,
        ...data.map((item) => [
          item.id,
          item.site.name,
          item.awardee_hrid,
          item.awardee_name,
          item.date_released,
          item.items.item_name,
          item.awarded_quantity,
          item.items.category,
          item.items.type,
          item.released_by ? item.released_by.name : "N/A",
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
        const excelData = this.generateExcelData(this.award);
        const worksheet = utils.aoa_to_sheet(excelData);
        const workbook = utils.book_new();
        utils.book_append_sheet(workbook, worksheet, "Supply Data");
        writeFile(workbook, "supply_export.xlsx");
      }
    },
    async getSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("https://10.109.2.112/api/sites", {
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
    async getAward() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "https://10.109.2.112/api/awarded/both",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        if (response.status === 200) {
          this.award = response.data.awarded;

          this.total = this.award.length;

          const filteredData = this.filteredItems;
          this.filteredTotalReleased = filteredData.length;

          this.filteredTotalQuantity = filteredData.reduce((sum, item) => {
            return sum + item.awarded_quantity;
          }, 0);
          this.filteredTotalNormal = filteredData.reduce((sum, item) => {
            if (item.items.category === "Normal") {
              return sum + item.awarded_quantity;
            }
            return sum;
          }, 0);
          this.filteredTotalPremium = filteredData.reduce((sum, item) => {
            if (item.items.category === "Premium") {
              return sum + item.awarded_quantity;
            }
            return sum;
          }, 0);
        } else {
          console.log("Error fetching awarded");
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
.card-stats {
  width: 100%;
  max-width: 400px;
  margin-bottom: 20px;
}

.card-stats .card-body {
  padding: 10px;
}
.link-button {
  text-decoration: none;
}
</style>
