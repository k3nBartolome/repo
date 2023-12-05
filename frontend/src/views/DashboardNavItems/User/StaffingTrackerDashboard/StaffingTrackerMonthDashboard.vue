<template>
  <div class="py-8">
    <div class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block">
            Site
            <select
              v-model="sites_selected"
              class="w-full px-3 py-2 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
          >
              <option disabled value="" selected>Please select one</option>
              <option v-for="site in sites" :key="site.id" :value="site.id">
                {{ site.name }}
              </option>
            </select>
          </label>
      </div>
      <div>
        <label class="block">Program</label>
        <select
          v-model="programs_selected"
          class="w-full px-3 py-2 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
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
      </div>
      <div>
        <button
          class="mt-4 px-4 py-2 text-white bg-red-600 rounded-md"
          @click="resetFilters"
        >
          Reset Filters
        </button>
      </div>
    </div>
  </div>
</div>
  <div class="py-2 overflow-x-auto">
    <table class="w-full border-collapse">
      <thead>
        <tr class="border-black border-2">
          <th class="py-2 px-4 text-left truncate"></th>
          <th class="py-2 px-4 text-left truncate">Month</th>
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
        <tr class="border-black border-2">
          <td class="py-2 px-4 border text-left truncate">
          </td>
          <td class="py-2 px-4 border text-left truncate">
            {{ mps1.month }}
          </td>
          <td class="py-2 px-4 border text-left truncate"> {{ mps1.total_target }}</td>
          <td class="py-2 px-4 border text-left truncate"> {{ mps1.internal }}</td>
          <td class="py-2 px-4 border text-left truncate"> {{ mps1.external }}</td>
          <td class="py-2 px-4 border text-left truncate"> {{ mps1.total }}</td>
          <td class="py-2 px-4 border text-left truncate"> {{ mps1.day_1 }}</td>
          <td class="py-2 px-4 border text-left truncate"> {{ mps1.day_2 }}</td>
          <td class="py-2 px-4 border text-left truncate"> {{ mps1.day_3 }}</td>
          <td class="py-2 px-4 border text-left truncate"> {{ mps1.day_4 }}</td>
          <td class="py-2 px-4 border text-left truncate"> {{ mps1.day_5 }}</td>
          <td class="py-2 px-4 border text-left truncate"> {{ mps1.classes }}</td>
          <td class="py-2 px-4 border text-left truncate"> {{ mps1.filled }}</td>
          <td class="py-2 px-4 border text-left truncate"> {{ mps1.open }}</td>
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
      programs: [],
      sites: [],
      programs_selected: "",
      sites_selected: "",
      initialFilters: {
        sites_selected: "",
        programs_selected: "",
      },
    };
  },
  watch: {
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
  },
  methods: {
    resetFilters() {
      this.sites_selected = this.initialFilters.sites_selected;
      this.programs_selected = this.initialFilters.programs_selected;
    },
    async getStaffing() {
      try {
        const token = this.$store.state.token;
        let apiUrl = "http://10.109.2.112:8081/api/mpsmonth";
        const params = {};
        if (this.sites_selected) {
          params.site_id = this.sites_selected;
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
      try {
        const token = this.$store.state.token;
        const response = await axios.get(`http://10.109.2.112:8081/api/programs`, {
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

