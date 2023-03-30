<template>
  <div class="overflow-x-auto">
    <table class="table-auto border border-black">
      <thead>
        <tr>
          <th class="px-4">Programs</th>
          <th
            v-for="daterange in daterange"
            :key="daterange.id"
            class="px-4 truncate py-1"
          >
            {{ daterange.date_range }}
          </th>
        </tr>
      </thead>
      <tbody class="overflow-y-auto">
        <tr v-for="program in programs" :key="program.id">
          <td class="px-4 py-1 truncate border border-black">{{ program.name }}</td>
          <td
            v-for="date in daterange"
            :key="date.id"
            class="w-1/4 px-2 py-1 border border-black"
          >
            <button
              type="button"
              @click="showButtons"
              class="bg-white px-4 w-full h-full"
            >
              {{ total_target }}
            </button>
            <div  class="flex items-center">
              <div v-if="total_target === 0">
                <router-link :to="`/addcapfile/${daterange.year,program.id + 100,daterange.id}`"
                  ><button class="mx-2 bg-blue-500" @click="getTwoDimensionalId(daterange.year,program.id + 100,daterange.id)">
                    Add
                  </button></router-link
                >
              </div>
            </div>
              <!-- <div v-else>
                <router-link :to="`/pushbackcapfile/${clark1Item.id}`"
                  ><button
                    class="mx-2 bg-green-500 w-22"
                    @click="getTwoDimensionalId(daterange.year,program.id + 100,daterange.id)"
                  >
                    Pushed Back
                  </button></router-link
                >
                  ><button class="mx-2 bg-red-500 w-22" @click="getTwoDimensionalId(daterange.year,program.id + 100,daterange.id)">
                    Cancel
                  </button></router-link
                >
              </div>
            </div> -->
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
      twoDimensionalIds: [],
      selectedCell: null,
      modalVisible: false,
      total_target: 0,
    };
  },
  created() {
    // fetch site and week data from API
    this.fetchSiteData();
    this.fetchWeekData();
  },
  methods: {
    closeModal() {
      this.modalVisible = false;
      this.selectedCell = null;
    },

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
    getTwoDimensionalId(programId, dateId) {
      const twoDimensionalId = `${programId}${dateId}`;
      if (!this.twoDimensionalIds.includes(twoDimensionalId)) {
        this.twoDimensionalIds.push(twoDimensionalId);
        console.log(twoDimensionalId);
      }
    },
  },
};
</script>
