<!-- eslint-disable vue/valid-v-for -->
<template>
  <thead>
    <tr class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
      <th class="px-1 py-2 border" colspan="2">SM DAVAO</th>
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
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">SM DAVAO</td>
    <td class="px-1 py-2 border">AT&T BB TELCO SERVICE</td>
    <template v-for="(dvsm1, weeksStart) in groupedData1" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvsm1 in dvsm1" :key="dvsm1.id">
      <button>  {{ dvsm1.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">SM DAVAO</td>
    <td class="px-1 py-2 border">AT&T BB PTB MOB</td>
    <template v-for="(dvsm2, weeksStart) in groupedData2" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvsm2 in dvsm2" :key="dvsm2.id">
      <button>  {{ dvsm2.total_target }}</button>
      </td>
      td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">SM DAVAO</td>
    <td class="px-1 py-2 border">DTV VIDEO SERVICE DV</td>
    <template v-for="(dvsm3, weeksStart) in groupedData3" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvsm3 in dvsm3" :key="dvsm3.id">
      <button>  {{ dvsm3.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">SM DAVAO</td>
    <td class="px-1 py-2 border">AT&T MTS</td>
    <template v-for="(dvsm4, weeksStart) in groupedData4" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvsm4 in dvsm4" :key="dvsm4.id">
      <button>  {{ dvsm4.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">SM DAVAO</td>
    <td class="px-1 py-2 border">Q5</td>
    <template v-for="(dvsm5, weeksStart) in groupedData5" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvsm5 in dvsm5" :key="dvsm5.id">
      <button>  {{ dvsm5.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">SM DAVAO</td>
    <td class="px-1 py-2 border">LEGAL ZOOM VOICE</td>
    <template v-for="(dvsm6, weeksStart) in groupedData6" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvsm6 in dvsm6" :key="dvsm6.id">
      <button>  {{ dvsm6.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">SM DAVAO</td>
    <td class="px-1 py-2 border">LEGAL ZOOM CHAT</td>
    <template v-for="(dvsm7, weeksStart) in groupedData7" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvsm7 in dvsm7" :key="dvsm7.id">
      <button>  {{ dvsm7.total_target }}</button>
      </td>
    </template>
  </tbody>
</template>
<script>
import axios from "axios";
export default {
  data() {
    return {
      groupedData1: [],
      groupedData2: [],
      groupedData3: [],
      groupedData4: [],
      groupedData5: [],
      groupedData6: [],
      groupedData7: [],
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.getSmdv();
  },
  methods: {
    async getSmdv() {
      await axios
        .get("http://127.0.0.1:8000/api/dvsm")
        .then((response) => {
          this.groupedData1 = response.data.dvsm1;
          this.groupedData2 = response.data.dvsm2;
          this.groupedData3 = response.data.dvsm3;
          this.groupedData4 = response.data.dvsm4;
          this.groupedData5 = response.data.dvsm5;
          this.groupedData6 = response.data.dvsm6;
          this.groupedData7 = response.data.dvsm7;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
