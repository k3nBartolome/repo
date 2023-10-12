<template>
  <header class="w-full bg-orange-300">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h1 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Staffing Tracker Reports Month
      </h1>
    </div>
  </header>
  <div class="py-2 overflow-x-auto">
    <table class="w-full border-collapse">
      <thead>
        <tr class="border-black border-2">
          <th class="py-2 px-4 text-left truncate"></th>
          <th class="py-2 px-4 text-left truncate">Month</th>
          <th class="py-2 px-4 text-left truncate">Site</th>
          <th class="py-2 px-4 text-left truncate">Program</th>
          <th class="py-2 px-4 text-left truncate">Target</th>
          <th class="py-2 px-4 text-left truncate">Internal</th>
          <th class="py-2 px-4 text-left truncate">External</th>
          <th class="py-2 px-4 text-left truncate">Overall Starts</th>
          <th class="py-2 px-4 text-left truncate">Day1</th>
          <th class="py-2 px-4 text-left truncate">Day2</th>
          <th class="py-2 px-4 text-left truncate">Day3</th>
          <th class="py-2 px-4 text-left truncate">Day4</th>
          <th class="py-2 px-4 text-left truncate">Day5</th>
          <th class="py-2 px-4 text-left truncate">Total Classes</th>
          <th class="py-2 px-4 text-left truncate">Filled</th>
          <th class="py-2 px-4 text-left truncate">Open</th>
        </tr>
      </thead>
      <tbody v-for="(mps1, index) in mps" :key="index">
        <template v-for="(mps2, index2) in mps1" :key="index2">
        <tr class="border-black border-2" v-for="(mps3, index3) in mps2" :key="index3">
          <td class="py-2 px-4 border text-left truncate">
          </td>
          <td class="py-2 px-4 border text-left truncate">
            {{ mps3.month }}
          </td>
          <td class="py-2 px-4 border text-left truncate">
           {{ mps3.site_name }}
          </td>
          <td class="py-2 px-4 border text-left truncate">
            {{ mps3.program_name }}
          </td>
          <td class="py-2 px-4 border text-left truncate">{{ mps3.total_target }}</td>
          <td class="py-2 px-4 border text-left truncate">{{ mps3.internal }}</td>
          <td class="py-2 px-4 border text-left truncate">{{ mps3.external }}</td>
          <td class="py-2 px-4 border text-left truncate">{{ mps3.total }}</td>
          <td class="py-2 px-4 border text-left truncate">{{ mps3.day_1 }}</td>
          <td class="py-2 px-4 border text-left truncate">{{ mps3.day_2 }}</td>
          <td class="py-2 px-4 border text-left truncate">{{ mps3.day_3 }}</td>
          <td class="py-2 px-4 border text-left truncate">{{ mps3.day_4 }}</td>
          <td class="py-2 px-4 border text-left truncate">{{ mps3.day_5 }}</td>
          <td class="py-2 px-4 border text-left truncate">{{ mps3.classes }}</td>
          <td class="py-2 px-4 border text-left truncate">{{ mps3.filled }}</td>
          <td class="py-2 px-4 border text-left truncate">{{ mps3.open }}</td>
        </tr>

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
      mont_selected: "",
      class_selected: "",
      active_status: "",
    };
  },
  computed: {},
  watch: {},
  mounted() {
    this.getClassesAll();
    this.getClasses();
    this.getStaffing();
    this.getSites();
    this.getPrograms();
    this.getDateRange();
  },
  methods: {
    async getStaffing() {
      try {
        const token = this.$store.state.token;

        const response = await axios.get("http://10.109.2.112:8081/api/mpssite", {
          headers: {
            Authorization: `Bearer ${token}`,
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
          "http://10.109.2.112:8081/api/classesall",
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
          "http://10.109.2.112:8081/api/classesstaffing2",
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
