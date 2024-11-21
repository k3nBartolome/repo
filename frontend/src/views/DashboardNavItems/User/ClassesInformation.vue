<template>
  <div class="py-0">
    <header class="p-4 py-0 bg-white">
      <div class="max-w-screen-xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900">Class Salary Package</h2>
      </div>
    </header>
    <div class="p-4 bg-gray-100">
      <div class="mb-4 md:flex md:space-x-2 md:items-center">
        <div class="w-full md:w-1/4">
          <select
            v-model="filterSite"
            @change="getLob(filterSite)"
            placeholder="Filter by Site"
            class="w-full p-2 border rounded-lg"
          >
            <option disabled value="" selected>Please select one</option>
            <option v-for="site in sites" :key="site.Id" :value="site.Id">
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
            <option v-for="lob in lobs" :key="lob.Id" :value="lob.Id">
              {{ lob.Name }}
            </option>
          </select>
        </div>
        <div class="relative w-full md:w-1/4">
          <input
            type="number"
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
        :data="classes"
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
          <tr v-for="item in classes" :key="item.id">
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
        {
          data: "No",
          title: "No",
        },
        {
          data: "HRID",
          title: "HRID",
          render: function (data) {
            return data ? data : "TBA";
          },
        },
        { data: "LastName", title: " NAME" },
        { data: "FirstName", title: "FIRST NAME" },
        { data: "MiddleName", title: "MIDDLE NAME" },
        {
          data: "DateHired",
          title: "DATE HIRED",
          render: function (data) {
            return data ? data : ""; // If data is null or undefined, return 'TBA'
          },
        },
        { data: "Lob", title: "DEPT/LOB/ACCOUNT" },
        { data: "Wave", title: "TEAM/WAVE" },
        { data: "Position", title: "POSITION" },
        { data: "Sites", title: "BLDG ASSIGNMENT" },
        { data: "Salary", title: "MONTHLY SALARY" },
        {
          data: "ND_Training",
          title: "ND%-TRAINING",
          render: function (data) {
            return `${data}%`;
          },
        },
        {
          data: "ND_Production",
          title: "ND%-PRODUCTION",
          render: function (data) {
            return `${data}%`;
          },
        },
        {
          data: "MS_Production",
          title: "MID-SHIFT%-PRODUCTION",
          render: function (data) {
            return `${data}%`;
          },
        },
        {
          data: "ComplexityAllowance",
          title: "COMPLEXITY ALLOWANCE(UPON START)",
        },
      ],

      filterLoading: false,
      exportLoading: false,
    };
  },
  mounted() {
    this.getSites();
    this.getLob();
  },
  computed: {},
  methods: {
    async getSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("https://10.236.102.139/api/sitev2", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.sites = response.data.sites;
          console.log("Sites:", response.data.sites);
        } else {
          console.log("Error fetching sites");
        }
      } catch (error) {
        console.log("Error:", error);
      }
    },

    async getLob(filterSite = "") {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("https://10.236.102.139/api/lobv2", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
          params: {
            site_id: filterSite === "ALL" ? "" : filterSite,
          },
        });

        if (response.status === 200) {
          this.lobs = response.data.lobs;
          console.log("LOBs:", this.lobs);
        } else {
          console.log("Error fetching LOBs");
        }
      } catch (error) {
        console.log("Error:", error);
      }
    },

    async fetchData() {
      this.filterLoading = true;
      try {
        const token = this.$store.state.token;
        console.log("Request params:", {
          filter_site: this.filterSite,
          filter_lob: this.filterLob,
          filter_wave: this.filterWave,
        });
        const response = await axios.get(
          "https://10.236.102.139/api/classes_information",
          {
            params: {
              filter_site: this.filterSite,
              filter_lob: this.filterLob,
              filter_wave: this.filterWave,
            },
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.classes = response.data.classes;
          console.log("Filtered classes:", this.classes);
        } else {
          console.log("Error fetching filtered data");
        }
      } catch (error) {
        console.error("Error fetching filtered data:", error);
      } finally {
        this.filterLoading = false;
      }
    },

    async exportToExcel() {
      this.exportLoading = true;
      try {
        const token = this.$store.state.token;

        // Extract unique LOB and WAVE values from the classes data
        const lobSet = new Set(
          this.classes.map((item) => item.Lob).filter(Boolean)
        );
        const waveSet = new Set(
          this.classes.map((item) => item.Wave).filter(Boolean)
        );

        let fileName = "SALARY PACKAGE";

        // Set filename based on the number of unique LOB and WAVE values
        if (lobSet.size === 1 && waveSet.size === 1) {
          fileName += ` -${[...lobSet][0]} ${[...waveSet][0]}`;
        } else if (lobSet.size > 1 || waveSet.size > 1) {
          if (lobSet.size > 1) {
            fileName += ``;
          }
          if (waveSet.size > 1) {
            fileName += ``;
          }
        }

        const response = await axios.get(
          "https://10.236.102.139/api/classes_information_export",
          {
            params: {
              filter_site: this.filterSite,
              filter_lob: this.filterLob,
              filter_wave: this.filterWave,
            },
            headers: {
              Authorization: `Bearer ${token}`,
            },
            responseType: "blob", // Ensures the response is treated as a binary Blob
          }
        );

        // Create a Blob from the response data
        const blob = new Blob([response.data], {
          type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        });

        // Create a link element to trigger the download
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.href = url;
        link.download = `${fileName}.xlsx`; // Name of the downloaded file
        link.click();

        // Clean up the URL object
        window.URL.revokeObjectURL(url);
      } catch (error) {
        console.error("Error exporting filtered data to Excel:", error);
        // Optionally show an error message to the user here
      } finally {
        this.exportLoading = false; // Set export loading to false when the request completes
      }
    },
  },
};
</script>
