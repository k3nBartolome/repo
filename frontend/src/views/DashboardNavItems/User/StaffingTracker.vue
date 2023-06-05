<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h1 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Staffing Tracker
      </h1>
    </div>
  </header>
  <div class="py-8">
    <div
      class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
    >
      <form class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5">
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
        <label class="block">
          Class
          <select
            v-model="class_selected"
            class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
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
      </form>
    </div>
  </div>
</template>
<script>
import axios from "axios";
export default {
  data() {
    return {
      class_selected: "",
      classesall: [],
      sites: [],
      programs: [],
      daterange: [],
      sites_selected: "",
      programs_selected: "",
      month_selected: "",
      week_selected: "",
    };
  },
  computed: {
    filteredClasses() {
      let filtered = this.classesall;

      // Filter by site
      if (this.sites_selected) {
        filtered = filtered.filter((c) => c.site.id === this.sites_selected);
      }

      // Filter by program
      if (this.programs_selected) {
        filtered = filtered.filter((c) => c.program.id === this.programs_selected);
      }

      // Filter by date range
      if (this.week_selected) {
        filtered = filtered.filter((c) => c.date_range.id === this.week_selected);
      }

      return filtered;
    },
  },
  mounted() {
    this.getSites();
    this.getPrograms();
    this.getDateRange();
    this.getClassesAll();
  },
  methods: {
    async getClassesAll() {
      await axios
        .get("http://10.109.2.112:8081/api/classesall")
        .then((response) => {
          this.classesall = response.data.classes;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getSites() {
      await axios
        .get("http://10.109.2.112:8081/api/sites")
        .then((response) => {
          this.sites = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getPrograms() {
      if (!this.sites_selected) {
        return; // do nothing if no site is selected
      }

      await axios
        .get(`http://10.109.2.112:8081/api/programs_selected/${this.sites_selected}`)
        .then((response) => {
          this.programs = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.error(error);
        });
    },

    async getDateRange() {
      if (!this.month_selected) {
        return;
      }

      await axios
        .get(`http://10.109.2.112:8081/api/daterange_selected/${this.month_selected}`)
        .then((response) => {
          this.daterange = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
