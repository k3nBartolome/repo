<template>
  <div class="py-0">
    <header class="p-4 py-0 bg-white">
      <div class="max-w-screen-xl mx-auto">
        <h3 class="text-2xl font-bold text-gray-900">Applicant Attendance List</h3>
      </div>
    </header>
    <div class="p-6 py-8 bg-gray-100">
      <!-- Filters Section -->
      <div class="mb-4 md:flex md:space-x-2 md:items-center py-6">
        <!-- Site Filter -->
        <div class="w-full md:w-1/4 mb-4 md:mb-0">
          <label for="site" class="block text-sm font-medium">Site</label>
          <select
            v-model="site"
            class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal text-black"
          >
            <option disabled value="">Please select Site</option>
            <option v-for="site in sites" :key="site.id" :value="site.id">
              {{ site.name }}
            </option>
          </select>
        </div>

        <!-- Min Date Filter -->
        <div class="w-full md:w-1/4 mb-4 md:mb-0">
          <label for="minDate" class="block text-sm font-medium">Min Date</label>
          <input
            type="date"
            v-model="minDate"
            class="block w-full border border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal text-black"
          />
        </div>

        <!-- Max Date Filter -->
        <div class="w-full md:w-1/4 mb-4 md:mb-0">
          <label for="maxDate" class="block text-sm font-medium">Max Date</label>
          <input
            type="date"
            v-model="maxDate"
            class="block w-full border border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal text-black"
          />
        </div>

        <!-- Apply Filter Button -->
        <div class="w-full md:w-1/4 mb-4 md:mb-0">
          <button
            @click="applyFilter"
            class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg"
          >
            Apply Filter
          </button>
        </div>
      </div>

      <!-- Loading Indicator -->
      <div
        v-if="filterLoading || exportLoading"
        class="font-bold text-center text-blue-500"
      >
        {{ filterLoading ? "Rendering..." : "Exporting..." }}
      </div>

      <!-- Export Button -->
      <div class="mb-4">
        <button
          @click="exportToExcel"
          class="px-4 py-2 text-white bg-green-500 rounded-lg"
        >
          Export
        </button>
      </div>

      <!-- DataTable -->
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
          />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import DataTable from "datatables.net-vue3";
import DataTableLib from "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";

DataTable.use(DataTableLib);

export default {
  components: { DataTable },
  data() {
    return {
      applicant: [],
      sites: [],
       site: null,  // Assuming you are storing the site value
    minDate: null,  // Will hold the min date from backend
    maxDate: null, 
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
    this.fetchData();
    this.getSites();
     this.fetchDateRange();
  },
  methods: {
     async fetchDateRange() {
    try {
      const token = this.$store.state.token;  // Assuming the token is in Vuex
      const response = await axios.get("https://10.109.2.112/api/getCreatedAtRange", {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      // Set the min and max date to the state variables
      if (response.data.min_date && response.data.max_date) {
        this.minDate = response.data.min_date;
        this.maxDate = response.data.max_date;
      }
    } catch (error) {
      console.error("Error fetching date range:", error);
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
        this.sites = response.data.data || [];
      } catch (error) {
        console.error("Error fetching sites:", error);
      }
    },
    async fetchData() {
      this.filterLoading = true;
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "https://10.109.2.112/api/applicants_data",
          {
            params: {
              site_id: this.site || null,
              min_date: this.minDate || null,
              max_date: this.maxDate || null,
            },
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        this.applicant = response.data.applicant || [];
      } catch (error) {
        console.error("Error fetching filtered data:", error);
      } finally {
        this.filterLoading = false;
      }
    },
    applyFilter() {
      this.fetchData();
    },
    async exportToExcel() {
      this.exportLoading = true;
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "https://10.109.2.112/api/applicants_export_data",
          {
            params: {
              site_id: this.site || null,
              min_date: this.minDate || null,
              max_date: this.maxDate || null,
            },
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
    },
  },
};
</script>
