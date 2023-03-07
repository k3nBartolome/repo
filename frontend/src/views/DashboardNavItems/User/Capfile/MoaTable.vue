<!-- eslint-disable vue/valid-v-for -->
<template>
  <thead>
    <tr class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
      <th class="px-1 py-2 border" colspan="2">MOA</th>
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
    <td class="px-1 py-2 border">MOA</td>
    <td class="px-1 py-2 border">DTV VIDEO SERVICE</td>
    <template v-for="(moa1, weeksStart) in groupedData1" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="moa1 in moa1" :key="moa1.id">
        {{ moa1.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MOA</td>
    <td class="px-1 py-2 border">DTV VIDEO SERVICE FIBER</td>
    <template v-for="(moa2, weeksStart) in groupedData2" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="moa2 in moa2" :key="moa2.id">
        {{ moa2.total_target }}
      </td>
      td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MOA</td>
    <td class="px-1 py-2 border">AT&T MTS</td>
    <template v-for="(moa3, weeksStart) in groupedData3" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="moa3 in moa3" :key="moa3.id">
        {{ moa3.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MOA</td>
    <td class="px-1 py-2 border">ATT TRIAGE</td>
    <template v-for="(moa4, weeksStart) in groupedData4" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="moa4 in moa4" :key="moa4.id">
        {{ moa4.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MOA</td>
    <td class="px-1 py-2 border">AT&T MOBILITY</td>
    <template v-for="(moa5, weeksStart) in groupedData5" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="moa5 in moa5" :key="moa5.id">
        {{ moa5.total_target }}
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
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.getMoa();
  },
  methods: {
    async getMoa() {
      await axios
        .get("http://127.0.0.1:8000/api/moa")
        .then((response) => {
          this.groupedData1 = response.data.moa1;
          this.groupedData2 = response.data.moa2;
          this.groupedData3 = response.data.moa3;
          this.groupedData4 = response.data.moa4;
          this.groupedData5 = response.data.moa5;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
