<template>
  <div class="py-0">
    <header class="p-4 py-0 bg-white">
      <div class="max-w-screen-xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900">PERX Audit Tool SRv2</h2>
      </div>
    </header>
    <div class="p-4 bg-gray-100">
      <div class="mb-4 md:flex md:space-x-2 md:items-center">
        <div class="relative w-full md:w-1/4">
          <input
            v-model="filterLastName"
            placeholder="Filter by Last Name"
            class="w-full p-2 border rounded-lg"
            @input="validateLastName"
          />
          <div
            v-if="filterLastNameError"
            class="absolute left-0 text-sm text-red-500 top-full"
          >
            {{ filterLastNameError }}
          </div>
        </div>
        <div class="w-full md:w-1/4">
          <input
            v-model="filterFirstName"
            placeholder="Filter by First Name"
            class="w-full p-2 border rounded-lg"
          />
        </div>
        <div class="w-full md:w-1/4">
          <select
            v-model="filterRegion"
            placeholder="Filter by Region"
            class="w-full p-2 border rounded-lg"
            @change="getSites(filterRegion)"
          >
            <option disabled value="" selected>Please select one</option>
            <option value="ALL">All</option>
            <option value="CLARK">CLARK</option>
            <option value="DAVAO">DAVAO</option>
            <option value="L2">L2</option>
            <option value="QC">QC</option>
          </select>
        </div>
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
          <input
            v-model="filterStartDate"
            type="date"
            placeholder="Start"
            class="w-full p-2 border rounded-lg"
            @input="updateFilterStartDate"
          />
        </div>
        <div class="w-full md:w-1/4">
          <input
            v-model="filterEndDate"
            type="date"
            placeholder="End by Site"
            class="w-full p-2 border rounded-lg"
            @input="updateFilterEndDate"
          />
        </div>
        <div class="relative w-full md:w-1/4">
          <input
            v-model="filterContact"
            placeholder="Filter by Mobile No."
            class="w-full p-2 border rounded-lg"
            @input="validateContact"
          />
          <div
            v-if="filterContactError"
            class="absolute left-0 text-sm text-red-500 top-full"
          >
            {{ filterContactError }}
          </div>
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
      filterLastName: "",
      filterFirstName: "",
      filterSite: "",
      filterStartDate: "",
      filterEndDate: "",
      filterContact: "",
      filterRegion: "",
      filterLastNameError: "",
      filterContactError: "",
      perx: [],
      sites: [],
      columns: [
        { data: "ApplicantId", title: "ApplicantId" },
        { data: "DateOfApplication", title: "DateOfApplication" },
        { data: "LastName", title: "LastName" },
        { data: "FirstName", title: "FirstName" },
        { data: "MiddleName", title: "MiddleName" },
        { data: "MobileNumber", title: "MobileNo" },
        { data: "Region", title: "Region" },
        { data: "Site", title: "Site" },
        { data: "GeneralSource", title: "GenSource" },
        { data: "SpecSource", title: "SpecSource" },
        { data: "Step", title: "Step" },
        { data: "AppStep1", title: "GenStatus" },
        { data: "AppStep2", title: "SpecStatus" },
        { data: "ReferrerHRID", title: " ReferrerHRID" },
        { data: "ReferrerFirstName", title: " ReferrerFirstName" },
        { data: "ReferrerMiddleName", title: "ReferrerMiddleName" },
        { data: "ReferrerLastName", title: "ReferrerLastName" },
        { data: "ReferrerName", title: "ReferrerName" },
        { data: "DeclaredReferrerName", title: "DeclaredReferrerName" },
        { data: "DeclaredReferrerId", title: "DeclaredReferrerId" }
      ],
      filterLoading: false,
      exportLoading: false,
    };
  },
  mounted() {
    this.getDates();
    this.getSites();
  },
  computed: {
    formattedFilterDate() {
      return this.filterDate
        ? new Date(this.filterDate).toLocaleDateString("en-CA")
        : "";
    },
  },

  methods: {
    async getDates() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://10.109.2.112:8081/api/perx_datev2",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.filterStartDate = response.data.minDate;
          this.filterEndDate = response.data.maxDate;
          console.log(response.data.minDate);
          console.log(response.data.maxDate);
        } else {
          console.log("Error fetching Date");
        }
      } catch (error) {
        console.log(error);
      }
    },
    updateFilterStartDate(event) {
      this.filterStartDate = event.target.value;
    },

    updateFilterEndDate(event) {
      this.filterEndDate = event.target.value;
    },

    formatDateForInput(date) {
      const formattedDate = new Date(date).toISOString().split("T")[0];
      return formattedDate;
    },

    validateLastName() {
      this.filterLastNameError = "";
      if (this.filterLastName && this.filterLastName.length < 2) {
        this.filterLastNameError =
          "Last Name must be at least 2 characters long.";
      }
    },
    validateContact() {
      this.filterContactError = "";
      if (this.filterContact && this.filterContact.length < 4) {
        this.filterContactError =
          "Mobile No must be at least 4 characters long.";
      }
    },
    async getSites(filterRegion = '') {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://10.109.2.112:8081/api/perx_sitev2",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
            params: {
              filter_region: filterRegion === 'ALL' ? '' : filterRegion
            }
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

    async fetchData() {
      this.filterLoading = true;
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://10.109.2.112:8081/api/perxfilterv2",
          {
            params: {
              filter_lastname: this.filterLastName,
              filter_firstname: this.filterFirstName,
              filter_site: this.filterSite,
              filter_date_start: this.filterStartDate,
              filter_date_end: this.filterEndDate,
              filter_contact: this.filterContact,
              filter_region: this.filterRegion === 'ALL' ? '' : this.filterRegion,
            },
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        this.perx = response.data.perx;
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
        const response = await axios.get("http://10.109.2.112:8081/api/exportv2", {
          params: {
            filter_lastname: this.filterLastName,
            filter_firstname: this.filterFirstName,
            filter_site: this.filterSite,
            filter_date_start: this.filterStartDate,
            filter_date_end: this.filterEndDate,
            filter_contact: this.filterContact,
            filter_region: this.filterRegion === 'ALL' ? '' : this.filterRegion,
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
