<template>
  <div>
    <header class="w-full bg-white shadow">
      <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
        <h1 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
          Capacity File Dashboard
        </h1>
      </div>
    </header>
    <div class="flex float-right py-8 pr-8">
    </div>
    <div class="py-8">
      <div class="w-full pl-8 pr-8 overflow-x-scroll overflow-y-hidden">
        <table class="w-full text-white table-auto">
          <thead>
            <tr class="text-left bg-orange-600 border-2 border-orange-600 border-solid">
                <th class="px-16 py-1 truncate whitespace-no-wrap border">Site</th>
                <th class="px-10 py-1 truncate whitespace-no-wrap border">Line of Business</th>
                <th class="px-4 py-1 truncate whitespace-no-wrap border">{{ classesData[0]?.date_range }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="classData in classesData[0]?.data" :key="classData.id">
                <td class="px-4 py-1 border">{{ classData.site_name }}</td>
                <td class="px-4 py-1 border">{{ classData.program_name }}</td>
                <td class="px-4 py-1 border">{{ classData.total_target }}</td>
            </tr>
        </tbody>
        
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      classesData: [],
    };
  },
  async mounted() {
    await this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/classesall");
        const data = response.data;
        const flattenedData = Object.values(data).flat();
        this.classesData = flattenedData;
      } catch (error) {
        console.error(error);
      }
    },
  },
};
</script>

<style></style>
