<template>
  <div class="py-0">
    <header class="p-4 py-0 bg-white">
      <div class="max-w-screen-xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900">Classes</h2>
      </div>
    </header>
    <div class="p-4 bg-gray-100">
      <div class="mb-4 md:flex md:space-x-2 md:items-center">

        <div class="w-full md:w-1/4">
          <select
            v-model="filterSite"
            placeholder="Filter by Site"
            class="w-full p-2 border rounded-lg"
          >
            <option disabled value="" selected>Please select one</option>
            <option v-for="site in sites" :key="site.Id" :value="site.Name">
              {{ site.Name }}
            </option>
          </select>
        </div>
        <div class="w-full md:w-1/4">
          <select
            v-model="filterLob"
            placeholder="Filter by Lob"
            class="w-full p-2 border rounded-lg"
          >
            <option disabled value="" selected>Please select one</option>
            <option v-for="lob in lobs" :key="lob.Id" :value="lob.Name">
              {{ lob.Name }}
            </option>
          </select>
        </div>
        <div class="relative w-full md:w-1/4">
          <input
            v-model="filterWave"
            placeholder="Filter by Wave No."
            class="w-full p-2 border rounded-lg"

          />
        </div>
        <div class="w-full md:w-1/4">
          <button
            @click="fetchData"
            class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg"
          >
            Filter
          </button>
        </div>
        <div class="w-full md:w-1/4">
          <button
            @click="exportToExcel"
            class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg"
          >
            Export
          </button>
        </div>
      </div>
      <div
        v-if="filterLoading || exportLoading"
        class="font-bold text-center text-blue-500"
      >
        {{ filterLoading ? "Rendering..." : "Exporting..." }}
      </div>
      <DataTable
        :data="perx"
        :columns="columns"
        class="table divide-y divide-gray-200 table-auto table-striped"
        :options="{
          responsive: false,
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
            <!-- Add your table headers here -->
          </tr>
        </thead>
        <tbody class="truncate">
          <tr v-for="item in perx" :key="item.id">
            <!-- Add your table row data here -->
          </tr>
        </tbody>
      </DataTable>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";
import "datatables.net-buttons-bs5/css/buttons.bootstrap5.css";
import "datatables.net-responsive-bs5/css/responsive.bootstrap5.css";

import DataTable from "datatables.net-vue3";
import DataTableLib from "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";
import "datatables.net-buttons-bs5";
import "datatables.net-buttons-bs5/css/buttons.bootstrap5.css";
import "datatables.net-responsive-bs5";
import "datatables.net-responsive-bs5/css/responsive.bootstrap5.css";
import "bootstrap/dist/js/bootstrap.bundle"; // Make sure to include Bootstrap JavaScript

// Import DataTables extensions
import Buttons from "datatables.net-buttons-bs5";
import ButtonsHtml5 from "datatables.net-buttons/js/buttons.html5";
import print from "datatables.net-buttons/js/buttons.print";
import jszip from "jszip";
// eslint-disable-next-line no-unused-vars
import pdfMake from "pdfmake/build/pdfmake";
// eslint-disable-next-line no-unused-vars
import pdfFonts from "pdfmake/build/vfs_fonts";
import "bootstrap/dist/css/bootstrap.css";

// eslint-disable-next-line no-unused-vars
import { format } from "date-fns";

DataTable.use(DataTableLib);
DataTable.use(jszip);
DataTable.use(Buttons);
DataTable.use(ButtonsHtml5);
DataTable.use(print);

export default {
  components: { DataTable },
  data() {
    return {

      filterSite: "",
      filterWave: "",
      filterLob: "",
      selectedSite: "ALL",
      classes: [],
      sites: [],
      lobs: [],
      columns: [
        { data: "ApplicantId", title: "ApplicantId" },
        { data: "HRID", title: "HRID" },
        { data: "LastName", title: "Last Name" },
        { data: "FirstName", title: "First Name" },
        { data: "MiddleName", title: "Middle Name" },
        { data: "DateHired", title: "Date Hired" },
        { data: "Lob", title: "Account" },
        { data: "Wave", title: "Wave" },
        { data: "Position", title: "Position" },
        { data: "Site", title: "BLDG Assignment" },
        { data: "Salary", title: "Monthly Salary" },
        { data: "ND_Training", title: "ND%-Training" },
        { data: "ND_Production", title: "ND%-Production" },
        { data: "MS-Production", title: "Mid-Shift%-Production" },
        { data: "ComplexityAllowance", title: "Complexity Allowance" },

      ],
      filterLoading: false,
      exportLoading: false,
    };
  },
  mounted() {
    this.getSites();
  },
  computed: {

  },

  methods: {
    async getSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/perx_sitev2",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },

          }
        );

        if (response.status === 200) {
          this.sites = response.data.sites;
          console.log(response.data.sites);
        } else {
          console.log("Error fetching sites");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getLob(filterSite = "") {
  try {
    const token = this.$store.state.token;
    const response = await axios.get("http://127.0.0.1:8000/api/lobv2", {
      headers: {
        Authorization: `Bearer ${token}`,
      },
      params: {
        filter_site: filterSite === "ALL" ? "" : filterSite,
      },
    });

    if (response.status === 200) {
      this.sites = [{ id: "ALL", name: "All Sites" }, ...response.data.sites];
      console.log(this.sites);
    } else {
      console.log("Error fetching sites");
    }
  } catch (error) {
    console.log(error);
  }
},


    async fetchData() {
      this.filterLoading = true;
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/classes_information",
          {
            params: {
              filter_lob: this.filterLob,
              filter_site: this.filterSite,
              filter_contact: this.filterWave,

            },
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        this.classes = response.data.classes;
      } catch (error) {
        console.error("Error fetching filtered data", error);
      } finally {
        this.filterLoading = false;
      }
    },

    async exportToExcel() {
      this.exportLoading = true; // Set export loading to true before making the request
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/exportv2", {
          params: {
            filter_lastname: this.filterLastName,
            filter_firstname: this.filterFirstName,
            filter_site: this.filterSite,
            filter_date_start: this.filterStartDate,
            filter_date_end: this.filterEndDate,
            filter_contact: this.filterWave,
            filter_region: this.filterRegion === "ALL" ? "" : this.filterRegion,
          },
          headers: {
            Authorization: `Bearer ${token}`,
          },
          responseType: "blob",
        });

        const blob = new Blob([response.data], {
          type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        });
        const url = window.URL.createObjectURL(blob);

        const link = document.createElement("a");
        link.href = url;
        link.download = "perx_data.xlsx";
        link.click();
      } catch (error) {
        console.error("Error exporting filtered data to Excel", error);
      } finally {
        this.exportLoading = false; // Set export loading to false when the request completes
      }
    },
  },
};
</script>
