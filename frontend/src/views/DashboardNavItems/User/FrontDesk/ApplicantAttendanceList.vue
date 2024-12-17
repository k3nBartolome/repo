<template>
  <div class="py-0">
    <header class="p-4 py-0 bg-white">
      <div class="max-w-screen-xl mx-auto">
        <h3 class="text-2xl font-bold text-gray-900">Applicant Attendance List</h3>
      </div>
    </header>
    <div class="p-6 py-8 bg-gray-100">
      <div class="mb-4 md:flex md:space-x-2 md:items-center py-6">
        <div class="w-full md:w-1/4 mb-4 md:mb-0">
          <button
            @click="exportToExcel"
            class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg"
          >
            Export
          </button>
        </div>
        <div>
         </div>
         </div>
      <div  v-if="filterLoading || exportLoading"
        class="font-bold text-center text-blue-500"
      >
        {{ filterLoading ? "Rendering..." : "Exporting..." }}
      </div>
      <div class="overflow-x-auto">
        <div class="min-w-full">
      <DataTable
        :data="applicant"
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
          </tr>
        </thead>
        <tbody class="truncate">
          <tr v-for="item in applicant" :key="item.id">
            <!-- Add your table row data here -->
          </tr>
        </tbody>
      </DataTable>
  </div>
  </div>
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
      applicant: [],
      columns: [
        { data: "site", title: "Site" },
        { data: "last_name", title: "Last Name" },
        { data: "first_name", title: "First Name" },
        { data: "middle_name", title: "Middle Name" },
        { data: "email", title: "Email Address" },
        { data: "contact_number", title: "Mobile Number" },
        { data: "created_at", title: "In" },
      ],
      filterLoading: false,
      exportLoading: false,
    };
  },
  mounted() {
  this.fetchData()
  },
  methods: {
    async fetchData() {
      this.filterLoading = true;
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
        `https://10.109.2.112/api/applicants_data/${this.$store.state.site_id}`,
          {
           
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        this.applicant = response.data.applicant;
      } catch (error) {
        console.error("Error fetching filtered data", error);
      } finally {
        this.filterLoading = false;
      }
    },
    async exportToExcel() {
  this.exportLoading = true;
  try {
    const token = this.$store.state.token;
    const response = await axios.get(
      `https://10.109.2.112/api/applicants_export_data/${this.$store.state.site_id}`,
      {
        headers: { Authorization: `Bearer ${token}` },
        responseType: "blob",
      }
    );
    const blob = new Blob([response.data], {
      type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.href = url;
    link.download = "applicantAttendance.xlsx";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } catch (error) {
    console.error("Error exporting to Excel:", error);
  } finally {
    this.exportLoading = false;
  }
}
  },
};
</script>
