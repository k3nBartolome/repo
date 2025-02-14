<template>
  <div class="px-4">
    <div class="bg-white shadow-md rounded-lg overflow-x-auto overflow-y-auto">
      <table class="min-w-full border-collapse border-2 border-gray-300">
        <thead>
          <tr class="border-b-4 border-gray-300 bg-gray-100 text-center">
            <th class="border-2 border-gray-300 px-4 py-2 truncate">Month</th>
            <th class="border-2 border-gray-300 px-4 py-2 truncate">
              Week Name
            </th>
            <th class="border-2 border-gray-300 px-4 py-2 truncate">
              Total Target
            </th>
            <th class="border-2 border-gray-300 px-4 py-2 truncate">
              Over Pipeline
            </th>
            <th class="border-2 border-gray-300 px-4 py-2 truncate">
              Pipeline To Goal
            </th>
            <th class="border-2 border-gray-300 px-4 py-2 truncate">
              Total Internals
            </th>
            <th class="border-2 border-gray-300 px-4 py-2 truncate">
              Total Externals
            </th>
            <th class="border-2 border-gray-300 px-4 py-2 truncate">For JO</th>
            <th class="border-2 border-gray-300 px-4 py-2 truncate">
              For Testing
            </th>
            <th class="border-2 border-gray-300 px-4 py-2 truncate">
              Internal
            </th>
            <th class="border-2 border-gray-300 px-4 py-2 truncate">
              External
            </th>
            <th class="border-2 border-gray-300 px-4 py-2 truncate">
              Total SU
            </th>
            <th class="border-2 border-gray-300 px-4 py-2 truncate">
              Fill Rate%
            </th>
            <th class="border-2 border-gray-300 px-4 py-2 truncate">Day 1</th>
            <th class="border-2 border-gray-300 px-4 py-2 truncate">Day 1%</th>
          </tr>
        </thead>
        <tbody v-for="(mps1, index) in mps" :key="index">
          <tr
            class="text-black bg-white border-b-2 border-gray-400 border-solid"
          >
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.month }}
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.week_name }}
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.total_target }}
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.pipeline_total }}
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.pipeline_goal }}%
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.total_internal }}
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.total_external }}
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.jo }}
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.versant }}
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.internal }}
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.external }}
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.total_show_ups }}
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.fill_rate }}%
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.day_1 }}
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: left">
              {{ mps1.day_1sup }} %
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
  watch: {},
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
        let apiUrl = "http://127.0.0.1:8000/api/mpsmonth";

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
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          `http://127.0.0.1:8000/api/programs`,
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
