<template>
  <header class="bg-white p-4 py-0">
    <div class="max-w-screen-xl mx-auto">
      <h2 class="text-3xl font-bold text-gray-900">SR Pending Movement</h2>
    </div>
  </header>
    <div class="bg-gray-100 px-4">
      <div class="mb-4 md:flex md:space-x-2 md:items-center">
        
        <div class="w-full md:w-1/4">
          <input
            v-model="filterStartDate"
            type="date"
            placeholder="Start"
            class="p-2 border rounded-lg w-full"
            @input="updateFilterStartDate"
          />
        </div>
        <div class="w-full md:w-1/4">
          <input
            v-model="filterEndDate"
            type="date"
            placeholder="End by Site"
            class="p-2 border rounded-lg w-full"
            @input="updateFilterEndDate"
          />
        </div>
        
        <div class="w-full md:w-1/4">
          <button
            @click="getSr"
            class="px-4 py-2 bg-blue-500 text-white rounded-lg w-full"
          >
            Filter
          </button>
        </div>
      </div>
    </div>
    <div class="px-4">
      <div
        class="bg-white shadow-md rounded-lg overflow-x-auto overflow-y-auto"
      >
        <table class="min-w-full border-collapse border-2 border-gray-300">
        <thead>
          <tr class="border-4 border-gray-300 px-1 text-center">
            <th
              class="border-2 border-gray-300 px-1">Step</th>
      <th class="border-2 border-gray-300 px-1">Bridgetowne</th>
      <th class="border-2 border-gray-300 px-1">Clark</th>
      <th class="border-2 border-gray-300 px-1">Davao</th>
      <th class="border-2 border-gray-300 px-1">Makati</th>
      <th class="border-2 border-gray-300 px-1">MOA</th>
      <th class="border-2 border-gray-300 px-1">QC North Edsa</th>
        </tr>
    </thead>
    <tbody v-for="(item, index) in sr" :key="index">
      <tr
      class="font-semibold text-black bg-white border-2 border-gray-400 border-solid px-2"
    >
<td class="border-2 border-gray-300 text-left font-semibold">{{item.CombinedStepAppStep}}</td>
<td class="border-2 border-gray-300 text-center font-semibold">{{item.Bridgetowne}}</td>
<td class="border-2 border-gray-300 text-center font-semibold">{{item.Clark}}</td>
<td class="border-2 border-gray-300 text-center font-semibold">{{item.Davao}}</td>
<td class="border-2 border-gray-300 text-center font-semibold">{{item.Makati}}</td>
<td class="border-2 border-gray-300 text-center font-semibold">{{item.MOA}}</td>
<td class="border-2 border-gray-300 text-center font-semibold">{{item['QC North EDSA']}}</td>

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
      sr: [],
      filterStartDate:"",
      filterEndDate:"",
    };
  },
  computed: {
    formattedFilterDate() {
      return this.filterDate
        ? new Date(this.filterDate).toLocaleDateString("en-CA")
        : "";
    },
  },
  mounted() {
    this.getSr();
  },
  methods: {
    updateFilterStartDate(event) {
      this.filterStartDate = event.target.value;
    },

    updateFilterEndDate(event) {
      this.filterEndDate = event.target.value;
    },

    formatDateForInput(date) {
      const formattedDate = new Date(date).toISOString().split("T")[0];
      return formattedDate;
    },
    async getSr() {
  try {
    const token = this.$store.state.token;
    let params = {};

    if (this.filterStartDate && this.filterEndDate) {
      params = {
        filter_date_start: this.filterStartDate,
        filter_date_end: this.filterEndDate,
      };
    }

    const response = await axios.get(
      "http://10.109.2.112:8081/api/sr_compliance",
      {
        params,
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    );

    this.sr = response.data.sr;
    console.log("sr data:", this.sr);
  } catch (error) {
    console.log("Error:", error);
  }
},

  },
};
</script>

<style scoped>

</style>
