<template>
  <header class="w-full bg-orange-300">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h1 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Staffing Tracker Reports
      </h1>
    </div>
  </header>
  <div class="py-2 overflow-x-auto">
    <table class="w-full border-collapse">
      <thead>
        <tr>
          <th class="py-2 px-4 text-left">Month</th>
          <th class="py-2 px-4 text-left">Target</th>
          <th class="py-2 px-4 text-left">Internal</th>
          <th class="py-2 px-4 text-left">External</th>
          <th class="py-2 px-4 text-left">Overall Starts</th>
          <th class="py-2 px-4 text-left">Day1</th>
          <th class="py-2 px-4 text-left">Day2</th>
          <th class="py-2 px-4 text-left">Day3</th>
          <th class="py-2 px-4 text-left">Day4</th>
          <th class="py-2 px-4 text-left">Day5</th>
          <th class="py-2 px-4 text-left">Total Classes</th>
          <th class="py-2 px-4 text-left">Filled</th>
          <th class="py-2 px-4 text-left">Open</th>
        </tr>
      </thead>
      <tbody v-for="(mps, index) in mps" :key="index">
        <tr>
          <td class="border-b">
            <div class="py-2 px-4">{{ mps.month }}</div>
          </td>
          <td class="py-2 px-4 border">{{ mps.total_target }}</td>
          <td class="py-2 px-4 border">{{ mps.internal }}</td>
          <td class="py-2 px-4 border">{{ mps.external }}</td>
          <td class="py-2 px-4 border">{{ mps.total }}</td>
          <td class="py-2 px-4 border">{{ mps.day_1 }}</td>
          <td class="py-2 px-4 border">{{ mps.day_2 }}</td>
          <td class="py-2 px-4 border">{{ mps.day_3 }}</td>
          <td class="py-2 px-4 border">{{ mps.day_4 }}</td>
          <td class="py-2 px-4 border">{{ mps.day_5 }}</td>
          <td class="py-2 px-4 border">{{ mps.classes }}</td>
          <td class="py-2 px-4 border">{{ mps.filled }}</td>
          <td class="py-2 px-4 border">{{ mps.open }}</td>
        </tr>
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

        const response = await axios.get("http://127.0.0.1:8000/api/mps", {
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
