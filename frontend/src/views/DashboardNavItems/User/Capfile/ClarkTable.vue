<template>
  <thead>
    <tr class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
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
  <tbody
    v-for="(clark1, weeksStart) in groupedData1"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">AT&T BB MULTI SERVICE</td>
    <td class="px-1 py-2 border border" v-for="clark1 in clark1" :key="clark1.id">
      <button>{{ clark1.total_target }}</button>
    </td>
  </tbody>
  <tbody
    v-for="(clark2, weeksStart) in groupedData2"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">EBAY BUY PHONE</td>
    <td class="px-1 py-2 border border" v-for="clark2 in clark2" :key="clark2.id">
      {{ clark2.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark3, weeksStart) in groupedData3"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">EBAY BUY CHAT</td>
    <td class="px-1 py-2 border border" v-for="clark3 in clark3" :key="clark3.id">
      {{ clark3.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark4, weeksStart) in groupedData4"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">EBAY SELL PHONE</td>
    <td class="px-1 py-2 border border" v-for="clark4 in clark4" :key="clark4.id">
      {{ clark4.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark5, weeksStart) in groupedData5"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">EBAY SELL CHAT</td>
    <td class="px-1 py-2 border border" v-for="clark5 in clark5" :key="clark5.id">
      {{ clark5.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark6, weeksStart) in groupedData6"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">ZINUS</td>
    <td class="px-1 py-2 border border" v-for="clark6 in clark6" :key="clark6.id">
      {{ clark6.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark7, weeksStart) in groupedData7"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">MODIVCARE FULL TIME</td>
    <td class="px-1 py-2 border border" v-for="clark7 in clark7" :key="clark7.id">
      {{ clark7.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark8, weeksStart) in groupedData8"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">COMCAST SPECIAL PROJECT</td>
    <td class="px-1 py-2 border border" v-for="clark8 in clark8" :key="clark8.id">
      {{ clark8.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark9, weeksStart) in groupedData9"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">LUMEN/CRIS PHQ1</td>
    <td class="px-1 py-2 border border" v-for="clark9 in clark9" :key="clark9.id">
      {{ clark9.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark10, weeksStart) in groupedData10"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">ENS Q1</td>
    <td class="px-1 py-2 border border" v-for="clark10 in clark10" :key="clark10.id">
      {{ clark10.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark11, weeksStart) in groupedData11"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">CRIS Q2A</td>
    <td class="px-1 py-2 border border" v-for="clark11 in clark11" :key="clark11.id">
      {{ clark11.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark12, weeksStart) in groupedData12"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">CRIS Q2B</td>
    <td class="px-1 py-2 border border" v-for="clark12 in clark12" :key="clark12.id">
      {{ clark12.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark13, weeksStart) in groupedData13"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">FIS</td>
    <td class="px-1 py-2 border border" v-for="clark13 in clark13" :key="clark13.id">
      {{ clark13.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark14, weeksStart) in groupedData14"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">BRIGHTSPEED T1</td>
    <td class="px-1 py-2 border border" v-for="clark14 in clark14" :key="clark14.id">
      {{ clark14.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark15, weeksStart) in groupedData15"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">BRIGHTSPEED 2A</td>
    <td class="px-1 py-2 border border" v-for="clark15 in clark15" :key="clark15.id">
      {{ clark15.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark16, weeksStart) in groupedData16"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">BRIGHTSPEED 2A</td>
    <td class="px-1 py-2 border border" v-for="clark16 in clark16" :key="clark16.id">
      {{ clark16.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark17, weeksStart) in groupedData17"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">COMCAST XM T1</td>
    <td class="px-1 py-2 border border" v-for="clark17 in clark17" :key="clark17.id">
      {{ clark17.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark18, weeksStart) in groupedData18"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">COMCAST NED 3P</td>
    <td class="px-1 py-2 border border" v-for="clark18 in clark18" :key="clark18.id">
      {{ clark18.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark19, weeksStart) in groupedData19"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">COMCAST NED BILLING</td>
    <td class="px-1 py-2 border border" v-for="clark19 in clark19" :key="clark19.id">
      {{ clark19.total_target }}
    </td>
  </tbody>
  <tbody
    v-for="(clark20, weeksStart) in groupedData20"
    :key="weeksStart"
    class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
  >
    <td class="px-1 py-2 border border">CLARK</td>
    <td class="px-1 py-2 border border">COMCAST DIGITAL CARE</td>
    <td class="px-1 py-2 border border" v-for="clark20 in clark20" :key="clark20.id">
      {{ clark20.total_target }}
    </td>
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
      groupedData8: [],
      groupedData9: [],
      groupedData10: [],
      groupedData11: [],
      groupedData12: [],
      groupedData13: [],
      groupedData14: [],
      groupedData15: [],
      groupedData16: [],
      groupedData17: [],
      groupedData18: [],
      groupedData19: [],
      groupedData20: [],
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
          this.groupedData1 = response.data.clark1;
          this.groupedData2 = response.data.clark2;
          this.groupedData3 = response.data.clark3;
          this.groupedData4 = response.data.clark4;
          this.groupedData5 = response.data.clark5;
          this.groupedData6 = response.data.clark6;
          this.groupedData7 = response.data.clark7;
          this.groupedData8 = response.data.clark8;
          this.groupedData9 = response.data.clark9;
          this.groupedData10 = response.data.clark10;
          this.groupedData11 = response.data.clark11;
          this.groupedData12 = response.data.clark12;
          this.groupedData13 = response.data.clark13;
          this.groupedData14 = response.data.clark14;
          this.groupedData15 = response.data.clark15;
          this.groupedData16 = response.data.clark16;
          this.groupedData17 = response.data.clark17;
          this.groupedData18 = response.data.clark18;
          this.groupedData19 = response.data.clark19;
          this.groupedData20 = response.data.clark20;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
