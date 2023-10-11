<template>
    <div class="p-4">
      <div class="mb-4 flex flex-wrap space-y-2 md:space-y-0 md:space-x-2">
        <input
          v-model="filterLastName"
          placeholder="Filter by Last Name"
          class="p-2 border rounded-lg"
        />
        <input
          v-model="filterFirstName"
          placeholder="Filter by First Name"
          class="p-2 border rounded-lg"
        />
        <input
          v-model="filterMiddleName"
          placeholder="Filter by Middle Name"
          class="p-2 border rounded-lg"
        />
        <button
          @click="fetchData"
          class="px-4 py-2 bg-blue-500 text-white rounded-lg"
        >
          Filter
        </button>
        <button
          @click="exportToExcel"
          class="px-4 py-2 bg-blue-500 text-white rounded-lg"
        >
          Export
        </button>
      </div>
  
      <DataTable
        :data="sites"
        :columns="columns"
        class="table divide-y divide-gray-200 table-auto table-striped"
        :options="{
          responsive: true, // Enable responsive design
          autoWidth: false,
          pageLength: 10,
          lengthChange: true,
          ordering: true,
          scrollX: true,
          dom: 'rtlip',
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
          <tr>
            <!-- Add table headers here -->
          </tr>
        </thead>
        <tbody class="truncate">
          <tr v-for="item in sites" :key="item.id">
            <!-- Add table row data here -->
          </tr>
        </tbody>
      </DataTable>
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
        filterLastName: "",
        filterFirstName: "",
        filterMiddleName: "",
        sites: [],
        columns: [
        { data: "name", title: "ID" },
        { data: "region", title: "Site" },
        { data: "country", title: "Item Name" },
      ]
      };
    },
    methods: {
        generateExcelData(data) {
  if (!data) {
    return [];
  }
  const customHeaders = [
    "ID",
    "Site",
    "Awardee ID",
  ];

  const excelData = [
    customHeaders,
    ...data.map((item) => [
      item.name,
      item.region,
      item.country,
    ]),
  ];

  return excelData;
},

    exportToExcel() {
      const filteredData = this.sites;

      if (this.sites_selected) {
        const excelData = this.generateExcelData(filteredData);
        const worksheet = utils.aoa_to_sheet(excelData);
        const workbook = utils.book_new();
        utils.book_append_sheet(workbook, worksheet, "PERX Data");
        writeFile(workbook, "supply_export.xlsx");
      } else {
        const excelData = this.generateExcelData(this.sites);
        const worksheet = utils.aoa_to_sheet(excelData);
        const workbook = utils.book_new();
        utils.book_append_sheet(workbook, worksheet, "PERX Data");
        writeFile(workbook, "supply_export.xlsx");
      }
    },
      async fetchData() {
    console.log("Filter LastName:", this.filterLastName);
    console.log("Filter FirstName:", this.filterFirstName);
    console.log("Filter MiddleName:", this.filterMiddleName);
        try {
          const token = this.$store.state.token;
          const response = await axios.get("http://127.0.0.1:8000/api/perxfilter", {
            params: {
              filter_lastname: this.filterLastName,
              filter_firstname: this.filterFirstName,
              filter_middlename: this.filterMiddleName,
            },
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
  
          this.sites = response.data.perx;
        } catch (error) {
          console.error("Error fetching data", error);
        }
      },
    },
  };
  </script>