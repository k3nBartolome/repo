<template>
  <div class="py-0">
    <div class="container mx-auto mt-4 px-4 py-0">
      <div class="mb-4 md:flex md:space-x-2 md:items-center py-0">
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <MultiSelect
            v-model="sites_selected"
            :options="sites"
            filter
            optionLabel="name"
            placeholder="Select Sites"
            class="px-4 py-2 border rounded-lg w-full bg-gray-100"
            :selected-items-class="'bg-orange-500 text-white'"
            :panel-style="{ backgroundColor: 'white' }"
            :panel-class="'border border-gray-300 rounded-lg shadow-lg text-black'"
            @change="getPrograms"
          />
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <MultiSelect
            v-model="programs_selected"
            :options="programs"
            filter
            optionLabel="name"
            placeholder="Select Programs"
            class="px-4 py-2 border rounded-lg w-full bg-gray-100"
            :selected-items-class="'bg-orange-500 text-white'"
            :panel-style="{ backgroundColor: 'white' }"
            :panel-class="'border border-gray-300 rounded-lg shadow-lg text-black'"
          />
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <MultiSelect
            v-model="month_selected"
            :options="months"
            filter
            optionLabel="month"
            placeholder="Select Month"
            class="px-4 py-2 border rounded-lg w-full bg-gray-100"
            :selected-items-class="'bg-orange-500 text-white'"
            :panel-style="{ backgroundColor: 'white' }"
            :panel-class="'border border-gray-300 rounded-lg shadow-lg text-black'"
            @change="getDateRange"
          />
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <MultiSelect
            v-model="week_selected"
            :options="daterange"
            filter
            optionLabel="date_range"
            placeholder="Select Date"
            class="px-4 py-2 border rounded-lg w-full bg-gray-100"
            :selected-items-class="'bg-orange-500 text-white'"
            :panel-style="{ backgroundColor: 'white' }"
            :panel-class="'border border-gray-300 rounded-lg shadow-lg text-black'"
          />
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <button
        @click="exportToExcel"
        class="px-4 py-2 bg-orange-500 text-white rounded-lg w-full"
      >
        Export to Excel
      </button>
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <button
          class="px-4 py-2 bg-red-500 text-white rounded-lg w-full"
            @click="resetFilters"
          >
            Reset Filters
          </button>
        </div>
      </div>
    </div>

    <div class="px-4">
      <div class="bg-white shadow-md rounded-lg overflow-y-auto">
        <table class="min-w-full border-collapse border-2 border-gray-300">
          <thead>
            <tr class="border-b-4 border-gray-300 bg-gray-100 text-center">
            <th class="border-2 border-gray-300 px-1 py-1 truncate">Month</th>
            <th class="border-2 border-gray-300 px-2 py-1 truncate">Week</th>
            <th class="border-2 border-gray-300 px-1 py-1 truncate">Site</th>
            <th class="border-2 border-gray-300 px-2 py-1 truncate">Program</th>
            <th class="border-2 border-gray-300 px-2 py-1 truncate">Target</th>
            <th class="border-2 border-gray-300 px-2 py-1 truncate">Internal</th>
            <th class="border-2 border-gray-300 px-2 py-1 truncate">External</th>
            <th class="border-2 border-gray-300 px-2 py-1 truncate">Overall Starts</th>
            <th class="border-2 border-gray-300 px-2 py-1 truncate">Day1</th>
            <th class="border-2 border-gray-300 px-2 py-1 truncate">Day2</th>
            <th class="border-2 border-gray-300 px-2 py-1 truncate">Day3</th>
            <th class="border-2 border-gray-300 px-2 py-1 truncate">Day4</th>
            <th class="border-2 border-gray-300 px-2 py-1 truncate">Day5</th>
            <th class="border-2 border-gray-300 px-2 py-1 truncate">Total Classes</th>
            <th class="border-2 border-gray-300 px-2 py-1 truncate">Filled</th>
            <th class="border-2 border-gray-300 px-2 py-1 truncate">Open</th>
          </tr>
        </thead>
        <tbody v-for="(mps1, index) in mps" :key="index">
          <template v-for="(mps2, index2) in mps1" :key="index2">
            <template v-for="(mps3, index3) in mps2" :key="index3">
              <tr
                class="border-2 border-black"
                v-for="(mps4, index4) in mps3"
                :key="index4"
              >
                <td class="border-2 border-gray-300 px-1 py-1 text-center font-semibold truncate">
                  {{ mps4.month }}
                </td>
                <td class="border-2 border-gray-300 px-2 py-1 text-center font-semibold truncate">
                  {{ mps4.week_name }}
                </td>
                <td class="border-2 border-gray-300 px-1 py-1 text-center font-semibold truncate">
                  {{ mps4.site_name }}
                </td>
                <td class="border-2 border-gray-300 px-2 py-1 text-center font-semibold truncate">
                  {{ mps4.program_name }}
                </td>
                <td class="border-2 border-gray-300 px-2 py-1 text-center font-semibold truncate">
                  {{ mps4.total_target }}
                </td>
                <td class="border-2 border-gray-300 px-2 py-1 text-center font-semibold truncate">
                  {{ mps4.internal }}
                </td>
                <td class="border-2 border-gray-300 px-2 py-1 text-center font-semibold truncate">
                  {{ mps4.external }}
                </td>
                <td class="border-2 border-gray-300 px-2 py-1 text-center font-semibold truncate">
                  {{ mps4.total }}
                </td>
                <td class="border-2 border-gray-300 px-2 py-1 text-center font-semibold truncate">
                  {{ mps4.day_1 }}
                </td>
                <td class="border-2 border-gray-300 px-2 py-1 text-center font-semibold truncate">
                  {{ mps4.day_2 }}
                </td>
                <td class="border-2 border-gray-300 px-2 py-1 text-center font-semibold truncate">
                  {{ mps4.day_3 }}
                </td>
                <td class="border-2 border-gray-300 px-2 py-1 text-center font-semibold truncate">
                  {{ mps4.day_4 }}
                </td>
                <td class="border-2 border-gray-300 px-2 py-1 text-center font-semibold truncate">
                  {{ mps4.day_5 }}
                </td>
                <td class="border-2 border-gray-300 px-2 py-1 text-center font-semibold truncate">
                  {{ mps4.classes }}
                </td>
                <td class="border-2 border-gray-300 px-2 py-1 text-center font-semibold truncate">
                  {{ mps4.filled }}
                </td>
                <td class="border-2 border-gray-300 px-2 py-1 text-center font-semibold truncate">
                  {{ mps4.open }}
                </td>
              </tr>
            </template>
          </template>
        </tbody>
      </table>
    </div>
  </div>
</div>
</template>
<script>
import axios from "axios";
import MultiSelect from "primevue/multiselect";
export default {
  components: {
    MultiSelect,
  },
  data() {
    return {
      mps: [],
      programs: [],
      sites: [],
      daterange: [],
      week_selected: [],
      programs_selected: [],
      sites_selected: [],
      month_selected: [],
      months: [],
      initialFilters: {
        week_selected: "",
      programs_selected: "",
      sites_selected: "",
      month_selected: "",
      },
    };
  },
  computed: {},
  watch: {
    month_selected: {
      handler: "getStaffing",
      immediate: true,
    },
    week_selected: {
      handler: "getStaffing",
      immediate: true,
    },
    sites_selected: {
      handler: "getStaffing",
      immediate: true,
    },
    programs_selected: {
      handler: "getStaffing",
      immediate: true,
    },
  },
  mounted() {
    this.getStaffing();
    this.getSites();
    this.getPrograms();
    this.getDateRange();
    this.getMonth();
  },
  methods: {
    resetFilters() {
      this.month_selected = this.initialFilters.month_selected;
      this.programs_selected = this.initialFilters.programs_selected;
      this.sites_selected = this.initialFilters.sites_selected;
      this.week_selected = this.initialFilters.week_selected;
    },
    async getStaffing() {
      try {
        const token = this.$store.state.token;

        const response = await axios.get("http://127.0.0.1:8000/api/mpsweek", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
          params: {
            month_num: this.month_selected.map(
              (month_num) => month_num.month_num
            ),
            site_id: this.sites_selected.map((site) => site.site_id),
            program_id: this.programs_selected.map(
              (program) => program.program_id
            ),
            date_id: this.week_selected.map(
              (week_selected) => week_selected.date_id
            ),
          },
        });

        this.mps = response.data.mps;
        console.log(response.data.mps);
      } catch (error) {
        console.log(error);
      }
    },
    async getMonth() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/months", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.months = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching sites");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async exportToExcel() {
      // Set export loading to true before making the request
      try {
        const token = this.$store.state.token;

        // Make an API request to trigger the Excel export
        const response = await axios.get(
          "http://127.0.0.1:8000/api/export-to-excel",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
            responseType: "blob", // Tell Axios to expect a binary response
            params: {
              // Include any parameters needed for the export
              month_num: this.month_selected.map(
                (month_num) => month_num.month_num
              ),
              site_id: this.sites_selected.map((site) => site.site_id),
              program_id: this.programs_selected.map(
                (program) => program.program_id
              ),
              date_id: this.week_selected.map(
                (week_selected) => week_selected.date_id
              ),
            },
          }
        );

        // Create a Blob and create a download link for the Excel file
        const blob = new Blob([response.data], {
          type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "your_filename.xlsx";
        a.click();
      } catch (error) {
        console.error("Error exporting data to Excel", error);
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
    async getDateRange() {
      if (!this.month_selected) {
        return;
      }
      try {
        const token = this.$store.state.token;
        const monthId = this.month_selected.map((month) => month.month_num);

        const url = `http://127.0.0.1:8000/api/daterange_select/${monthId.join(
          ","
        )}`;

        const response = await axios.get(url, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.daterange = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching week");
        }
      } catch (error) {
        console.error(error);
      }
    },
    async getPrograms() {
      if (!this.sites_selected) {
        return;
      }

      try {
        const token = this.$store.state.token;
        const siteId = this.sites_selected.map((site) => site.site_id);

        const url = `http://127.0.0.1:8000/api/programs_select/${siteId.join(
          ","
        )}`;

        const response = await axios.get(url, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

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

.col-span-6 {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

/* Responsive styles for the table */
.table {
  width: 100%;
  border-collapse: collapse;
}

.table th,
.table td {
  padding: 0.5rem;
}

@media (max-width: 768px) {
  .table th,
  .table td {
    padding: 0.25rem;
  }
}

/* Styles for the reset button */


.table thead th {
  padding: 8px;
}

.table tbody td {
  padding: 8px;
}
.dataTables_wrapper .dataTables_filter {
  float: left;
  padding-right: 30px;
}

.dataTables_wrapper .dataTables_Buttons {
  float: left;
  margin-top: 30px;
}

.dataTables_wrapper .dataTables_pagination {
  float: left;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.dataTables_wrapper .dataTables_length {
  float: left;
  padding-right: 15px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.dataTables_wrapper .dt-buttons .btn {
  background-color: #007bff;
  color: #fff;
  border-radius: 4px;
  padding: 8px 12px;
  margin-right: 8px;
  margin-top: 15px;
}
</style>
