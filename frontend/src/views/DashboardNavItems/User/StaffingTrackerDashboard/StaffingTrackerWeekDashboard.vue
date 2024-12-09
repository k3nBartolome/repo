<template>
  <div class="py-0">
    <div class="container px-4 py-0 mx-auto mt-4">
      <div class="py-0 mb-4 md:flex md:space-x-2 md:items-center">
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <MultiSelect
            v-model="sites_selected"
            :options="sites"
            filter
            optionLabel="name"
            placeholder="Select Sites"
            class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
            :selected-items-class="'bg-orange-500 text-white'"
            :panel-style="{ backgroundColor: 'white' }"
            :panel-class="'border border-gray-300 rounded-lg shadow-lg text-black'"
            @change="getPrograms"
          />
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <MultiSelect
            v-model="programs_selected"
            :options="programs"
            filter
            optionLabel="name"
            placeholder="Select Programs"
            class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
            :selected-items-class="'bg-orange-500 text-white'"
            :panel-style="{ backgroundColor: 'white' }"
            :panel-class="'border border-gray-300 rounded-lg shadow-lg text-black'"
          />
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <MultiSelect
            v-model="month_selected"
            :options="months"
            filter
            optionLabel="month"
            placeholder="Select Month"
            class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
            :selected-items-class="'bg-orange-500 text-white'"
            :panel-style="{ backgroundColor: 'white' }"
            :panel-class="'border border-gray-300 rounded-lg shadow-lg text-black'"
            @change="getDateRange"
          />
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <MultiSelect
            v-model="week_selected"
            :options="daterange"
            filter
            optionLabel="date_range"
            placeholder="Select Date"
            class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
            :selected-items-class="'bg-orange-500 text-white'"
            :panel-style="{ backgroundColor: 'white' }"
            :panel-class="'border border-gray-300 rounded-lg shadow-lg text-black'"
          />
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <button
            @click="exportToExcel"
            class="w-full px-4 py-2 text-white bg-green-600 rounded-lg"
          >
            Export to Excel
          </button>
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <button
            class="w-full px-4 py-2 text-white bg-red-500 rounded-lg"
            @click="resetFilters"
          >
            Reset Filters
          </button>
        </div>
      </div>
    </div>

    <div class="px-4">
      <div class="overflow-y-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full border-2 border-collapse border-gray-300">
          <thead>
            <tr class="text-center bg-gray-100 border-b-4 border-gray-300">
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                Week Name
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                Site Name
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                Program Name
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                Program Group
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                Total Target
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                Internals
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                Externals
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                Total
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                Fill Rate%
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                For JO
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                For Testing
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                OV
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                Day 1
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                Day 1%
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                Pipeline Total
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                Pipeline To Goal%
              </th>
              <th
                class="px-1 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                Status
              </th>
            </tr>
          </thead>
          <tbody v-for="(mps1, index) in mps" :key="index">
            <tr class="border-2 border-black">
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.week_name }}
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.site_name }}
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.program_name }}
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.program_group }}
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.total_target }}
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.show_ups_internal }}
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.show_ups_external }}
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.show_ups_total }}
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.fillrate }}%
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.pending_jo }}
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.pending_berlitz }}
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.pending_ov }}
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.day_1 }}
              </td>

              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.day_1sup }}%
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.pipeline_total }}
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.hires_goal }}%
              </td>
              <td
                class="px-2 py-1 font-semibold text-center truncate border-2 border-gray-300"
              >
                {{ mps1.color_status }}
              </td>
            </tr>
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

        const response = await axios.get("https://10.109.2.112/api/mpsweek", {
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
        const response = await axios.get("https://10.109.2.112/api/months", {
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
    async getSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("https://10.109.2.112/api/sites", {
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

        const url = `https://10.109.2.112/api/daterange_select/${monthId.join(
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

        const url = `https://10.109.2.112/api/programs_select/${siteId.join(
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
