<template>
  <div class="py-0">
    <div
      class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
    >
      <form
        class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-6"
      >
        <div class="col-span-6 md:col-span-1">
          <button
            type="button"
            class="w-full h-12 mt-2 font-semibold text-white bg-gray-500 rounded hover:bg-gray-600"
            @click="resetFilter"
          >
            Reset Filters
          </button>
        </div>
        <div class="col-span-6 md:col-span-1">
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
        </div>
        <div class="col-span-6 md:col-span-1">
          <label class="block">
            Programs
            <select
              v-model="programs_selected"
              class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            >
              <option disabled value="" selected>Please select one</option>
              <option
                v-for="program in programs"
                :key="program.id"
                :value="program.id"
              >
                {{ program.name }}
              </option>
            </select>
          </label>
        </div>
        <div class="col-span-6 md:col-span-1">
          <label class="block">
            Month
            <select
              v-model="month_selected"
              class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              @change="onMonthSelected"
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
        </div>
        <div class="col-span-6 md:col-span-1">
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
        </div>

        <div class="col-span-6 md:col-span-1">
           <router-link
        :to="{
          path: `/addcapfile/}`,
          query: {
            program: programs_selected,
            site: sites_selected,
            daterange: week_selected,
          },
        }"
      >

      <button
      v-if="!classExists"
      type="submit"
      :disabled="isButtonDisabled"
      class="w-full h-12 mt-2 font-semibold text-white bg-orange-500 rounded hover:bg-gray-600"
    >
      <i class="fa fa-building"></i> Add
    </button>
  </router-link>
  <span v-if="EmptySelection" class="text-red-500 text-sm ml-2">Fill all the selections first</span>

</div>

      </form>
    </div>
  </div>
  <div class="py-0">
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
// eslint-disable-next-line no-unused-vars

import "bootstrap/dist/css/bootstrap.css";

DataTable.use(DataTableLib);
//DataTable.use(pdfmake);
DataTable.use(ButtonsHtml5);

export default {
  components: { DataTable },
  data() {
    return {
      classes: [],
      sites: [],
      programs: [],
      daterange: [],
      sites_selected: "",
      programs_selected: "",
      month_selected: "",
      week_selected: "",
      columns: [
        { data: "id", title: "ID" },
        {
          data: "id",
          title: "Actions",
          orderable: false,
          searchable: false,
          render: function (data) {
            return `<button class="w-40 text-xs btn btn-primary" data-id="${data}"  onclick="window.vm.navigateToEdit(${data})">Edit</button>
                    <button class="w-40 text-xs btn btn-secondary" data-id="${data}" onclick="window.vm.navigateToPushback(${data})">Pushback/Update</button>
                    <button class="w-40 btn btn-danger" data-id="${data}" onclick="window.vm.navigateToCancel(${data})">Cancel</button>
  `;
          },
        },
        { data: "site.country", title: "Country" },
        { data: "site.region", title: "Region" },
        { data: "site.name", title: "Site" },
        { data: "program.name", title: "Program" },
        { data: "date_range.month", title: "Month" },
        { data: "date_range.date_range", title: "Hiring Week" },
        { data: "total_target", title: "Total Target" },
        { data: "original_start_date", title: "Original Start Date" },
        { data: "type_of_hiring", title: "Type of Hiring" },
        { data: "created_at", title: "Created date" },
        { data: "created_by_user.name", title: "Created by" },
      ],
    };
  },
  computed: {
    isButtonDisabled() {
      return this.classExists || !this.sites_selected || !this.programs_selected || !this.week_selected;
    },
    EmptySelection() {
      return !this.sites_selected || !this.programs_selected || !this.week_selected;
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

      if (this.week_selected) {
        filteredData = filteredData.filter((classes) => {
          const weekId = classes.date_range.id;
          return weekId === this.week_selected;
        });
      }

      return filteredData;
    },
  },
  mounted() {
    window.vm = this;
    this.getSites();
    this.getPrograms();
    this.getDateRange();
    this.getClassesAll();
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
    navigateToEdit(id) {
      this.$router.push(`/editcapfile/${id}`);
    },
    navigateToCancel(id) {
      this.$router.push(`/cancelcapfile/${id}`);
    },
    navigateToPushback(id) {
      this.$router.push(`/pushbackcapfile/${id}`);
    },
    async getClassesAll() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://10.109.2.112:8081/api/classesall",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.classes = response.data.classes;
          console.log(response.data.classes);
        } else {
          console.log("Error fetching classes");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://10.109.2.112:8081/api/sites", {
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
          `http://10.109.2.112:8081/api/programs_selected/${this.sites_selected}`,
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
          `http://10.109.2.112:8081/api/daterange_selected/${this.month_selected}`,
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
