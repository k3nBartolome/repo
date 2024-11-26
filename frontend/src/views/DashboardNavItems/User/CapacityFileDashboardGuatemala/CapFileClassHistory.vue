<template>
  <div class="py-0">
    <div class="mb-4 md:flex md:space-x-2 md:items-center">
      <div class="relative w-full md:w-1/4">
        <select
          v-model="sites_selected"
          class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
          @change="getPrograms"
        >
          <option disabled value="" selected>Please select Site</option>
          <option v-for="site in sites" :key="site.id" :value="site.id">
            {{ site.name }}
          </option>
        </select>
      </div>
      <div class="w-full md:w-1/4">
        <select
          v-model="programs_selected"
          class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
        >
          <option disabled value="" selected>Please select Program</option>
          <option
            v-for="program in programs"
            :key="program.id"
            :value="program.id"
          >
            {{ program.name }}
          </option>
        </select>
      </div>
      <div class="w-full md:w-1/4">
        <select
          v-model="month_selected"
          class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
          @change="onMonthSelected"
        >
          <option disabled value="" selected>Please select Month</option>
          <option value="1">January</option>
          <option value="2">February</option>
          <option value="3">March</option>
          <option value="4">April</option>
          <option value="5">May</option>
          <option value="6">June</option>
          <option value="7">July</option>
          <option value="8">August</option>
          <option value="9">September</option>
          <option value="10">October</option>
          <option value="11">November</option>
          <option value="12">December</option>
        </select>
      </div>
      <div class="w-full md:w-1/4">
        <select
          v-model="week_selected"
          class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
        >
          <option disabled value="" selected>Please select Week</option>
          <option
            v-for="daterange in daterange"
            :key="daterange.id"
            :value="daterange.id"
          >
            {{ daterange.date_range }}
          </option>
        </select>
      </div>
      <div class="w-full md:w-1/4">
        <button
          type="button"
          class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg"
          @click="getClassesAll"
        >
          Filter
        </button>
      </div>
      <div class="w-full md:w-1/4">
        <button
          type="button"
          class="w-full px-4 py-2 text-white bg-green-600 rounded-lg"
          @click="exportToExcel"
        >
          Export
        </button>
      </div>
      <div class="w-full md:w-1/4">
        <button
          type="button"
          class="w-full px-4 py-2 text-white bg-red-500 rounded-lg"
          @click="resetFilter"
        >
          Reset Filters
        </button>
      </div>
    </div>
  </div>

  <div class="py-1">
    <div class="pl-8 pr-8">
      <div class="scroll">
        <div class="w-2/3 mx-auto datatable-container">
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
              dom: 'frtip',
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
                <!-- ...existing code... -->
              </tr>
            </thead>
          </DataTable>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
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
      classes: [],
      grandTotal: [],
      grandTotal2: [],
      sites: [],
      programs: [],
      daterange: [],
      sites_selected: "",
      programs_selected: "",
      month_selected: "",
      week_selected: "",
      columns: [
        { data: "id", title: "ID" },
        { data: "country", title: "Country" },
        { data: "region", title: "Region" },
        { data: "site_name", title: "Site" },
        { data: "program_name", title: "Program" },
        { data: "date_range", title: "Hiring Week" },
        { data: "external_target", title: "External Target" },
        { data: "internal_target", title: "Internal Target" },
        { data: "total_target", title: "Total Target" },
        { data: "within_sla", title: "Within SLA" },
        { data: "condition", title: "Condition" },
        { data: "original_start_date", title: "Original Start Date" },
        { data: "changes", title: "Changes" },
        { data: "agreed_start_date", title: "Agreed Start Date" },
        { data: "wfm_date_requested", title: "WFM Date Requested" },
        { data: "notice_weeks", title: "Notice Weeks" },
        { data: "notice_days", title: "Notice Days" },
        { data: "pipeline_utilized", title: "Pipeline Utilized" },
        { data: "remarks", title: "Remarks" },
        { data: "status", title: "Status" },
        { data: "category", title: "Category" },
        { data: "type_of_hiring", title: "Type of Hiring" },
        { data: "with_erf", title: "With ERF" },
        { data: "erf_number", title: "ERF Number" },
        { data: "wave_no", title: "Wave No" },
        { data: "ta", title: "TA" },
        { data: "wf", title: "WF" },
        { data: "tr", title: "TR" },
        { data: "cl", title: "CL" },
        { data: "op", title: "OP" },
        { data: "approved_by", title: "Approved By" },
        { data: "cancelled_by", title: "Cancelled By" },
        { data: "requested_by", title: "Requested By" },
        { data: "created_by", title: "Created By" },
        { data: "updated_by", title: "Updated By" },
        { data: "approved_date", title: "Approved Date" },
        { data: "cancelled_date", title: "Cancelled Date" },
        { data: "created_at", title: "Created Date" },
        { data: "updated_at", title: "Updated Date" },
      ],
    };
  },
  watch: {
    sites_selected: "getClassesAll",
    programs_selected: "getClassesAll",
    month_selected: "getClassesAll",
    week_selected: "getClassesAll",
  },

  computed: {
    EmptySelection() {
      return (
        !this.sites_selected || !this.programs_selected || !this.week_selected
      );
    },
    classExists() {
      return this.classes.some((c) => {
        return (
          c.site_id === this.sites_selected &&
          c.program_id === this.programs_selected &&
          c.date_range_id === this.week_selected
        );
      });
    },
  },
  mounted() {
    this.getClassesAll();
    this.getSites();
    this.getPrograms();
    this.getDateRange();
  },
  methods: {
    onMonthSelected() {
      if (this.month_selected) {
        this.getDateRange();
      }
    },
    resetFilter() {
      this.sites_selected = "";
      this.programs_selected = "";
      this.month_selected = "";
      this.week_selected = "";
    },
    async getClassesAll() {
      console.log("Sites Selected:", this.sites_selected);
      console.log("Programs Selected:", this.programs_selected);
      console.log("Month Selected:", this.month_selected);
      console.log("Week Selected:", this.week_selected);
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "https://10.236.103.190/api/classesdashboardguatemala2",
          {
            params: {
              sites_selected: this.sites_selected,
              programs_selected: this.programs_selected,
              month_selected: this.month_selected,
              week_selected: this.week_selected,
            },
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.classes = response.data.classes;
        } else {
          console.error(
            "Error fetching classes. Status code:",
            response.status
          );
        }
      } catch (error) {
        console.error("An error occurred:", error);
      }
    },

    async exportToExcel() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "https://10.236.103.190/api/history_export",
          {
            params: {
              sites_selected: this.sites_selected,
              programs_selected: this.programs_selected,
              month_selected: this.month_selected,
              week_selected: this.week_selected,
            },
            headers: {
              Authorization: `Bearer ${token}`,
            },
            responseType: "blob",
          }
        );

        // Check if the response contains data
        if (response && response.data) {
          // Create a Blob from the response data
          const blob = new Blob([response.data], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          });

          // Create a URL for the Blob
          const url = window.URL.createObjectURL(blob);

          // Create a link element and trigger a download
          const link = document.createElement("a");
          link.href = url;
          link.download = "filtered_class_history_data.xlsx";
          document.body.appendChild(link); // Append the link to the body
          link.click(); // Simulate a click on the link
          document.body.removeChild(link); // Remove the link from the body after download
          window.URL.revokeObjectURL(url); // Revoke the URL to free up memory
        } else {
          console.error("Empty response received when exporting data to Excel");
        }
      } catch (error) {
        console.error("Error exporting filtered data to Excel", error);
      }
    },

    async getSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("https://10.236.103.190/api/sites7", {
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
    async getPrograms() {
      if (!this.sites_selected) {
        return;
      }

      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          `https://10.236.103.190/api/programs_selected/${this.sites_selected}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.programs = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching programs");
        }
      } catch (error) {
        console.error(error);
      }
    },

    async getDateRange() {
      if (!this.month_selected) {
        return;
      }

      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          `https://10.236.103.190/api/daterange_selected/${this.month_selected}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.daterange = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching date range");
        }
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>
