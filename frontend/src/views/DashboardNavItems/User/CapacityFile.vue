<template>
  <table class="min-w-full divide-y divide-gray-200">
    <thead>
      <tr>
        <th
          class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50"
        >
          Site
        </th>
        <th
          class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50"
        >
          Program
        </th>
        <th
          class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50"
        >
          Date Range
        </th>
        <th
          class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50"
        >
          Status
        </th>
      </tr>
    </thead>
    <tbody
      class="truncate bg-white divide-y divide-gray-200"
      v-for="(siteClasses, siteName) in classes"
      :key="siteName"
    >
      <tr v-for="(programClasses, programName) in siteClasses" :key="programName">
        <template
          v-for="(dateRangeClasses, dateRange) in programClasses"
          :key="dateRange"
        >
            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
             
            </td>
            <td class="px-6 py-4 text-sm text-gray-500 truncate">
              
            </td>
            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
            </td>
            <td v-for=" Classe in dateRangeClasses" :key="Classe" class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
              {{ Classe }}
            </td>
        </template>
      </tr>
    </tbody>
  </table>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      classes: {},
    };
  },
  mounted() {
    this.fetchClassesData();
  },
  methods: {
    async fetchClassesData() {
      axios
        .get("http://127.0.0.1:8000/api/classesall")
        .then((response) => {
          this.classes = response.data;
          console.log(this.classes);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getClasses(programName, siteName, dateRangeId) {
      if (!this.classes[siteName] || !this.classes[siteName][programName]) {
        return null;
      }

      return this.classes[siteName][programName][dateRangeId] || null;
    },
  },
};
</script>
