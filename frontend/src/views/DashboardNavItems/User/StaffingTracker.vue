<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h1 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Staffing Tracker
      </h1>
    </div>
  </header>
  <div class="py-8 bg-gray-100">
    <div class="px-4 py-6 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
      <form class="">
        <div
          class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5"
        >
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
              <option
                v-for="program in programs"
                :key="program.id"
                :value="program.id"
              >
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
        </div>
      </form>
    </div>
  </div>
  <div class="py-2 bg-gray-100">
    <div
      class="px-4 py-6 mx-auto bg-white border-2  max-w-7xl sm:px-6 lg:px-8"
    >
      <form
        class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5"
      >
        <label class="block">
          Country
          <input
            type="text"
            disabled
            v-model="country"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Region
          <input
            disabled
            type="text"
            v-model="region"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Site
          <select
            disabled
            v-model="site_selected"
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
          Program
          <select
            disabled
            v-model="program_selected"
            class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
            @change="getPrograms"
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
        <label class="block">
          Type of Hiring
          <select
            disabled
            v-model="type_of_hiring"
            class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
          >
            <option disabled value="" selected>Please select one</option>
            <option value="attrition">Attrition</option>
            <option value="growth">Growth</option>
            <option value="attrition and growth">Attrition and Growth</option>
          </select>
        </label>
        <label class="block">
          Year
          <input
            disabled
            type="text"
            v-model="year"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Month
          <input
            disabled
            type="text"
            v-model="month"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Hiring Week
          <select
            disabled
            v-model="hiring_week"
            class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
            @change="getDateRange2"
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
          Training Start
          <input
            disabled
            type="date"
            v-model="training_start"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Internal Target
          <input
            disabled
            type="text"
            v-model="internal_target"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          External Target
          <input
            disabled
            type="text"
            v-model="external_target"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Target
          <input
            disabled
            type="number"
            v-model="total_target"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
            @change="syncDeficitTotal"
          />
        </label>
        <label class="block">
          Wave#
          <input
            disabled
            type="text"
            v-model="wave_no"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          ERF#
          <input
            disabled
            type="text"
            v-model="erf_number"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
      </form>
    </div>
  </div>
  <form class="" @submit.prevent="addClass">
    <div class="py-2 bg-gray-100">
      <div class="px-4 py-6 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
        <div
          class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5"
        >
          <label class="block">
            Day 1
            <input
              type="number"
              v-model="day1"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Day 2
            <input
              type="number"
              v-model="day2"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Day 3
            <input
              type="number"
              v-model="day3"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Day 4
            <input
              type="number"
              v-model="day4"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Day 5
            <input
              type="number"
              v-model="day5"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Day 6
            <input
              type="number"
              v-model="day6"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Day 7
            <input
              type="number"
              v-model="day7"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Day 8
            <input
              type="number"
              v-model="day8"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Total_Endorsed
            <input
              disabled
              type="number"
              v-model="total_endorsed"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
        </div>
      </div>
    </div>
    <div class="py-2 bg-gray-100">
      <div class="px-4 py-6 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
        <div
          class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5"
        >
          <label class="block">
            Show-ups Internal
            <input
              type="number"
              v-model="show_ups_internal"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
              @change="syncShowUpsTotal"
            />
          </label>
          <label class="block">
            Show-ups External
            <input
              type="number"
              v-model="show_ups_external"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
              @change="syncShowUpsTotal"
            />
          </label>
          <label class="block">
            Show-ups Total
            <input
              type="number"
              v-model="show_ups_total"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
              @change="syncDeficitTotal"
            />
          </label>
          <label class="block">
            Deficit
            <input
              type="number"
              v-model="deficit"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Percentage
            <input
              type="number"
              v-model="percentage"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Status
            <select
              v-model="status"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            >
              <option value="open">Open</option>
              <option value="filled">Filled</option>
            </select>
          </label>
          <label class="block">
            Internals
            <input
              type="number"
              v-model="internal_hires"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Externals
            <input
              type="number"
              v-model="external_hires"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Additional Extended JO
            <input
              type="number"
              v-model="additional_extended_jo"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Overhires
            <input
              type="number"
              v-model="over_hires"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
        </div>
      </div>
    </div>
    <div class="py-2 bg-gray-100">
      <div class="px-4 py-6 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
        <div
          class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5"
        >
          <label class="block">
            With JO
            <input
              type="number"
              v-model="with_jo"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Pending Jo
            <input
              type="number"
              v-model="pending_jo"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Pending Berlitz
            <input
              type="number"
              v-model="pending_berlitz"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Pending Pre-Emps
            <input
              type="number"
              v-model="pending_pre_emps"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Classes
            <input
              type="number"
              v-model="classes_number"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Total Pipeline
            <input
              type="number"
              v-model="total_pipeline"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Cap Starts
            <input
              type="number"
              v-model="cap_starts"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            All Internals
            <input
              type="number"
              v-model="internal_hires_all"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            All Externals
            <input
              type="number"
              v-model="external_hires_all"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Pipeline Target
            <input
              type="number"
              v-model="pipeline_target"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Total Deficit
            <input
              type="number"
              v-model="total_deficit"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
        </div>
      </div>
    </div>
    <div class="py-2 bg-gray-100">
      <div class="px-4 py-6 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-4 font-semibold">
          <label class="block">
            Pipeline
            <textarea
              v-model="pipeline"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100 h-100"
              required
            />
          </label>
        </div>
        <div class="flex justify-center py-8">
          <button
            type="submit"
            class="self-center px-4 py-1 font-bold text-white bg-orange-500 rounded hover:bg-gray-600 wi"
          >
            <i class="fa fa-save"></i> Save
          </button>
        </div>
      </div>
    </div>
  </form>
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
      daterange2: [],
      sites_selected: "",
      programs_selected: "",
      month_selected: "",
      week_selected: "",
      country: "",
      region: "",
      site_selected: "",
      program_selected: "",
      type_of_hiring: "",
      year: "",
      month: "",
      hiring_week: "",
      classes_start: "",
      training_start: "",
      internal_target: "",
      external_target: "",
      total_target: "",
      erf_number: "",
      wave_no: "",
      day1: 0,
      day2: 0,
      day3: 0,
      day4: 0,
      day5: 0,
      day6: 0,
      day7: 0,
      day8: 0,
      total_endorsed: 0,
      show_ups_internal: 0,
      show_ups_external: 0,
      show_ups_total: 0,
      deficit: 0,
      percentage: 0,
      status: "",
      internal_hires: 0,
      external_hires: 0,
      additional_extended_jo: 0,
      with_jo: 0,
      pending_jo: 0,
      pending_berlitz: 0,
      pending_pre_emps: 0,
      classes_number: 0,
      total_pipeline: 0,
      cap_starts: 0,
      internal_hires_all: 0,
      external_hires_all: 0,
      pipeline_target: 0,
      total_deficit: 0,
      pipeline: "",
      over_hires: 0,
    };
  },
  computed: {
    filteredClasses() {
      let filtered = this.classesall;
      if (this.sites_selected) {
        filtered = filtered.filter((c) => c.site.id === this.sites_selected);
      }
      if (this.programs_selected) {
        filtered = filtered.filter(
          (c) => c.program.id === this.programs_selected
        );
      }
      if (this.week_selected) {
        filtered = filtered.filter(
          (c) => c.date_range.id === this.week_selected
        );
      }
      return filtered;
    },
    show_ups_total_computed() {
      const show_ups_internal = parseInt(this.show_ups_internal) || 0;
      const show_ups_external = parseInt(this.show_ups_external) || 0;
      return (show_ups_internal + show_ups_external).toFixed();
    },
    deficit_computed() {
      const target = parseInt(this.target) || 0;
      const show_ups_total = parseInt(this.show_ups_total) || 0;
      return target - show_ups_total < 0 ? 0 : target - show_ups_total;
    },
    percentage_computed() {
      const target = parseInt(this.target) || 0;
      const show_ups_total = parseInt(this.show_ups_total) || 0;
      return show_ups_total / target;
    },
    total_endorsed_computed() {
      const days = [
        parseInt(this.day1) || 0,
        parseInt(this.day2) || 0,
        parseInt(this.day3) || 0,
        parseInt(this.day4) || 0,
        parseInt(this.day5) || 0,
        parseInt(this.day6) || 0,
        parseInt(this.day7) || 0,
        parseInt(this.day8) || 0,
      ];

      return days.reduce((total, day) => total + day, 0).toFixed();
    },
    classes_number_computed() {
      const target = this.target;

      if (target % 15 > 1) {
        return Math.floor(target / 15) + 1;
      } else {
        return Math.floor(target / 15);
      }
    },
  },
  watch: {
    day1: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day2: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day3: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day4: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day5: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day6: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day7: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day8: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
  },
  mounted() {
    this.getSites();
    this.getPrograms();
    this.getDateRange();
    this.getDateRange2();
    this.getClassesAll();
    this.getClasses();
  },
  methods: {
    syncShowUpsTotal() {
      this.show_ups_total = this.show_ups_total_computed;
    },
    syncDeficitTotal() {
      this.deficit = this.deficit_computed;
      this.percentage = this.percentage_computed;
      this.classes_number = this.classes_number_computed;
    },
    syncEndorsedTotal() {
      this.total_endorsed = this.total_endorsed_computed;
    },
    async getClasses() {
      if (!this.class_selected) {
        return; // do nothing if no class is selected
      }

      try {
        const response = await axios.get(
          `http://10.109.2.112:8081/api/classes/${this.class_selected}`
        );
        const classObj = response.data.class;
        console.log(classObj);
        this.type_of_hiring = classObj.type_of_hiring;
        this.external_target = classObj.external_target;
        this.internal_target = classObj.internal_target;
        this.total_target = classObj.total_target;
        this.region = classObj.site.region;
        this.country = classObj.site.country;
        this.program_selected = classObj.program_id;
        this.site_selected = classObj.site_id;
        this.hiring_week = classObj.date_range.id;
        this.year = classObj.date_range.year;
        this.month = classObj.date_range.month;
        this.training_start = classObj.agreed_start_date;

        console.log(classObj);
      } catch (error) {
        console.log(error);
      }
    },
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
        .get(
          `http://10.109.2.112:8081/api/programs_selected/${this.sites_selected}`
        )
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
        .get(
          `http://10.109.2.112:8081/api/daterange_selected/${this.month_selected}`
        )
        .then((response) => {
          this.daterange = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getDateRange2() {
      console.log(this.hiring);
      await axios
        .get("http://10.109.2.112:8081/api/daterange")
        .then((response) => {
          this.daterange2 = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    addClass() {
      const formData = {
        day1: this.day1,
        day2: this.day2,
        day3: this.day3,
        day4: this.day4,
        day5: this.day5,
        day6: this.day6,
        day7: this.day7,
        day8: this.day8,
        total_endorsed: this.total_endorsed,
        show_ups_internal: this.show_ups_internal,
        show_ups_external: this.show_ups_external,
        show_ups_total: this.show_ups_total,
        deficit: this.deficit,
        percentage: this.percentage,
        status: this.status,
        internal_hires: this.internal_hires,
        external_hires: this.external_hires,
        additional_extended_jo: this.additional_extended_jo,
        with_jo: this.with_jo,
        pending_jo: this.pending_jo,
        pending_berlitz: this.pending_berlitz,
        pending_pre_emps: this.pending_pre_emps,
        classes_number: this.classes_number,
        total_pipeline: this.total_pipeline,
        cap_starts: this.cap_start,
        internal_hires_all: this.internal_hires_all,
        external_hires_all: this.external_hires_all,
        pipeline_target: this.pipeline_target,
        total_deficit: this.total_deficit,
        pipeline: this.pipeline,
        over_hires: this.over_hires,
        created_by: this.$store.state.user_id,
      };
      axios
        .post("http://10.109.2.112:8081/api/classesstaffing/", formData)
        .then((response) => {
          console.log(response.data);
          this.day1 = "";
          this.day2 = "";
          this.day3 = "";
          this.day4 = "";
          this.day5 = "";
          this.day6 = "";
          this.day7 = "";
          this.day8 = "";
          this.total_endorsed = "";
          this.show_ups_internal = "";
          this.show_ups_external = "";
          this.show_ups_total = "";
          this.deficit = "";
          this.percentage = "";
          this.status = "";
          this.internal_hires = "";
          this.external_hires = "";
          this.additional_extended_jo = "";
          this.with_jo = "";
          this.pending_jo = "";
          this.pending_berlitz = "";
          this.pending_pre_emps = "";
          this.classes_number = "";
          this.total_pipeline = "";
          this.cap_starts = "";
          this.all_internal_hires = "";
          this.all_external_hires = "";
          this.pipeline_target = "";
          this.total_deficit = "";
          this.pipeline = "";
          this.over_hires = "";
          this.$router.push("/staffing", () => {
            location.reload();
          });
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
  },
};
</script>
