<!-- eslint-disable vue/valid-v-for -->
<template>
  <thead>
    <tr class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
      <th class="px-1 py-2 border" colspan="2">ROBINSON DAVAO</th>
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
    <td class="px-1 py-2 border">ROBINSON DAVAO</td>
    <td class="px-1 py-2 border">SXM SERVICE</td>
    <template v-for="(dvrob1, weeksStart) in groupedData1" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvrob1 in dvrob1" :key="dvrob1.id">
        {{ dvrob1.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">ROBINSON DAVAO</td>
    <td class="px-1 py-2 border">SXM SAVES</td>
    <template v-for="(dvrob2, weeksStart) in groupedData2" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvrob2 in dvrob2" :key="dvrob2.id">
        {{ dvrob2.total_target }}
      </td>
      td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">ROBINSON DAVAO</td>
    <td class="px-1 py-2 border">NOTION</td>
    <template v-for="(dvrob3, weeksStart) in groupedData3" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvrob3 in dvrob3" :key="dvrob3.id">
        {{ dvrob3.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">ROBINSON DAVAO</td>
    <td class="px-1 py-2 border">AT&T MOBILITY CLG</td>
    <template v-for="(dvrob4, weeksStart) in groupedData4" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvrob4 in dvrob4" :key="dvrob4.id">
        {{ dvrob4.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">ROBINSON DAVAO</td>
    <td class="px-1 py-2 border">MODIVCARE</td>
    <template v-for="(dvrob5, weeksStart) in groupedData5" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvrob5 in dvrob5" :key="dvrob5.id">
        {{ dvrob5.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">ROBINSON DAVAO</td>
    <td class="px-1 py-2 border">ULTRAMINT</td>
    <template v-for="(dvrob6, weeksStart) in groupedData6" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvrob6 in dvrob6" :key="dvrob6.id">
        {{ dvrob6.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">ROBINSON DAVAO</td>
    <td class="px-1 py-2 border">ULTRAMINT LIVECHAT</td>
    <template v-for="(dvrob7, weeksStart) in groupedData7" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvrob7 in dvrob7" :key="dvrob7.id">
        {{ dvrob7.total_target }}
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
    this.getRobdv();
  },
  methods: {
    async getRobdv() {
      await axios
        .get("http://127.0.0.1:8000/api/dvrob")
        .then((response) => {
          this.groupedData1 = response.data.dvrob1;
          this.groupedData2 = response.data.dvrob2;
          this.groupedData3 = response.data.dvrob3;
          this.groupedData4 = response.data.dvrob4;
          this.groupedData5 = response.data.dvrob5;
          this.groupedData6 = response.data.dvrob6;
          this.groupedData7 = response.data.dvrob7;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
