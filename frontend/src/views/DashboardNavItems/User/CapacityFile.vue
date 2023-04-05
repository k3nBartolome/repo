<template>
  <div class="overflow-x-auto">
    <table class="table-auto border border-black">
      <thead>
        <tr>
          <th class="px-4">Sites</th>
          <th class="px-4">Programs</th>
          <th
            v-for="daterange in daterange"
            :key="daterange.id"
            class="px-4 truncate py-1 border border-black bg-orange-500 text-white"
          >
            {{ daterange.date_range }}
          </th>
        </tr>
      </thead>
      <tbody class="overflow-y-auto">
        <tr v-for="program in programs" :key="program.id">
          <td class="px-4 py-1 truncate border border-black">{{ program.site_id }}</td>
          <td class="px-4 py-1 truncate border border-black">{{ program.name }}</td>
          <td
            v-for="daterange in daterange"
            :key="daterange.id"
            class="w-1/4 px-2 py-1 border border-black truncate"
          >
            <button
              type="button"
              @click="showButtons"
              class="bg-white px-4 w-full h-full"
            >
              {{ total_target }}
            </button>
            <div class="flex items-center" v-if="total_target === 0">
              <router-link
                :to="{
                  path: `/addcapfile/${daterange.year}${program.id + 100}${daterange.id}`,
                  query: {
                    program: program.id,
                    daterange: daterange.id,
                    site: program.site_id,
                  },
                }"
              >
                <button
                  class="mx-2 bg-blue-500"
                  @click="
                    getTwoDimensionalId(daterange.year, program.id + 100, daterange.id)
                  "
                >
                  Add
                </button>
              </router-link>
            </div>
          </td>
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
      programs: [],
      daterange: [],
      classes: [],
    };
  },
  created() {
    // fetch site and week data from API
    //this.fetchSiteData();
    this.fetchWeekData();
    this.fetchClassesData();
    this.fetchProgramData();
  },
  methods: {
    async fetchProgramData() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/programs");
        this.programs = response.data.data;
      } catch (error) {
        console.error(error);
      }
    },
    async fetchWeekData() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/daterange");
        this.daterange = response.data.data;
      } catch (error) {
        console.error(error);
      }
    },
    async fetchClassesData() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/classesall");
        this.classes = response.data.groupedDataa;
      } catch (error) {
        console.error(error);
      }
    },
    getTwoDimensionalId(dateYear, programId, dateId) {
      const twoDimensionalId = `${dateYear}${programId}${dateId}`;
      if (!this.twoDimensionalIds.includes(twoDimensionalId)) {
        this.twoDimensionalIds.push(twoDimensionalId);
        console.log(twoDimensionalId);
      }
    },
  },
};
</script>
