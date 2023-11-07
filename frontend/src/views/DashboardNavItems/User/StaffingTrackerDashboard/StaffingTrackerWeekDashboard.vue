<template>
  <div class="py-8">
    <div class="px-4 sm:px-6 lg:px-8">
      <button @click="exportToExcel" class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition duration-300 ease-in-out">
        Export to Excel
      </button>
      <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="bg-surface-ground text-text-color p-4 rounded-md shadow-md">
          <label class="block mb-2">Sites</label>
          <MultiSelect
            v-model="sites_selected"
            :options="sites"
            filter
            optionLabel="name"
            placeholder="Select Sites"
            class="w-full p-2 border border-gray-300 rounded-lg md:w-60 focus:ring focus:ring-orange-500 focus:ring-opacity-50 hover:border-orange-500 hover:ring hover:ring-orange-500 hover:ring-opacity-50 transition-all duration-300 ease-in-out"
            :selected-items-class="'bg-orange-500 text-white'"
            :panel-style="{ backgroundColor: 'white' }"
            :panel-class="'border border-gray-300 rounded-lg shadow-lg text-black'"
            @change="getPrograms"
          />
        </div>
        <div class="bg-surface-ground text-text-color p-4 rounded-md shadow-md">
          <label class="block mb-2">Programs</label>
          <MultiSelect
            v-model="programs_selected"
            :options="programs"
            filter
            optionLabel="name"
            placeholder="Select Programs"
            class="w-full p-2 border border-gray-300 rounded-lg md:w-60 focus:ring focus:ring-orange-500 focus:ring-opacity-50 hover:border-orange-500 hover:ring hover:ring-orange-500 hover:ring-opacity-50 transition-all duration-300 ease-in-out"
            :selected-items-class="'bg-orange-500 text-white'"
            :panel-style="{ backgroundColor: 'white' }"
            :panel-class="'border border-gray-300 rounded-lg shadow-lg text-black'"
          />
        </div>
        <div class="bg-surface-ground text-text-color p-4 rounded-md shadow-md">
          <label class="block mb-2">Month</label>
          <MultiSelect
            v-model="month_selected"
            :options="months"
            filter
            optionLabel="month"
            placeholder="Select Month"
            class="w-full p-2 border border-gray-300 rounded-lg md:w-60 focus:ring focus:ring-orange-500 focus:ring-opacity-50 hover:border-orange-500 hover:ring hover:ring-orange-500 hover:ring-opacity-50 transition-all duration-300 ease-in-out"
            :selected-items-class="'bg-orange-500 text-white'"
            :panel-style="{ backgroundColor: 'white' }"
            :panel-class="'border border-gray-300 rounded-lg shadow-lg text-black'"
            @change="getDateRange"
          />
        </div>
        <div class="bg-surface-ground text-text-color p-4 rounded-md shadow-md">
          <label class="block mb-2">Week</label>
          <MultiSelect
            v-model="week_selected"
            :options="daterange"
            filter
            optionLabel="date_range"
            placeholder="Select Date"
            class="w-full p-2 border border-gray-300 rounded-lg md:w-60 focus:ring focus:ring-orange-500 focus:ring-opacity-50 hover:border-orange-500 hover:ring hover:ring-orange-500 hover:ring-opacity-50 transition-all duration-300 ease-in-out"
            :selected-items-class="'bg-orange-500 text-white'"
            :panel-style="{ backgroundColor: 'white' }"
            :panel-class="'border border-gray-300 rounded-lg shadow-lg text-black'"
          />
        </div>
      </div>
      
    </div>

    <div class="py-6 overflow-x-auto">
      <table class="w-full table-auto">
        <thead class="bg-gray-200">
          <tr>
            <th class="px-4 py-2 text-left">Month</th>
            <th class="px-4 py-2 text-left">Week</th>
            <th class="px-4 py-2 text-left">Site</th>
            <th class="px-4 py-2 text-left">Program</th>
            <th class="px-4 py-2 text-left">Target</th>
            <th class="px-4 py-2 text-left">Internal</th>
            <th class="px-4 py-2 text-left">External</th>
            <th class="px-4 py-2 text-left">Overall Starts</th>
            <th class="px-4 py-2 text-left">Day1</th>
            <th class="px-4 py-2 text-left">Day2</th>
            <th class="px-4 py-2 text-left">Day3</th>
            <th class="px-4 py-2 text-left">Day4</th>
            <th class="px-4 py-2 text-left">Day5</th>
            <th class="px-4 py-2 text-left">Total Classes</th>
            <th class="px-4 py-2 text-left">Filled</th>
            <th class="px-4 py-2 text-left">Open</th>
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
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.month }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.week_name }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.site_name }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.program_name }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.total_target }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.internal }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.external }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.total }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.day_1 }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.day_2 }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.day_3 }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.day_4 }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.day_5 }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.classes }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.filled }}
                </td>
                <td class="px-4 py-2 text-left truncate border">
                  {{ mps4.open }}
                </td>
              </tr>
            </template>
          </template>
        </tbody>
      </table>
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
        const response = await axios.get("http://127.0.0.1:8000/api/export-to-excel", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
          responseType: 'blob', // Tell Axios to expect a binary response
          params: {
            // Include any parameters needed for the export
            month_num: this.month_selected.map((month_num) => month_num.month_num),
            site_id: this.sites_selected.map((site) => site.site_id),
            program_id: this.programs_selected.map((program) => program.program_id),
            date_id: this.week_selected.map((week_selected) => week_selected.date_id),
          },
        });

        // Create a Blob and create a download link for the Excel file
        const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'your_filename.xlsx';
        a.click();
      } catch (error) {
        console.error('Error exporting data to Excel', error);
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

    const url = `http://127.0.0.1:8000/api/daterange_select/${monthId.join(',')}`;

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

    const url = `http://127.0.0.1:8000/api/programs_select/${siteId.join(',')}`;

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
    }}

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
