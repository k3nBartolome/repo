<template>
  <div class="overflow-x-auto">
    <table class="table-auto border border-black">
      <thead>
        <tr>
          <th class="px-4">Programs</th>
          <th
            v-for="daterange in daterange"
            :key="daterange.id"
            class="px-4 truncate  py-1"
          >
            {{ daterange.date_range }}
          </th>
        </tr>
      </thead>
      <tbody class="overflow-y-auto">
        <tr v-for="program in programs" :key="program.id">
          <td class=" px-4 py-1 truncate border border-black">{{ program.name }}</td>
          <td v-for="date in daterange" :key="date.id" class="w-1/4 px-2 py-1 border border-black">
            <button
              type="button"
              v-on:click="getTwoDimensionalId(date.year,program.id + 100, date.id)"
              class="bg-red-500 px-4"
            >target
            </button>
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
      programs: [], // array of site objects
      daterange: [], // array of week objects
      twoDimensionalIds: [], // array to hold two-dimensional IDs
    };
  },
  created() {
    // fetch site and week data from API
    this.fetchSiteData();
    this.fetchWeekData();
  },
  methods: {
    async fetchSiteData() {
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
    getTwoDimensionalId(dateYear,programId, dateId) {
      const twoDimensionalId = `${dateYear}${programId}${dateId}`;
      if (!this.twoDimensionalIds.includes(twoDimensionalId)) {
        this.twoDimensionalIds.push(twoDimensionalId);
        console.log(twoDimensionalId);
        // do something with the two-dimensional ID here
      }
    },
  },
};
</script>
