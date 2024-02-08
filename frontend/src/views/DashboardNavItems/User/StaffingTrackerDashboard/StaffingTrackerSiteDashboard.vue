<template>
  <div class="py-0">
    <div class="container mx-auto mt-4 px-4 py-0">
      <div class="mb-4 md:flex md:space-x-2 md:items-center py-0">
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          
          <select
            v-model="month_selected"
            class="px-4 py-2 border rounded-lg w-full bg-gray-100"
          >
            <option disabled value="" selected>Please select Month</option>
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
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
         
          <select
            v-model="programs_selected"
            class="px-4 py-2 border rounded-lg w-full bg-gray-100"
          >
            <option disabled value="" selected>Please select Program</option>
            <option
              v-for="program in programs"
              :key="program.id"
              :value="program.id"
            >
              {{ program.name }}
            </option>
          </select>
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <button
          class="px-4 py-2 bg-red-500 text-white rounded-lg w-full"
            @click="resetFilters"
          >
            Reset Filters
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="px-4">
    <div class="bg-white shadow-md rounded-lg overflow-y-auto">
      <table class="min-w-full border-collapse border-2 border-gray-300">
        <thead>
          <tr class="border-b-4 border-gray-300 bg-gray-100 text-center">
          <th class="border-2 border-gray-300 px-4 py-2 truncate">Site</th>
          <th class="border-2 border-gray-300 px-4 py-2 truncate">Target</th>
          <th class="border-2 border-gray-300 px-4 py-2 truncate">Internal</th>
          <th class="border-2 border-gray-300 px-4 py-2 truncate">External</th>
          <th class="border-2 border-gray-300 px-4 py-2 truncate">Overall Starts</th>
          <th class="border-2 border-gray-300 px-4 py-2 truncate">Day1</th>
          <th class="border-2 border-gray-300 px-4 py-2 truncate">Day2</th>
          <th class="border-2 border-gray-300 px-4 py-2 truncate">Day3</th>
          <th class="border-2 border-gray-300 px-4 py-2 truncate">Day4</th>
          <th class="border-2 border-gray-300 px-4 py-2 truncate">Day5</th>
          <th class="border-2 border-gray-300 px-4 py-2 truncate">Total Classes</th>
          <th class="border-2 border-gray-300 px-4 py-2 truncate">Filled</th>
          <th class="border-2 border-gray-300 px-4 py-2 truncate">Open</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(mps1, index) in mps"
          :key="index"
          class="border-2 border-black"
        >
          <td class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate">{{ mps1.site }}</td>
          <td class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate">{{ mps1.total_target }}</td>
          <td class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate">{{ mps1.internal }}</td>
          <td class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate">{{ mps1.external }}</td>
          <td class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate">{{ mps1.total }}</td>
          <td class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate">{{ mps1.day_1 }}</td>
          <td class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate">{{ mps1.day_2 }}</td>
          <td class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate">{{ mps1.day_3 }}</td>
          <td class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate">{{ mps1.day_4 }}</td>
          <td class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate">{{ mps1.day_5 }}</td>
          <td class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate">{{ mps1.classes }}</td>
          <td class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate">{{ mps1.filled }}</td>
          <td class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate">{{ mps1.open }}</td>
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
      mps: [],
      class_staffing: [],
      programs: [],
      programs_selected: "",
      month_selected: "",
      initialFilters: {
        month_selected: "",
        programs_selected: "",
      },
    };
  },
  computed: {},
  watch: {
    month_selected: {
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
    this.getPrograms();
  },
  methods: {
    resetFilters() {
      this.month_selected = this.initialFilters.month_selected;
      this.programs_selected = this.initialFilters.programs_selected;
    },
    async getStaffing() {
      try {
        const token = this.$store.state.token;
        let apiUrl = "http://127.0.0.1:8000/api/mpssite";
        const params = {};
        if (this.month_selected) {
          params.month_num = this.month_selected;
        }
        if (this.programs_selected) {
          params.program_id = this.programs_selected;
        }
        if (Object.keys(params).length > 0) {
          apiUrl += `?${new URLSearchParams(params).toString()}`;
        }
        const response = await axios.get(apiUrl, {
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
    async getPrograms() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(`http://127.0.0.1:8000/api/programs`, {
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
<style scoped>
/* Responsive styles for the form */
.col-span-6 {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

/* Responsive styles for the table */
.table {
  width: 100%;
  border-collapse: collapse;
}

.table th,
.table td {
  padding: 0.5rem;
}

@media (max-width: 768px) {
  .table th,
  .table td {
    padding: 0.25rem;
  }
}

/* Styles for the reset button */
button {
  background-color: #e53e3e;
}
</style>
