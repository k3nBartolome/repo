<template>
  <div class="py-8">
    <div class="pl-8 pr-8 overflow-x-auto overflow-y-auto">
      <table class="w-full  table-auto">
        <thead>
          <tr
            class="text-left  border-2 border-orange-600 border-solid"
          >
      <th class="px-1 py-2">Step</th>
      <th class="px-1 py-2">Bridgetowne</th>
      <th class="px-1 py-2">Clark</th>
      <th class="px-1 py-2">Davao</th>
      <th class="px-1 py-2">Makati</th>
      <th class="px-1 py-2">MOA</th>
      <th class="px-1 py-2">QC North Edsa</th>
        </tr>
    </thead>
    <tbody v-for="(item, index) in sr" :key="index">
      <tr
      class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
    >
<td class="px-1 py-2">{{item.CombinedStepAppStep}}</td>
<td class="px-1 py-2">{{item.Bridgetowne}}</td>
<td class="px-1 py-2">{{item.Clark}}</td>
<td class="px-1 py-2">{{item.Davao}}</td>
<td class="px-1 py-2">{{item.Makati}}</td>
<td class="px-1 py-2">{{item.MOA}}</td>
<td class="px-1 py-2">{{item['QC North EDSA']}}</td>

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
    };
  },
  mounted() {
    this.getSr();
  },
  methods: {
    async getSr() {
      try {
        const token = this.$store.state.token;
        const config = {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        };

        const response = await axios.get("http://127.0.0.1:8000/api/sr_compliance", config);
        console.log("Response received:", response.data);

        this.sr = response.data.sr;
        console.log("sr data:", this.sr);

        this.initializeWebDataRocks();
      } catch (error) {
        console.log("Error:", error);
      }
    },
  },
};
</script>

<style scoped>

</style>
