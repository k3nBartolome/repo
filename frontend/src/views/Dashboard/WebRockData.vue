<template>
  <div class="container mx-auto mt-4 p-4 pt-0 pb-2">
    <div class="mb-4 md:flex md:space-x-2 md:items-center py-0">
      <div class="w-full md:w-1/3 mt-4 md:mt-0">
        <input
          v-model="filterStartDate"
          type="date"
          placeholder="Start"
          class="px-4 py-2 border rounded-lg w-full bg-gray-100"
          @input="updateFilterStartDate"
        />
      </div>

      <div class="w-full md:w-1/3 mt-4 md:mt-0">
        <input
          v-model="filterEndDate"
          type="date"
          placeholder="End by Site"
          class="px-4 py-2 border rounded-lg w-full bg-gray-100"
          @input="updateFilterEndDate"
        />
      </div>

      <div class="w-full md:w-1/3 mt-4 md:mt-0">
        <button
          @click="getSr"
          class="px-4 py-2 bg-blue-500 text-white rounded-lg w-full"
        >
          Filter
        </button>
      </div>
    </div>
  </div>

  <div class="px-4 pb-4 pt-0">
    <div class="bg-white shadow-md rounded-lg overflow-x-auto overflow-y-auto">
      <table class="min-w-full border-collapse border-2 border-gray-300">
        <thead>
          <tr class="border-b-4 border-gray-300 bg-gray-100 text-center">
            <th class="border-4 border-gray-300 px-4 py-2">Step</th>
            <th class="border-2 border-gray-300 px-4 py-2">Bridgetowne</th>
            <th class="border-2 border-gray-300 px-4 py-2">Clark</th>
            <th class="border-2 border-gray-300 px-4 py-2">Davao</th>
            <th class="border-2 border-gray-300 px-4 py-2">Makati</th>
            <th class="border-2 border-gray-300 px-4 py-2">MOA</th>
            <th class="border-2 border-gray-300 px-4 py-2">QC North Edsa</th>
            <th class="border-4 border-gray-300 px-4 py-2">Total</th>
          </tr>
        </thead>
        <tbody v-for="(item, index) in sr" :key="index">
          <tr
            class=""
            :class="{
              'border-4 border-gray-300 px-4 py-2 text-left font-bold bg-blue-300 text-lg':
                item.Step,
              'border-4 border-gray-300 px-4 py-2 text-left font-semibold text-sm':
                item.AppStep,
            }"
          >
            <td
              class=""
              :class="{
                'border-4 border-gray-300 px-4 py-2 text-left font-bold bg-blue-300':
                  item.Step,
                'border-4 border-gray-300 px-4 py-2 text-left font-semibold':
                  item.AppStep,
              }"
            >
              {{ item.Step || item.AppStep }}
            </td>

            <td class="border-gray-300 border-2 px-4 py-2 text-center">
              {{ item.Bridgetowne }}
            </td>
            <td class="border-gray-300 border-2 px-4 py-2 text-center">
              {{ item.Clark }}
            </td>
            <td class="border-gray-300 border-2 px-4 py-2 text-center">
              {{ item.Davao }}
            </td>
            <td class="border-gray-300 border-2 px-4 py-2 text-center">
              {{ item.Makati }}
            </td>
            <td class="border-gray-300 border-2 px-4 py-2 text-center">
              {{ item.MOA }}
            </td>
            <td class="border-gray-300 border-2 px-4 py-2 text-center">
              {{ item["QC North EDSA"] }}
            </td>
            <td
              class="border-4 border-gray-300 px-4 py-2 text-center font-semibold"
            >
              {{ item["TotalCount"] }}
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
      sr: [],
      filterStartDate: "",
      filterEndDate: "",
      showAppStep: true,
      minDate: [],
      maxDate: [],
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
    this.getDates();
  },
  methods: {
    async getDates() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/sr_date", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.filterStartDate = response.data.minDate;
          this.filterEndDate = response.data.maxDate;
          console.log(response.data.minDate);
          console.log(response.data.maxDate);
        } else {
          console.log("Error fetching Date");
        }
      } catch (error) {
        console.log(error);
      }
    },
    toggleAppStep(item) {
      if (this.showAppStep) {
        console.log("Showing item.Step:", item.Step);
      } else {
        console.log("Showing item.AppStep:", item.AppStep);
      }
      this.showAppStep = !this.showAppStep;
    },
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
          "http://127.0.0.1:8000/api/sr_compliance",
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

<style scoped></style>
