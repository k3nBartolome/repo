<template>
  <div class="py-0">
    <div class="px-6 py-0 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8">
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
    </div>
</div>
  <div class="py-6 overflow-x-auto">
    <table class="w-full py-6 border-collapse ">
      <thead>
        <tr class="border-2 border-black">
          <th class="px-4 py-2 text-left truncate"></th>

          <th class="px-4 py-2 text-left truncate">Month</th>
         <th class="px-4 py-2 text-left truncate">Week</th>
          <th class="px-4 py-2 text-left truncate">Site</th>
          <th class="px-4 py-2 text-left truncate">Program</th>
          <th class="px-4 py-2 text-left truncate">Target</th>
          <th class="px-4 py-2 text-left truncate">Internal</th>
          <th class="px-4 py-2 text-left truncate">External</th>
          <th class="px-4 py-2 text-left truncate">Overall Starts</th>
          <th class="px-4 py-2 text-left truncate">Day1</th>
          <th class="px-4 py-2 text-left truncate">Day2</th>
          <th class="px-4 py-2 text-left truncate">Day3</th>
          <th class="px-4 py-2 text-left truncate">Day4</th>
          <th class="px-4 py-2 text-left truncate">Day5</th>
          <th class="px-4 py-2 text-left truncate">Total Classes</th>
          <th class="px-4 py-2 text-left truncate">Filled</th>
          <th class="px-4 py-2 text-left truncate">Open</th>
        </tr>
      </thead>
      <tbody v-for="(mps1, index) in mps" :key="index">
        <template v-for="(mps2, index2) in mps1" :key="index2">
          <template v-for="(mps3, index3) in mps2" :key="index3">
          <tr class="border-2 border-black" v-for="(mps4, index4) in mps3" :key="index4">

          <td class="px-4 py-2 text-left truncate border">
          </td>
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
          <td class="px-4 py-2 text-left truncate border">{{ mps4.total_target }}</td>
          <td class="px-4 py-2 text-left truncate border">{{ mps4.internal }}</td>
          <td class="px-4 py-2 text-left truncate border">{{ mps4.external }}</td>
          <td class="px-4 py-2 text-left truncate border">{{ mps4.total }}</td>
          <td class="px-4 py-2 text-left truncate border">{{ mps4.day_1 }}</td>
          <td class="px-4 py-2 text-left truncate border">{{ mps4.day_2 }}</td>
          <td class="px-4 py-2 text-left truncate border">{{ mps4.day_3 }}</td>
          <td class="px-4 py-2 text-left truncate border">{{ mps4.day_4 }}</td>
          <td class="px-4 py-2 text-left truncate border">{{ mps4.day_5 }}</td>
          <td class="px-4 py-2 text-left truncate border">{{ mps4.classes }}</td>
          <td class="px-4 py-2 text-left truncate border">{{ mps4.filled }}</td>
          <td class="px-4 py-2 text-left truncate border">{{ mps4.open }}</td>
        </tr>
      </template>
        </template>
      </tbody>
    </table>
  </div>
</template>
<script>
import axios from "axios";
export default {
  data() {
    return {
      mps: [],
      grand_totals:[],
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
      active_status: "",
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
        month_num: this.month_selected,
        site_id: this.sites_selected,
        program_id: this.programs_selected,
        date_id: this.week_selected
      },
    });

    this.mps = response.data.mps;
    console.log(response.data.mps);
  } catch (error) {
    console.log(error);
  }
},
    async getClasses() {
      try {
        const token = this.$store.state.token;

        const response = await axios.get(
          "http://127.0.0.1:8000/api/classesall",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        this.classesall = response.data.classes;
        console.log(response.data.classes);

        const filteredClasses = this.filteredClasses;
        if (filteredClasses.length > 0) {
          this.class_selected = filteredClasses[0].id;
        } else {
          this.class_selected = "";
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getClassesAll() {
      try {
        const token = this.$store.state.token;

        const response = await axios.get(
          "http://127.0.0.1:8000/api/classesstaffing2",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        this.class_staffing = response.data.class_staffing;
        console.log(response.data.class_staffing);
      } catch (error) {
        console.log(error);
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
