<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h1 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Staffing Tracker
      </h1>
    </div>
  </header>
  <div class="py-4">
    <div class="pl-8 pr-8 overflow-x-auto overflow-y-auto">
      <div class="mb-4">
        <input
          type="text"
          v-model="search"
          placeholder="Search..."
          class="px-6 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500"
        />
        <router-link
          :to="{
            path: `/addstaffing/`,
            query: {
              program: programs_selected,
              site: sites_selected,
              daterange: week_selected,
            },
          }"
        >
        <button
          type="submit"
          class="px-10 py-4 font-bold text-white bg-orange-500 rounded hover:bg-gray-600 float-right"
        >
          <i class="fa fa-building"></i> Add
        </button>
      </router-link>
      </div>
    </div>
  </div>
  <div class="py-2">
    <div class="pl-8 pr-8 overflow-x-auto overflow-y-auto">
      <table class="w-full text-white table-auto">
        <thead class="sticky-header">
          <tr
            class="text-center bg-orange-500 border-2 border-orange-600 border-solid truncate"
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
            <th class="px-1 py-2">Day7</th>
            <th class="px-1 py-2">Day8</th>
            <th class="px-1 py-2">Day1 Start Rate</th>
            <th class="px-1 py-2">Day2 Start Rate</th>
            <th class="px-1 py-2">Day3 Start Rate</th>
            <th class="px-1 py-2">Day4 Start Rate</th>
            <th class="px-1 py-2">Day5 Start Rate</th>
            <th class="px-1 py-2">Day6 Start Rate</th>
            <th class="px-1 py-2">Day7 Start Rate</th>
            <th class="px-1 py-2">Day8 Start Rate</th>
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
            class="font-semibold text-center text-black bg-white border-2 border-gray-400 border-solid align-center truncate"
          >
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.id }}
            </td>
            <td class="px-2 py-2 truncate">
              <div class="flex justify-center mt-2">
                <button
                  class="flex items-center justify-center px-4 py-2 mr-2 text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-green-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
                >
                  Update
                </button>
              </div>
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.classes.site.country, }}
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
              {{ class_staffing.day_7 }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_8 }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_1_start_rate }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_2_start_rate }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_3_start_rate }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_4_start_rate }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_5_start_rate }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_6_start_rate }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_7_start_rate }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ class_staffing.day_8_start_rate }}
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
      search: "",
    };
  },
  computed: {

  },
  mounted() {
    this.getClassesAll();
  },
  methods: {
    async getClassesAll() {
      await axios
        .get("http://127.0.0.1:8000/api/classesstaffing")
        .then((response) => {
          this.class_staffing = response.data.class_staffing;
          console.log(response.data.class_staffing);
        })
        .catch((error) => {
          console.log(error);
        });
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
