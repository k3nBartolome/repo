<template>
  <div class="py-0">
    <div
      class="container px-4 py-0 mx-auto mt-4 flex flex-wrap space-x-2 items-center"
    >
      <div class="flex-grow">
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
      <div class="flex-grow">
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
      <div class="flex-grow">
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
      <div class="flex-grow">
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
      <div>
        <button
          type="button"
          class="px-4 py-2 text-white bg-red-500 rounded-lg"
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
            :data="filteredData"
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
        { data: "site.country", title: "Country" },
        { data: "site.region", title: "Region" },
        { data: "site.name", title: "Site" },
        { data: "program.name", title: "Program" },
        { data: "date_range.date_range", title: "Hiring Week" },
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
  computed: {
    filteredData() {
      let filteredData = [...this.classes];
      if (this.sites_selected) {
        filteredData = filteredData.filter((classes) => {
          return classes.site.id === this.sites_selected;
        });
      }

      if (this.programs_selected) {
        filteredData = filteredData.filter((classes) => {
          return classes.program.id === this.programs_selected;
        });
      }
      if (this.month_selected) {
        filteredData = filteredData.filter((classes) => {
          return classes.date_range.month_num === parseInt(this.month_selected);
        });
      }
      if (this.week_selected) {
        filteredData = filteredData.filter((classes) => {
          const weekId = classes.date_range.id;
          return weekId === this.week_selected;
        });
      }

      return filteredData;
    },
    EmptySelection() {
      return (
        !this.sites_selected || !this.programs_selected || !this.week_selected
      );
    },
    classExists() {
      return this.classes.some((c) => {
        return (
          c.site.id === this.sites_selected &&
          c.program.id === this.programs_selected &&
          c.date_range.id === this.week_selected
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
      this.status = "";
    },
    async getClassesAll() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/classesdashboard2",
          {
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
    async getSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/sites", {
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
          `http://127.0.0.1:8000/api/programs_selected/${this.sites_selected}`,
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
          `http://127.0.0.1:8000/api/daterange_selected/${this.month_selected}`,
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
