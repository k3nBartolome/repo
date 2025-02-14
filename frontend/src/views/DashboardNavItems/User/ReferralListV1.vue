<template>
  <div class="py-0">
    <header class="p-4 py-0 bg-white">
      <div class="max-w-screen-xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900">OSS Referrals</h2>
      </div>
    </header>
    <div class="p-4 bg-gray-100">
      <div class="mb-4 md:flex md:space-x-2 md:items-center">
        <div class="w-full md:w-1/4">
          <select
            v-model="filterReferredBySite"
            placeholder="Filter by Site"
            class="w-full p-2 border rounded-lg"
          >
            <option disabled value="" selected>Please select ReferredBySite</option>
            <option v-for="site in ref_sites" :key="site.ReferredBySite" :value="site.ReferredBySite">
              {{ site.ReferredBySite }}
            </option>
          </select>
        </div>
        <div class="w-full md:w-1/4">
          <select
            v-model="filterPreferredSite"
            placeholder="Filter by Site"
            class="w-full p-2 border rounded-lg"
          >
            <option disabled value="" selected>Please select PreferredSi</option>
            <option v-for="site in pref_sites" :key="site.PreferredSite" :value="site.PreferredSite">
              {{ site.PreferredSite }}
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
        :data="ref_data"
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
          <tr v-for="item in ref_data" :key="item.id">
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
      filterStartDate: "",
      filterEndDate: "",
      filterReferredBySite: "",
      filterPreferredSite: "",
      ref_data: [],
      pref_sites: [],
      ref_sites: [],
      sites: [],
      columns: [
        { data: "ReferredByHrid", title: "ReferredByHrid" },
        { data: "ReferredByFirstName", title: "ReferredByFirstName" },
        { data: "ReferredByLastName", title: "ReferredByLastName" },
        { data: "ReferredByEmailAddress", title: "ReferredByEmailAddress" },
        { data: "ReferredByLob", title: "ReferredByLob" },
        { data: "ReferredBySite", title: "ReferredBySite" },
        { data: "ReferralFirstName", title: "ReferralFirstName" },
        { data: "ReferralLastName", title: "ReferralLastName" },
        { data: "ReferralIsWithEperience", title: "ReferralIsWithExperience" },
        { data: "ReferralLOB", title: "ReferralContact" },
        { data: "ReferredDate", title: "ReferredDate" },
        { data: "Position", title: "Position" },
        { data: "PreferredSite", title: "PreferredSite" },
      ],
      filterLoading: false,
      exportLoading: false,
    };
  },
  mounted() {
    this.getDates();
    this.getRefSites();
    this.getPrepSites();
  },
  computed: {
    formattedfilterReferredDate() {
      return this.filterReferredDate
        ? new Date(this.filterReferredDate).toLocaleDateString("en-CA")
        : "";
    },
  },

  methods: {
    async getDates() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/ref_date",
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
    async getRefSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/ref_site",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.ref_sites = response.data.sites;
          console.log(response.data.data);
        } else {
          console.log("Error fetching sites");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getPrepSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/pref_site",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.pref_sites = response.data.sites;
          console.log(response.data.data);
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
          "http://127.0.0.1:8000/api/ref_v1",
          {
            params: {
              filter_ref_site: this.filterReferredBySite,
              filter_pref_site: this.filterPreferredSite,
              filter_date_start: this.filterStartDate,
              filter_date_end: this.filterEndDate,

            },
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        this.ref_data = response.data.ref_data;
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
        const response = await axios.get("http://127.0.0.1:8000/api/ref_v1_export", {
          params: {
            filter_ref_site: this.filterReferredBySite,
              filter_pref_site: this.filterPreferredSite,
              filter_date_start: this.filterStartDate,
              filter_date_end: this.filterEndDate,
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
        link.download = "referrals_v1.xlsx";
        link.click();
      } catch (error) {
        console.error("Error exporting filtered data to Excel", error);
      } finally {
        this.exportLoading = false;
      }
    },
  },
};
</script>
