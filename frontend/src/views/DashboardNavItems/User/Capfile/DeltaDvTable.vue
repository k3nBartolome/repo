<!-- eslint-disable vue/valid-v-for -->
<template>
  <thead>
    <tr class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
      <th class="px-1 py-2 border" colspan="2">DAVAO DELTA</th>
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
    <td class="px-1 py-2 border">DAVAO DELTA</td>
    <td class="px-1 py-2 border">DTV TECH - VIDEO TECH</td>
    <template v-for="(dvdelta1, weeksStart) in groupedData1" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvdelta1 in dvdelta1" :key="dvdelta1.id">
        {{ dvdelta1.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO DELTA</td>
    <td class="px-1 py-2 border">GREAT JONES</td>
    <template v-for="(dvdelta2, weeksStart) in groupedData2" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvdelta2 in dvdelta2" :key="dvdelta2.id">
        {{ dvdelta2.total_target }}
      </td>
      td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO DELTA</td>
    <td class="px-1 py-2 border">AT&T MSS</td>
    <template v-for="(dvdelta3, weeksStart) in groupedData3" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvdelta3 in dvdelta3" :key="dvdelta3.id">
        {{ dvdelta3.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO DELTA</td>
    <td class="px-1 py-2 border">FACEBOOK META</td>
    <template v-for="(dvdelta4, weeksStart) in groupedData4" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvdelta4 in dvdelta4" :key="dvdelta4.id">
        {{ dvdelta4.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO DELTA</td>
    <td class="px-1 py-2 border">PAYPAL APFS</td>
    <template v-for="(dvdelta5, weeksStart) in groupedData5" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvdelta5 in dvdelta5" :key="dvdelta5.id">
        {{ dvdelta5.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO DELTA</td>
    <td class="px-1 py-2 border">PAYPAL CS</td>
    <template v-for="(dvdelta6, weeksStart) in groupedData6" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvdelta6 in dvdelta6" :key="dvdelta6.id">
        {{ dvdelta6.total_target }}
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
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.getDeltadv();
  },
  methods: {
    async getDeltadv() {
      await axios
        .get("http://127.0.0.1:8000/api/dvdelta")
        .then((response) => {
          this.groupedData1 = response.data.dvdelta1;
          this.groupedData2 = response.data.dvdelta2;
          this.groupedData3 = response.data.dvdelta3;
          this.groupedData4 = response.data.dvdelta4;
          this.groupedData5 = response.data.dvdelta5;
          this.groupedData6 = response.data.dvdelta6;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
