<template>
  <div>
    <header class="bg-white p-4">
      <div class="max-w-screen-xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900">PERX Audit Tool</h2>
      </div>
    </header>
    <div class="bg-gray-100 p-4">
      <div class="mb-4 md:flex md:space-x-2 md:items-center">
        <div class="w-full md:w-1/4 relative">
          <input
            v-model="filterLastName"
            placeholder="Filter by Last Name"
            class="p-2 border rounded-lg w-full"
            @input="validateLastName"
          />
          <div
            v-if="filterLastNameError"
            class="absolute top-full left-0 text-red-500 text-sm"
          >
            {{ filterLastNameError }}
          </div>
        </div>
        <div class="w-full md:w-1/4">
          <input
            v-model="filterFirstName"
            placeholder="Filter by First Name"
            class="p-2 border rounded-lg w-full"
          />
        </div>
        <div class="w-full md:w-1/4">
          <input
            v-model="filterSite"
            placeholder="Filter by Site"
            class="p-2 border rounded-lg w-full"
          />
        </div>
        <div class="w-full md:w-1/4">
          <input
            v-model="filterStartDate"
            type="date"
            placeholder="Start"
            class="p-2 border rounded-lg w-full"
            @input="updateFilterStartDate"
          />
        </div>
        <div class="w-full md:w-1/4">
          <input
            v-model="filterEndDate"
            type="date"
            placeholder="End by Site"
            class="p-2 border rounded-lg w-full"
            @input="updateFilterEndDate"
          />
        </div>
        <div class="w-full md:w-1/4 relative">
          <input
            v-model="filterContact"
            placeholder="Filter by Mobile No."
            class="p-2 border rounded-lg w-full"
            @input="validateContact"
          />
          <div
            v-if="filterContactError"
            class="absolute top-full left-0 text-red-500 text-sm"
          >
            {{ filterContactError }}
          </div>
        </div>
        <div class="w-full md:w-1/4">
          <button
            @click="fetchData"
            class="px-4 py-2 bg-blue-500 text-white rounded-lg w-full"
          >
            Filter
          </button>
        </div>
        <div class="w-full md:w-1/4">
          <button
            @click="exportToExcel"
            class="px-4 py-2 bg-blue-500 text-white rounded-lg w-full"
          >
            Export
          </button>
        </div>
      </div>
      <div
        v-if="filterLoading || exportLoading"
        class="text-center text-blue-500 font-bold"
      >
        {{ filterLoading ? "Rendering..." : "Exporting..." }}
      </div>
      <DataTable
        :data="perx"
        :columns="columns"
        class="table divide-y divide-gray-200 table-auto table-striped"
        :options="{
          responsive: true,
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
/* eslint-disable */
import axios from "axios";
import DataTable from "datatables.net-vue3";
import DataTableLib from "datatables.net-bs5";
import Buttons from "datatables.net-buttons-bs5";
import ButtonsHtml5 from "datatables.net-buttons/js/buttons.html5";
import print from "datatables.net-buttons/js/buttons.print";
import pdfFonts from "pdfmake/build/vfs_fonts";
import "datatables.net-responsive-bs5";
import jszip from "jszip";
import "bootstrap/dist/css/bootstrap.css";
import { format } from "date-fns";
DataTable.use(DataTableLib);
DataTable.use(jszip);
DataTable.use(ButtonsHtml5);

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
      filterLastNameError: "",
      filterContactError: "",
      perx: [],
      columns: [
        { data: "ApplicantId", title: "ApplicantId" },
        { data: "ApplicationInfoId", title: "SR ID" },
        { data: "DateOfApplication", title: "DateOfApplication" },
        { data: "LastName", title: "LastName" },
        { data: "FirstName", title: "FirstName" },
        { data: "MiddleName", title: "MiddleName" },
        { data: "MobileNo", title: "MobileNo" },
        { data: "Site", title: "Site" },
        { data: "GenSource", title: "GenSource" },
        { data: "SpecSource", title: "SpecSource" },
        { data: "Step", title: "Step" },
        { data: "AppStep", title: "AppStep" },
        { data: "PERX_HRID", title: "PERX_HRID" },
        { data: "PERX_Last_Day", title: "PERX_Last_Day" },
        { data: "PERX_Retract", title: "PERX_Retract" },
        { data: "PERX_NAME", title: "PERX_NAME" },
        { data: "OSS_HRID", title: "OSS_HRID" },
        { data: "OSS_Last_Day", title: "OSS_Last_Day" },
        { data: "OSS_Retract", title: "OSS_Retract" },
        { data: "OSS_FNAME", title: "OSS_FNAME" },
        { data: "OSS_LNAME", title: "OSS_LNAME" },
        { data: "OSS_LOB", title: "OSS_LOB" },
        { data: "OSS_SITE", title: "OSS_SITE" },
      ],
      filterLoading: false,
      exportLoading: false,
    };
  },
  computed: {
    formattedFilterDate() {
      return this.filterDate
        ? new Date(this.filterDate).toLocaleDateString("en-CA")
        : "";
    },
  },

  methods: {
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

    async fetchData() {
      this.filterLoading = true;
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://10.109.2.112:8081/api/perxfilter",
          {
            params: {
              filter_lastname: this.filterLastName,
              filter_firstname: this.filterFirstName,
              filter_site: this.filterSite,
              filter_date_start: this.filterStartDate,
              filter_date_end: this.filterEndDate,
              filter_contact: this.filterContact,
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
        const response = await axios.get(
          "http://10.109.2.112:8081/api/export",
          {
            params: {
              filter_lastname: this.filterLastName,
              filter_firstname: this.filterFirstName,
              filter_site: this.filterSite,
              filter_date: this.filterDate,
              filter_contact: this.filterContact,
            },
            headers: {
              Authorization: `Bearer ${token}`,
            },
            responseType: "blob",
          }
        );

        const blob = new Blob([response.data], {
          type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        });
        const url = window.URL.createObjectURL(blob);

        const link = document.createElement("a");
        link.href = url;
        link.download = "filtered_perx_data.xlsx";
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
