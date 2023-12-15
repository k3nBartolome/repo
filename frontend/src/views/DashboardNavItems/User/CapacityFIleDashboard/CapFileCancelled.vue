<template>
  <div class="py-8">
    <div class="px-4 sm:px-6 lg:px-8">
      <button
        @click="exportToExcel"
        class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition duration-300 ease-in-out"
      >
        Export to Excel
      </button>
      <div class="grid grid-cols-1 gap-2 md:grid-cols-2 py-2">
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
      </div>
    </div>
  </div>

  <div class="px-2">
    <div class="">
      <div class="">
        <table class="border-2 border-black">
          <thead class="">
            <tr class="border-4 border-black px-1 text-center">
              <th class="border-2 border-black px-1" rowspan="2">Site Name</th>
              <th class="border-2 border-black px-1" rowspan="2">
                Program Name
              </th>
              <th class="border-2 border-black px-1" colspan="5">Jan</th>
              <th class="border-2 border-black px-1" colspan="5">Feb</th>
              <th class="border-2 border-black px-1" colspan="5">Mar</th>
              <th class="border-2 border-black px-1" colspan="6">Apr</th>
              <th class="border-2 border-black px-1" colspan="5">May</th>
              <th class="border-2 border-black px-1" colspan="5">Jun</th>
              <th class="border-2 border-black px-1" colspan="6">Jul</th>
              <th class="border-2 border-black px-1" colspan="5">Aug</th>
              <th class="border-2 border-black px-1" colspan="6">Sep</th>
              <th class="border-2 border-black px-1" colspan="5">Oct</th>
              <th class="border-2 border-black px-1" colspan="5">Nov</th>
              <th class="border-2 border-black px-1" colspan="6">Dec</th>
              <th class="border-4 border-black px-1" rowspan="2">Total</th>
            </tr>
            <tr class="border-4 border-black px-1">
              <th class="border-2 border-black px-1">1</th>
              <th class="border-2 border-black px-1">2</th>
              <th class="border-2 border-black px-1">3</th>
              <th class="border-2 border-black px-1">4</th>
              <th class="border-2 border-black px-1">Jan</th>
              <th class="border-2 border-black px-1">1</th>
              <th class="border-2 border-black px-1">2</th>
              <th class="border-2 border-black px-1">3</th>
              <th class="border-2 border-black px-1">4</th>
              <th class="border-2 border-black px-1">Feb</th>
              <th class="border-2 border-black px-1">1</th>
              <th class="border-2 border-black px-1">2</th>
              <th class="border-2 border-black px-1">3</th>
              <th class="border-2 border-black px-1">4</th>
              <th class="border-2 border-black px-1">Mar</th>
              <th class="border-2 border-black px-1">1</th>
              <th class="border-2 border-black px-1">2</th>
              <th class="border-2 border-black px-1">3</th>
              <th class="border-2 border-black px-1">4</th>
              <th class="border-2 border-black px-1">5</th>
              <th class="border-2 border-black px-1">Apr</th>
              <th class="border-2 border-black px-1">1</th>
              <th class="border-2 border-black px-1">2</th>
              <th class="border-2 border-black px-1">3</th>
              <th class="border-2 border-black px-1">4</th>
              <th class="border-2 border-black px-1">May</th>
              <th class="border-2 border-black px-1">1</th>
              <th class="border-2 border-black px-1">2</th>
              <th class="border-2 border-black px-1">3</th>
              <th class="border-2 border-black px-1">4</th>
              <th class="border-2 border-black px-1">Jun</th>
              <th class="border-2 border-black px-1">1</th>
              <th class="border-2 border-black px-1">2</th>
              <th class="border-2 border-black px-1">3</th>
              <th class="border-2 border-black px-1">4</th>
              <th class="border-2 border-black px-1">5</th>
              <th class="border-2 border-black px-1">Jul</th>
              <th class="border-2 border-black px-1">1</th>
              <th class="border-2 border-black px-1">2</th>
              <th class="border-2 border-black px-1">3</th>
              <th class="border-2 border-black px-1">4</th>
              <th class="border-2 border-black px-1">Aug</th>
              <th class="border-2 border-black px-1">1</th>
              <th class="border-2 border-black px-1">2</th>
              <th class="border-2 border-black px-1">3</th>
              <th class="border-2 border-black px-1">4</th>
              <th class="border-2 border-black px-1">5</th>
              <th class="border-2 border-black px-1">Sep</th>
              <th class="border-2 border-black px-1">1</th>
              <th class="border-2 border-black px-1">2</th>
              <th class="border-2 border-black px-1">3</th>
              <th class="border-2 border-black px-1">4</th>
              <th class="border-2 border-black px-1">Oct</th>
              <th class="border-2 border-black px-1">1</th>
              <th class="border-2 border-black px-1">2</th>
              <th class="border-2 border-black px-1">3</th>
              <th class="border-2 border-black px-1">4</th>
              <th class="border-2 border-black px-1">Nov</th>
              <th class="border-2 border-black px-1">1</th>
              <th class="border-2 border-black px-1">2</th>
              <th class="border-2 border-black px-1">3</th>
              <th class="border-2 border-black px-1">4</th>
              <th class="border-2 border-black px-1">5</th>
              <th class="border-2 border-black px-1">Dec</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(item, index) in classes" :key="index">
              <tr>
                <td class="border-4 border-black truncate px-2 font-semibold">
                  {{ item.Site }}
                </td>
                <td class="border-4 border-black truncate px-2 font-semibold">
                  {{ item.Program }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week1 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week2 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week3 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week4 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Jan }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week5 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week6 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week7 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week8 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Feb }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week9 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week10 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week11 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week12 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Mar }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week13 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week14 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week15 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week16 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week17 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Apr }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week18 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week19 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week20 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week21 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.May }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week22 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week23 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week24 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week25 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Jun }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week26 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week27 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week28 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week29 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week30 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Jul }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week31 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week32 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week33 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week34 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Aug }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week35 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week36 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week37 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week38 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week39 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Sep }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week40 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week41 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week42 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week43 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Oct }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week44 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week45 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week46 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week47 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Nov }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week48 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week49 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week50 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week51 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Week52 }}
                </td>
                <td class="border-2 border-black text-center font-semibold">
                  {{ item.Dec }}
                </td>
                <td class="border-4 border-black text-center font-semibold">
                  {{ item.GrandTotalByProgram }}
                </td>
              </tr>
            </template>
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
      classes: [],
      grandTotal: [],
      grandTotal2: [],
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
  mounted() {
    this.getClassesAll();
    this.getSites();
    this.getPrograms();
    this.getDateRange();
    this.getMonth();
  },
  watch: {
    month_selected: {
      handler: "getClassesAll",
      immediate: true,
    },
    week_selected: {
      handler: "getClassesAll",
      immediate: true,
    },
    sites_selected: {
      handler: "getClassesAll",
      immediate: true,
    },
    programs_selected: {
      handler: "getClassesAll",
      immediate: true,
    },
  },
  methods: {
    async getClassesAll() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/classesdashboard3",
          {
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
          }
        );

        if (response.status === 200) {
          this.classes = response.data.classes;
          this.grandTotal = response.data.grandTotal;
          this.grandTotal2 = response.data.grandTotal2;
        } else {
          console.error(
            "Error fetching classes. Status code:",
            response.status
          );
        }
      } catch (error) {
        console.error("An error occurred:", error);
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
        const response = await axios.get("http://127.0.0.1:8000/api/export3", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
          responseType: "blob", // Tell Axios to expect a binary response
          params: {
            // Include any parameters needed for the export

            site_id: this.sites_selected.map((site) => site.site_id),
            program_id: this.programs_selected.map(
              (program) => program.program_id
            ),
          },
        });

        // Create a Blob and create a download link for the Excel file
        const blob = new Blob([response.data], {
          type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "your_filename.xlsx";
        a.click();
      } catch (error) {
        console.error("Error exporting data to Excel", error);
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

        const url = `http://127.0.0.1:8000/api/daterange_select/${monthId.join(
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

        const url = `http://127.0.0.1:8000/api/programs_select/${siteId.join(
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
