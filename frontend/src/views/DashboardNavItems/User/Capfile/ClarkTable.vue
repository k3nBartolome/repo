<template>
  <div class="py-8 ">
    <div class="w-full pl-8 pr-8 overflow-x-scroll overflow-y-auto">
      <table class="w-full text-white table-auto">
        <thead>
          <tr
            class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
          >
            <th class="px-1 py-2 border" colspan="2">CLARK</th>
            <th class="px-1 py-2 border"></th>
            <th class="px-4 py-1 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
            <th class="px-1 py-2 border"></th>
          </tr> 
        </thead>
        <tbody>
          <tr v-for="(classes, weeksStart) in groupedData" :key="weeksStart"
          class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
        >
          <td class="px-1 py-2 border border-black" v-for="classA in classes" :key="classA.id">{{classA.site_name}}</td>
          <td class="px-1 py-2 border border-black" v-for="classA in classes" :key="classA.id">{{classA.program_name}}</td>
          <td class="px-1 py-2 border border-black" v-for="classA in classes" :key="classA.id">{{classA.total_target}}</td>
          <td class="px-4 py-1 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
          <td class="px-1 py-2 border border-black"></td>
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
      groupedData: [],
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.getClark();
  },
  methods: {
    async getClark() {
      await axios
        .get("http://127.0.0.1:8000/api/clark")
        .then((response) => {
          this.groupedData = response.data.classes;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>