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
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">AT&T BB MULTI SERVICE</td>
    <template v-for="(clark1, weeksStart) in groupedData1" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark1 in clark1" :key="clark1.id">
        {{ clark1.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">EBAY BUY PHONE</td>
    <template v-for="(clark2, weeksStart) in groupedData2" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark2 in clark2" :key="clark2.id">
        {{ clark2.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">EBAY BUY CHAT</td>
    <template v-for="(clark3, weeksStart) in groupedData3" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark3 in clark3" :key="clark3.id">
        {{ clark3.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">EBAY SELL PHONE</td>
    <template v-for="(clark4, weeksStart) in groupedData4" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark4 in clark4" :key="clark4.id">
        {{ clark4.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">EBAY SELL CHAT</td>
    <template v-for="(clark5, weeksStart) in groupedData5" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark5 in clark5" :key="clark5.id">
        {{ clark5.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">ZINUS</td>
    <template v-for="(clark6, weeksStart) in groupedData6" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark6 in clark6" :key="clark6.id">
        {{ clark6.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">MODIVCARE FULL TIME</td>
    <template v-for="(clark7, weeksStart) in groupedData7" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark7 in clark7" :key="clark7.id">
        {{ clark7.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">COMCAST SPECIAL PROJECT</td>
    <template v-for="(clark8, weeksStart) in groupedData8" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark8 in clark8" :key="clark8.id">
        {{ clark8.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">LUMEN/CRIS PHQ1</td>
    <template v-for="(clark9, weeksStart) in groupedData9" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark9 in clark9" :key="clark9.id">
        {{ clark9.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">ENS Q1</td>
    <template v-for="(clark10, weeksStart) in groupedData10" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark10 in clark10" :key="clark10.id">
        {{ clark10.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">CRIS Q2A</td>
    <template v-for="(clark11, weeksStart) in groupedData11" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark11 in clark11" :key="clark11.id">
        {{ clark11.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">CRIS Q2B</td>
    <template v-for="(clark12, weeksStart) in groupedData12" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark12 in clark12" :key="clark12.id">
        {{ clark12.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">FIS</td>
    <template v-for="(clark13, weeksStart) in groupedData13" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark13 in clark13" :key="clark13.id">
        {{ clark13.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">BRIGHTSPEED T1</td>
    <template v-for="(clark14, weeksStart) in groupedData14" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark14 in clark14" :key="clark14.id">
        {{ clark14.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">BRIGHTSPEED 2A</td>
    <template v-for="(clark15, weeksStart) in groupedData15" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark15 in clark15" :key="clark15.id">
        {{ clark15.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">BRIGHTSPEED ACQ</td>
    <template v-for="(clark16, weeksStart) in groupedData16" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark16 in clark16" :key="clark16.id">
        {{ clark16.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">COMCAST XM T1</td>
    <template v-for="(clark17, weeksStart) in groupedData17" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark17 in clark17" :key="clark17.id">
        {{ clark17.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">COMCAST NED 3P</td>
    <template v-for="(clark18, weeksStart) in groupedData18" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark18 in clark18" :key="clark18.id">
        {{ clark18.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">COMCAST NED BILLING</td>
    <template v-for="(clark19, weeksStart) in groupedData19" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark19 in clark19" :key="clark19.id">
        {{ clark19.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">COMCAST XM BILLING</td>
    <template v-for="(clark20, weeksStart) in groupedData20" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark20 in clark20" :key="clark20.id">
        {{ clark20.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">HYOSUNG</td>
    <template v-for="(clark21, weeksStart) in groupedData21" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark21 in clark21" :key="clark21.id">
        {{ clark21.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">DOORDASH</td>
    <template v-for="(clark22, weeksStart) in groupedData22" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark22 in clark22" :key="clark22.id">
        {{ clark22.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">DUN & BRADSTREET</td>
    <template v-for="(clark23, weeksStart) in groupedData23" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark23 in clark23" :key="clark23.id">
        {{ clark23.total_target }}
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
      groupedData21: [],
      groupedData22: [],
      groupedData23: [],
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
          this.groupedData21 = response.data.clark21;
          this.groupedData22 = response.data.clark22;
          this.groupedData23 = response.data.clark23;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
