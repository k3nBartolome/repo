<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h1 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Staffing Tracker
      </h1>
    </div>
  </header>
  <div class="py-2 bg-gray-100">
    <div
      class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
    >
      <form class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5">
        <label class="block">
          Country
          <input
            type="text"
            disabled
            v-model="country"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 bg-gray-200 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Region
          <input
            disabled
            type="text"
            v-model="region"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 bg-gray-200 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Site
          <select
            disabled
            v-model="site_selected"
            class="block w-full mt-1 border border-2 rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            @change="getPrograms"
          >
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
            class="block w-full mt-1 border border-2 bg-gray-200 rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
            @change="getPrograms"
          >
            <option v-for="program in programs" :key="program.id" :value="program.id">
              {{ program.name }}
            </option>
          </select>
        </label>
        <label class="block">
          Type of Hiring
          <select
            disabled
            v-model="type_of_hiring"
            class="block w-full mt-1 border border-2 rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
          >
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
            class="block w-full mt-1 border rounded-md focus:border-orange-600 bg-gray-200 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Month
          <input
            disabled
            type="text"
            v-model="month"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 bg-gray-200 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Hiring Week
          <select
            disabled
            v-model="hiring_week"
            class="block w-full mt-1 border border-2 rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
            @change="getDateRange"
          >
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
            class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Internal Target
          <input
            disabled
            type="text"
            v-model="internal_target"
            class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          External Target
          <input
            disabled
            type="text"
            v-model="external_target"
            class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Target
          <input
            disabled
            type="number"
            v-model="total_target"
            class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Wave#
          <input
            disabled
            type="text"
            v-model="wave_no"
            class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          ERF#
          <input
            disabled
            type="text"
            v-model="erf_number"
            class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
      </form>
    </div>
  </div>
  <form class="" @submit.prevent="addClass">
    <div class="py-2 bg-gray-100">
      <div
        class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
      >
        <div class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5">
          <label class="block">
            Day 1
            <input
              type="number"
              v-model="day_1"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Day 2
            <input
              type="number"
              v-model="day_2"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Day 3
            <input
              type="number"
              v-model="day_3"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Day 4
            <input
              type="number"
              v-model="day_4"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Day 5
            <input
              type="number"
              v-model="day_5"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Day 6
            <input
              type="number"
              v-model="day_6"
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
              class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
        </div>
      </div>
    </div>
    <div class="py-2 bg-gray-100">
      <div
        class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
      >
        <div class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5">
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
              disabled
              type="number"
              v-model="show_ups_total"
              class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Deficit
            <input
              disabled
              type="number"
              v-model="deficit"
              class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Percentage
            <input
              disabled
              type="text"
              v-model="percentage"
              class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
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
              <option disabled value="" selected>Please select one</option>
              <option value="open">Open</option>
              <option value="filled">Filled</option>
            </select>
          </label>
          <label class="block">
            Internals
            <input
              type="number"
              v-model="internals_hires"
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
              disabled
              type="number"
              v-model="over_hires"
              class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
        </div>
      </div>
    </div>
    <div class="py-2 bg-gray-100">
      <div
        class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
      >
        <div class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5">
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
            Pending OV
            <input
              type="number"
              v-model="pending_ov"
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
        </div>
      </div>
    </div>
    <div class="py-2 bg-gray-100">
      <div
        class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
      >
        <div class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5">
          <label class="block">
            Classes
            <input
              disabled
              type="number"
              v-model="classes_number"
              class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Total Pipeline
            <input
              disabled
              type="number"
              v-model="pipeline_total"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 bg-gray-200 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Cap Starts
            <input
              disabled
              type="number"
              v-model="cap_starts"
              class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            All Internals
            <input
              disabled
              type="number"
              v-model="internals_hires_all"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 bg-gray-200 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
          <label class="block">
            Pipeline Target
            <input
              disabled
              type="number"
              v-model="pipeline_target"
              class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
        </div>
        <label class="block">
          Additional Remarks
          <textarea
            type="text"
            v-model="additional_remarks"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
      </div>
    </div>
    <div class="py-2 bg-gray-100">
      <div
        class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
      >
        <div class="grid grid-cols-1 gap-4 font-semibold">
          <label class="block">
            Pipeline
            <textarea
              disabled
              v-model="pipeline"
              class="block w-full mt-1 border rounded-md bg-gray-200 focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100 h-100"
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
      classesall: [],
      sites: [],
      programs: [],
      daterange: [],
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
      day_1: 0,
      day_2: 0,
      day_3: 0,
      day_4: 0,
      day_5: 0,
      day_6: 0,
      total_endorsed: 0,
      show_ups_internal: 0,
      show_ups_external: 0,
      show_ups_total: 0,
      deficit: 0,
      percentage: 0,
      status: "",
      internals_hires: 0,
      externals_hires: 0,
      additional_extended_jo: 0,
      with_jo: 0,
      pending_jo: 0,
      pending_berlitz: 0,
      pending_ov: 0,
      pending_pre_emps: 0,
      classes_number: 0,
      pipeline_total: 0,
      cap_starts: 0,
      internals_hires_all: 0,
      externals_hires_all: 0,
      pipeline_target: 0,
      deficit_total: 0,
      pipeline: "",
      over_hires: 0,
      additional_remarks: "",
    };
  },
  computed: {
    programs_selected() {
      return this.$route.query.program;
    },
    sites_selected() {
      return this.$route.query.site;
    },
    week_selected() {
      return this.$route.query.daterange;
    },
    class_selected() {
      return this.$route.query.class_selected;
    },
    filteredClasses() {
      let filtered = this.classesall;
      if (this.sites_selected) {
        filtered = filtered.filter((c) => c.site.id === this.sites_selected);
      }
      if (this.programs_selected) {
        filtered = filtered.filter((c) => c.program.id === this.programs_selected);
      }
      if (this.week_selected) {
        filtered = filtered.filter((c) => c.date_range.id === this.week_selected);
      }
      return filtered;
    },
    show_ups_total_computed() {
      const show_ups_internal = parseInt(this.show_ups_internal) || 0;
      const show_ups_external = parseInt(this.show_ups_external) || 0;
      return (show_ups_internal + show_ups_external).toFixed();
    },
    deficit_computed() {
      const targetValue = Number(parseFloat(this.total_target));
      const showUpsValue = Number(parseFloat(this.show_ups_total));

      if (isNaN(targetValue) || isNaN(showUpsValue)) {
        return 0; // Treat invalid or empty values as 0
      }

      if (targetValue - showUpsValue < 0) {
        return 0;
      } else {
        return targetValue - showUpsValue;
      }
    },
    percentage_computed() {
      const total_target = Number(parseInt(this.total_target)) || 0;
      const show_ups_total = Number(parseInt(this.show_ups_total)) || 0;

      if (total_target === 0) {
        return "0%";
      }

      const percentage = (show_ups_total / total_target) * 100;
      return percentage.toFixed(2) + "%";
    },
    total_endorsed_computed() {
      const days = [
        parseInt(this.day_1) || 0,
        parseInt(this.day_2) || 0,
        parseInt(this.day_3) || 0,
        parseInt(this.day_4) || 0,
        parseInt(this.day_5) || 0,
        parseInt(this.day_6) || 0,
      ];
      return days.reduce((total, day) => total + day, 0).toFixed();
    },
    classes_number_computed() {
      const target = this.total_target;

      if (target % 15 > 1) {
        return Math.floor(target / 15) + 1;
      } else {
        return Math.floor(target / 15);
      }
    },
    over_hires_computed() {
      const difference = this.show_ups_total - this.total_target;
      if (difference < 0) {
        return 0;
      } else {
        return difference;
      }
    },
    pipeline_total_computed() {
      const pipeline = [
        parseInt(this.internals_hires) || 0,
        parseInt(this.externals_hires) || 0,
        parseInt(this.with_jo) || 0,
        parseInt(this.pending_jo) || 0,
        parseInt(this.pending_berlitz) || 0,
        parseInt(this.pending_ov) || 0,
        parseInt(this.pending_pre_emps) || 0,
      ];

      return pipeline.reduce((total, pipeline) => total + pipeline, 0).toFixed();
    },
    capstart_computed() {
      const show_ups_total = this.show_ups_total;
      const total_target = this.total_target;

      return show_ups_total > total_target ? total_target : show_ups_total;
    },
    pipeline_computed() {
      const internals_hires = this.internals_hires;
      const with_jo = this.with_jo;
      const pending_jo = this.pending_jo;
      const pending_berlitz = this.pending_berlitz;
      const pending_ov = this.pending_ov;
      const pending_pre_emps = this.pending_pre_emps;
      const additional_remarks = this.additional_remarks;

      let result = "";

      if (internals_hires !== "" && internals_hires !== 0) {
        result += `${internals_hires} Internals ; `;
      }

      if (with_jo !== "" && with_jo !== 0) {
        result += `${with_jo} With JO ; `;
      }

      if (pending_jo !== "" && pending_jo !== 0) {
        result += `${pending_jo} Pending JO ; `;
      }

      if (pending_berlitz !== "" && pending_berlitz !== 0) {
        result += `${pending_berlitz} Pending Berlitz ; `;
      }

      if (pending_ov !== "" && pending_ov !== 0) {
        result += `${pending_ov} Pending OV ; `;
      }

      if (pending_pre_emps !== "" && pending_pre_emps !== 0) {
        result += `${pending_pre_emps} Pending Pre Emps ; `;
      }

      if (additional_remarks !== "" && additional_remarks !== 0) {
        result += `${additional_remarks} Additional Remarks ; `;
      }

      return result.trim();
    },
    all_internals_hires_computed() {
      if (
        this.show_ups_internal >= this.total_target &&
        (this.deficit === "" || this.deficit === 0)
      ) {
        return 1;
      } else {
        return 0;
      }
    },
    deficit_total_computed() {
      const total_target = this.total_target;
      const show_ups_total = this.show_ups_total;
      const internals_hires = this.internals_hires;
      const with_jo = this.with_jo;

      const minValue = Math.min(total_target, show_ups_total + internals_hires + with_jo);
      const result = total_target - minValue;
      return result;
    },

    pipeline_target_computed() {
      const pipeline_total = this.pipeline_total;
      const total_target = this.total_target;

      const result = pipeline_total > total_target ? total_target : pipeline_total;

      return result || 0;
    },
  },
  watch: {
    day_1: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day_2: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day_3: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day_4: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day_5: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day_6: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    total_target: {
      handler: "syncDeficitTotal",
      immediate: true,
    },
    show_ups_total: {
      handler: "syncDeficitTotal",
      immediate: true,
    },
    internals_hires: {
      handler: "syncPipelineTotal",
      immediate: true,
    },
    externals_hires: {
      handler: "syncPipelineTotal",
      immediate: true,
    },
    with_jo: {
      handler: "syncPipelineTotal",
      immediate: true,
    },
    pending_jo: {
      handler: "syncPipelineTotal",
      immediate: true,
    },
    pending_berlitz: {
      handler: "syncPipelineTotal",
      immediate: true,
    },
    pending_pre_emps: {
      handler: "syncPipelineTotal",
      immediate: true,
    },
    additional_remarks: {
      handler: "syncPipelineTotal",
      immediate: true,
    },
    pipeline_total: {
      handler: "syncPipelineTarget",
      immediate: true,
    },
  },
  mounted() {
    this.getSites();
    this.getPrograms();
    this.getDateRange();
    this.getClassesAll();
    this.getClasses();
  },
  methods: {
    syncShowUpsTotal() {
      this.show_ups_total = this.show_ups_total_computed;
      this.internals_hires_all = this.all_internals_hires_computed;
    },
    syncDeficitTotal() {
      this.deficit = this.deficit_computed;
      this.percentage = this.percentage_computed;
      this.classes_number = this.classes_number_computed;
      this.over_hires = this.over_hires_computed;
      this.cap_starts = this.capstart_computed;
      this.internals_hires_all = this.all_internals_hires_computed;
      this.deficit_total = this.deficit_total_computed;
      this.pipeline_target = this.pipeline_target_computed;
    },
    syncEndorsedTotal() {
      this.total_endorsed = this.total_endorsed_computed;
    },
    syncPipelineTotal() {
      this.pipeline_total = this.pipeline_total_computed;
      this.pipeline = this.pipeline_computed;
      this.deficit_total = this.deficit_total_computed;
    },
    syncPipelineTarget() {
      this.pipeline_target = this.pipeline_target_computed;
      this.deficit_total = this.deficit_total_computed;
    },

    async getClasses() {
      try {
        if (this.class_selected) {
          const response = await axios.get(
            "http:///api/classes/" + this.$route.query.class_selected
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
          this.erf_number = classObj.erf_number;
          this.wave_no = classObj.wave_no;
        } else {
          console.log("No class selected");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getClassesAll() {
      await axios
        .get("http:///api/classesall")
        .then((response) => {
          this.classesall = response.data.classes;
          console.log(response.data.classes);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getSites() {
      await axios
        .get("http:///api/sites")
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
        .get(`http:///api/programs_selected/${this.sites_selected}`)
        .then((response) => {
          this.programs = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.error(error);
        });
    },
    async getDateRange() {
      await axios
        .get("http:///api/daterange")
        .then((response) => {
          this.daterange = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    addClass() {
      const formData = {
        day_1: this.day_1,
        day_2: this.day_2,
        day_3: this.day_3,
        day_4: this.day_4,
        day_5: this.day_5,
        day_6: this.day_6,
        total_endorsed: this.total_endorsed,
        show_ups_internal: this.show_ups_internal,
        show_ups_external: this.show_ups_external,
        show_ups_total: this.show_ups_total,
        deficit: this.deficit,
        percentage: this.percentage,
        status: this.status,
        internals_hires: this.internals_hires,
        externals_hires: this.externals_hires,
        additional_extended_jo: this.additional_extended_jo,
        with_jo: this.with_jo,
        pending_jo: this.pending_jo,
        pending_berlitz: this.pending_berlitz,
        pending_ov: this.pending_ov,
        pending_pre_emps: this.pending_pre_emps,
        classes_number: this.classes_number,
        pipeline_total: this.pipeline_total,
        cap_starts: this.cap_starts,
        internals_hires_all: this.internals_hires_all,
        externals_hires_all: this.externals_hires_all,
        pipeline_target: this.pipeline_target,
        pipeline: this.pipeline,
        over_hires: this.over_hires,
        additional_remarks: this.additional_remarks,
        classes_id: this.class_selected,
        created_by: this.$store.state.user_id,
      };
      axios
        .post("http:///api/classesstaffing", formData)
        .then((response) => {
          console.log(response.data);
          this.day_1 = "";
          this.day_2 = "";
          this.day_3 = "";
          this.day_4 = "";
          this.day_5 = "";
          this.day_6 = "";
          this.total_endorsed = "";
          this.show_ups_internal = "";
          this.show_ups_external = "";
          this.show_ups_total = "";
          this.deficit = "";
          this.percentage = "";
          this.status = "";
          this.internals_hires = "";
          this.externals_hires = "";
          this.additional_extended_jo = "";
          this.with_jo = "";
          this.pending_jo = "";
          this.pending_berlitz = "";
          this.pending_ov = "";
          this.pending_pre_emps = "";
          this.classes_number = "";
          this.pipeline_total = "";
          this.cap_starts = "";
          this.internals_hires_all = "";
          this.all_externals_hires = "";
          this.pipeline_target = "";
          this.pipeline = "";
          this.over_hires = "";
          this.additional_remarks = "";
          this.class_selected = "";
          this.$router.push("/staffing", () => {
            location.reload();
          });
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
  },
  async getSites() {
    await axios
      .get("http:///api/sites")
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
      .get(`http:///api/programs_selected/${this.sites_selected}`)
      .then((response) => {
        this.programs = response.data.data;
        console.log(response.data.data);
      })
      .catch((error) => {
        console.error(error);
      });
  },

  async getDateRange() {
    await axios
      .get("http:///api/daterange")
      .then((response) => {
        this.daterange = response.data.data;
        console.log(response.data.data);
      })
      .catch((error) => {
        console.log(error);
      });
  },
  addClass() {
    const formData = {
      day_1: this.day_1,
      day_2: this.day_2,
      day_3: this.day_3,
      day_4: this.day_4,
      day_5: this.day_5,
      day_6: this.day_6,
      total_endorsed: this.total_endorsed,
      show_ups_internal: this.show_ups_internal,
      show_ups_external: this.show_ups_external,
      show_ups_total: this.show_ups_total,
      deficit: this.deficit,
      percentage: this.percentage,
      status: this.status,
      internals_hires: this.internals_hires,
      externals_hires: this.externals_hires,
      additional_extended_jo: this.additional_extended_jo,
      with_jo: this.with_jo,
      pending_jo: this.pending_jo,
      pending_berlitz: this.pending_berlitz,
      pending_ov: this.pending_ov,
      pending_pre_emps: this.pending_pre_emps,
      classes_number: this.classes_number,
      pipeline_total: this.pipeline_total,
      cap_starts: this.cap_starts,
      internals_hires_all: this.internals_hires_all,
      externals_hires_all: this.externals_hires_all,
      pipeline_target: this.pipeline_target,
      pipeline: this.pipeline,
      over_hires: this.over_hires,
      additional_remarks: this.additional_remarks,
      classes_id: this.class_selected,
      created_by: this.$store.state.user_id,
    };
    axios
      .post("http:///api/classesstaffing", formData)
      .then((response) => {
        console.log(response.data);
        this.day_1 = "";
        this.day_2 = "";
        this.day_3 = "";
        this.day_4 = "";
        this.day_5 = "";
        this.day_6 = "";
        this.total_endorsed = "";
        this.show_ups_internal = "";
        this.show_ups_external = "";
        this.show_ups_total = "";
        this.deficit = "";
        this.percentage = "";
        this.status = "";
        this.internals_hires = "";
        this.externals_hires = "";
        this.additional_extended_jo = "";
        this.with_jo = "";
        this.pending_jo = "";
        this.pending_berlitz = "";
        this.pending_ov = "";
        this.pending_pre_emps = "";
        this.classes_number = "";
        this.pipeline_total = "";
        this.cap_starts = "";
        this.internals_hires_all = "";
        this.all_externals_hires = "";
        this.pipeline_target = "";
        this.pipeline = "";
        this.over_hires = "";
        this.additional_remarks = "";
        this.class_selected = "";
        this.$router.push("/staffing", () => {
          location.reload();
        });
      })
      .catch((error) => {
        console.log(error.response.data);
      });
  },
};
</script>
