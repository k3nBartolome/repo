<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h1 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Staffing Tracker
      </h1>
    </div>
  </header>
  <div class="py-8 bg-white">
    <div
      class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
    >
      <form class="">
        <div class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5">
          <label class="block">
            Site
            <select
              v-model="sites_selected"
              class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              @change="getPrograms"
            >
              <option disabled value="" selected>Please select one</option>
              <option v-for="site in sites" :key="site.id" :value="site.id">
                {{ site.name }}
              </option>
            </select>
          </label>
          <label class="block">
            Programs
            <select
              v-model="programs_selected"
              class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            >
              <option disabled value="" selected>Please select one</option>
              <option v-for="program in programs" :key="program.id" :value="program.id">
                {{ program.name }}
              </option>
            </select>
          </label>
          <label class="block">
            Month
            <select
              v-model="month_selected"
              class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              @change="getDateRange"
            >
              <option disabled value="" selected>Please select one</option>
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
          </label>
          <label class="block">
            Week Range
            <select
              v-model="week_selected"
              class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            >
              <option disabled value="" selected>Please select one</option>
              <option
                v-for="daterange in daterange"
                :key="daterange.id"
                :value="daterange.id"
              >
                {{ daterange.date_range }}
              </option>
            </select>
          </label>
          <label class="block" style="display: none">
            Class
            <select
              v-model="class_selected"
              class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              @change="getClasses"
            >
              <option disabled value="" selected>Please select one</option>
              <option
                v-for="classes in filteredClasses"
                :key="classes.id"
                :value="classes.id"
              >
                {{ classes.site.name }} {{ classes.program.name }}
                {{ classes.date_range.date_range }} {{ classes.total_target }}
              </option>
            </select>
          </label>
          <router-link
            :to="{
              path: `/addstaffing/}`,
              query: {
                program: programs_selected,
                site: sites_selected,
                daterange: week_selected,
                class_selected: class_selected,
              },
            }"
          >
            <button
              type="submit"
              class="float-right px-10 py-4 font-bold text-white bg-orange-500 rounded hover:bg-gray-600"
            >
              <i class="fa fa-building"></i> Add
            </button>
          </router-link>
        </div>
      </form>
    </div>
  </div>
  <div class="py-4">
    <div class="pl-8 pr-8">
      <div class="scroll">
        <div class="w-2/3 mx-auto datatable-container">
          <DataTable
            :data="class_staffing"
            :columns="columns"
            class="table divide-y divide-gray-200 table-auto table-striped"
            :options="{
              responsive: false,
              autoWidth: false,
              pageLength: 10,
              lengthChange: true,
              ordering:true,
              scrollX: true,
              dom: 'Bfrtip',
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
// eslint-disable-next-line no-unused-vars

import "bootstrap/dist/css/bootstrap.css";

DataTable.use(DataTableLib);
//DataTable.use(pdfmake);
DataTable.use(ButtonsHtml5);

export default {
  components: { DataTable },
  data() {
    return {
      class_staffing: [],
      classesall: [],
      programs: [],
      sites: [],
      daterange: [],
      week_selected: "",
      programs_selected: "",
      sites_selected: "",
      month_selected: "",
      class_selected: "",
      columns: [
        { data: "id", title: "ID" },
        {
          data: "id",
          title: "Actions",
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
            const classSelected = row.classes ? row.classes.id : "";
            return `<button class="btn btn-primary" data-id="${data}" onclick="window.vm.navigateToEdit(${data}, '${classSelected}')">Edit</button>`;
          },
        },
        { data: "classes.site.country", title: "Country" },
        { data: "classes.site.name", title: "Site" },
        { data: "classes.program.name", title: "Program" },
        { data: "classes.date_range.date_range", title: "Hiring Week" },
        { data: "classes.total_target", title: "Target" },
        { data: "classes.wave_no", title: "Wave#" },
        { data: "classes.erf_number", title: "ERF#" },
        { data: "classes.type_of_hiring", title: "Types of Hiring" },
        { data: "day_1", title: "Day1" },
        { data: "day_2", title: "Day2" },
        { data: "day_3", title: "Day3" },
        { data: "day_4", title: "Day4" },
        { data: "day_5", title: "Day5" },
        { data: "day_6", title: "Day6" },
        { data: "day_1_start_rate", title: "Day1 Start Rate" },
        { data: "total_endorsed", title: "Total Endorsed" },
        { data: "endorsed_rate", title: "Endorsed Rate" },
        { data: "show_ups_internal", title: "Show-ups-Internal" },
        { data: "show_ups_external", title: "Show-ups-External" },
        { data: "show_ups_total", title: "Show-ups-Total" },
        { data: "deficit", title: "Deficit" },
        { data: "percentage", title: "Percentage" },
        { data: "status", title: "Status" },
        { data: "internals_hires", title: "Internals" },
        { data: "additional_extended_jo", title: "Additional Extended JO" },
        { data: "over_hires", title: "Over Hires" },
        { data: "with_jo", title: "With JO" },
        { data: "pending_jo", title: "Pending JO" },
        { data: "pending_berlitz", title: "Pending Berlitz" },
        { data: "pending_ov", title: "Pending OV" },
        { data: "pending_pre_emps", title: "Pending Pre-Emps" },
        { data: "classes_number", title: "Classes" },
        { data: "pipeline_total", title: "Total Pipeline" },
        { data: "cap_starts", title: "Cap Starts" },
        { data: "internals_hires_all", title: "All Internals" },
        { data: "pipeline_target", title: "Pipeline Target" },
        { data: "deficit_total", title: "Total Deficit" },
        { data: "additional_remarks", title: "Additional Remarks" },
        { data: "pipeline", title: "Pipeline" },
      ],
    };
  },
  watch: {
    sites_selected: {
      immediate: true,
      handler() {
        this.getPrograms();
        this.class_selected = "";
        this.updateClassSelected();
      },
    },
    programs_selected: {
      immediate: true,
      handler() {
        this.getClasses();
        this.class_selected = "";
        this.updateClassSelected();
      },
    },
    week_selected: {
      immediate: true,
      handler() {
        this.class_selected = "";
        this.updateClassSelected();
      },
    },
  },

  computed: {
    filteredClasses() {
      return this.classesall.filter((cls) => {
        return (
          cls.site.id === this.sites_selected &&
          cls.program.id === this.programs_selected &&
          cls.date_range.id === this.week_selected
        );
      });
    },
  },
  mounted() {
    window.vm = this;
    this.getClassesAll();
    this.getClasses();
    this.getSites();
    this.getPrograms();
    this.getDateRange();
  },
  methods: {
    navigateToEdit(id, classSelected) {
      this.$router.push({
        path: `/updatestaffing/${id}`,
        query: {
          class_selected: classSelected,
        },
      });
    },

    updateClassSelected() {
      const filteredClasses = this.filteredClasses;
      if (filteredClasses.length > 0) {
        this.class_selected = filteredClasses[0].id;
      } else {
        this.class_selected = "";
      }
    },
    async getClasses() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/classesall");
        this.classesall = response.data.classes;
        console.log(response.data.classes);

        const filteredClasses = this.filteredClasses; // Get the filtered classes
        if (filteredClasses.length > 0) {
          this.class_selected = filteredClasses[0].id; // Select the first class from the filtered classes
        } else {
          this.class_selected = ""; // Reset the selection if there are no matching classes
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getClassesAll() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/classesstaffing");
        this.class_staffing = response.data.class_staffing;
        console.log(response.data.class_staffing);
      } catch (error) {
        console.log(error);
      }
    },

    async getSites() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/sites");
        this.sites = response.data.data;
        console.log(response.data.data);
      } catch (error) {
        console.log(error);
      }
    },

    async getPrograms() {
      if (!this.sites_selected) {
        return; // Do nothing if no site is selected
      }

      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/programs_selected/${this.sites_selected}`
        );
        this.programs = response.data.data;
        console.log(response.data.data);
      } catch (error) {
        console.error(error);
      }
    },

    async getDateRange() {
      if (!this.month_selected) {
        return;
      }

      try {
        const response = await axios.get(
          `http://127.0.0.1:8000/api/daterange_selected/${this.month_selected}`
        );
        this.daterange = response.data.data;
        console.log(response.data.data);
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>
<style>
.table-responsive {
  overflow: auto;
}

.datatable-container {
  width: 100%;
}

.table {
  white-space: nowrap;
}

.table thead th {
  padding: 8px;
}

.table tbody td {
  padding: 8px;
}
</style>
