<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h1 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Staffing Tracker
      </h1>
    </div>
  </header>
  <div class="py-8 bg-white">
    <div
      class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
    >
      <form class="">
        <div class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5">
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
          <label class="block" style="display: none">
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
          <router-link
          :to="{
            path: `/addstaffing/}`,
            query: {
              program: programs_selected,
              site: sites_selected,
              daterange: week_selected,
              class_selected: class_selected,
            },
          }"
        >
          <button
            type="submit"
            class="float-right px-10 py-4 font-bold text-white bg-orange-500 rounded hover:bg-gray-600"
          >
            <i class="fa fa-building"></i> Add
          </button>
        </router-link>
        </div>
      </form>
    </div>
  </div>
  <div class="py-4">
    <div class="pl-8 pr-8 overflow-x-auto overflow-y-auto">
      <div class="mb-4">
        <input
          type="text"
          v-model="search"
          placeholder="Search..."
          class="px-6 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500"
        />

      </div>
    </div>
  </div>
  <div class="py-2">
    <div class="pl-8 pr-8 overflow-x-auto overflow-y-auto">
      <table class="w-full text-white table-auto">
        <thead class="sticky-header">
          <tr
            class="text-center truncate bg-orange-500 border-2 border-orange-600 border-solid"
          >
            <th class="px-1 py-2">ID</th>
            <th class="px-2 py-2 truncate">Action</th>
            <th class="px-1 py-2">Country</th>
            <th class="px-1 py-2">Site</th>
            <th class="px-1 py-2">Program</th>
            <th class="px-1 py-2">Hiring Week</th>
            <th class="px-1 py-2">Target</th>
            <th class="px-1 py-2">Wave#</th>
            <th class="px-1 py-2">ERF#</th>
            <th class="px-1 py-2">Types of Hiring</th>
            <th class="px-1 py-2">Day1</th>
            <th class="px-1 py-2">Day2</th>
            <th class="px-1 py-2">Day3</th>
            <th class="px-1 py-2">Day4</th>
            <th class="px-1 py-2">Day5</th>
            <th class="px-1 py-2">Day6</th>
            <th class="px-1 py-2">Day1 Start Rate</th>
            <th class="px-1 py-2">Total Endorsed</th>
            <th class="px-1 py-2">Endorsed Rate</th>
            <th class="px-1 py-2">Show-ups-Internal</th>
            <th class="px-1 py-2">Show-ups-External</th>
            <th class="px-1 py-2">Show-ups-Total</th>
            <th class="px-1 py-2">Deficit</th>
            <th class="px-1 py-2">Percentage</th>
            <th class="px-1 py-2">Status</th>
            <th class="px-1 py-2">Internals</th>
            <th class="px-1 py-2">Additional Extended JO</th>
            <th class="px-1 py-2">Over Hires</th>
            <th class="px-1 py-2">With JO</th>
            <th class="px-1 py-2">Pending JO</th>
            <th class="px-1 py-2">Pending Berlitz</th>
            <th class="px-1 py-2">Pending OV</th>
            <th class="px-1 py-2">Pending Pre-Emps</th>
            <th class="px-1 py-2">Classes</th>
            <th class="px-1 py-2">Total Pipeline</th>
            <th class="px-1 py-2">Cap Starts</th>
            <th class="px-1 py-2">All Internals</th>
            <th class="px-1 py-2">Pipeline Target</th>
            <th class="px-1 py-2">Total Deficit</th>
            <th class="px-1 py-2">Additional Remarks</th>
            <th class="px-1 py-2">Pipeline</th>
          </tr>
        </thead>
        <tbody v-for="class_staffing in class_staffing" :key="class_staffing.id">
          <tr
            class="font-semibold text-center text-black truncate bg-white border-2 border-gray-400 border-solid align-center"
          >
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.id }}
            </td>
            <td class="px-2 py-2 truncate">
              <div class="flex justify-center mt-2">
                <router-link :to="`/updatestaffing/${class_staffing.id}`">
                  <button
                    class="flex items-center justify-center px-4 py-2 mr-2 text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-green-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
                  >
                    Update
                  </button>
                </router-link>
              </div>
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.classes.site.country }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.classes.site.name }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.classes.program.name }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.classes.date_range.date_range }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.classes.total_target }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.classes.wave_no }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.classes.erf_number }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.classes.type_of_hiring }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_1 }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_2 }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_3 }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_4 }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_5 }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_6 }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_1_start_rate }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.total_endorsed }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.endorsed_rate }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.show_ups_internal }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.show_ups_external }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.show_ups_total }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.deficit }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.percentage }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.status }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.internals_hires }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.additional_extended_jo }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.over_hires }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.with_jo }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.pending_jo }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.pending_berlitz }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.pending_ov }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.pending_pre_emps }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.classes_number }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.pipeline_total }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.cap_starts }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.internals_hires_all }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.pipeline_target }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.deficit_total }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.additional_remarks }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.pipeline }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      class_staffing: [],
      classesall: [],
      programs: [],
      sites: [],
      daterange: [],
      search: "",
      week_selected: "",
      programs_selected: "",
      sites_selected: "",
      month_selected: "",
      class_selected: "",
    };
  },
  watch: {
    sites_selected: {
      immediate: true,
      handler() {
        this.getPrograms();
        this.class_selected = "";
        this.updateClassSelected();
      },
    },
    programs_selected: {
      immediate: true,
      handler() {
        this.getClasses();
        this.class_selected = "";
        this.updateClassSelected();
      },
    },
    week_selected: {
      immediate: true,
      handler() {
        this.class_selected = "";
        this.updateClassSelected();
      },
    },
  },

  computed: {
    filteredClasses() {
      return this.classesall.filter((cls) => {
        return (
          cls.site.id === this.sites_selected &&
          cls.program.id === this.programs_selected &&
          cls.date_range.id === this.week_selected
        );
      });
    },
  },
  mounted() {
    this.getClassesAll();
    this.getClasses();
    this.getSites();
    this.getPrograms();
    this.getDateRange();
  },
  methods: {
    updateClassSelected() {
      const filteredClasses = this.filteredClasses;
      if (filteredClasses.length > 0) {
        this.class_selected = filteredClasses[0].id;
      } else {
        this.class_selected = "";
      }
    },
    async getClasses() {
      try {
        const response = await axios.get("http://10.109.2.112:8081/api/classesall");
        this.classesall = response.data.classes;
        console.log(response.data.classes);

        const filteredClasses = this.filteredClasses; // Get the filtered classes
        if (filteredClasses.length > 0) {
          this.class_selected = filteredClasses[0].id; // Select the first class from the filtered classes
        } else {
          this.class_selected = ""; // Reset the selection if there are no matching classes
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getClassesAll() {
      try {
        const response = await axios.get("http://10.109.2.112:8081/api/classesstaffing");
        this.class_staffing = response.data.class_staffing;
        console.log(response.data.class_staffing);
      } catch (error) {
        console.log(error);
      }
    },

    async getSites() {
      try {
        const response = await axios.get("http://10.109.2.112:8081/api/sites");
        this.sites = response.data.data;
        console.log(response.data.data);
      } catch (error) {
        console.log(error);
      }
    },

    async getPrograms() {
      if (!this.sites_selected) {
        return; // Do nothing if no site is selected
      }

      try {
        const response = await axios.get(
          `http://10.109.2.112:8081/api/programs_selected/${this.sites_selected}`
        );
        this.programs = response.data.data;
        console.log(response.data.data);
      } catch (error) {
        console.error(error);
      }
    },

    async getDateRange() {
      if (!this.month_selected) {
        return;
      }

      try {
        const response = await axios.get(
          `http://10.109.2.112:8081/api/daterange_selected/${this.month_selected}`
        );
        this.daterange = response.data.data;
        console.log(response.data.data);
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>
<style>
.sticky-header {
  position: sticky;
  top: 0;
  z-index: 1;
  background-color: #fff;
}
</style>
