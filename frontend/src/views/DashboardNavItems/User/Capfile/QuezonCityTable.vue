<!-- eslint-disable vue/valid-v-for -->
<template>
  <thead>
    <tr class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
      <th class="px-1 py-2 border" colspan="2">QUEZON CITY</th>
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
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">AT&T BB PTB MOB</td>
    <template v-for="(quezoncity1, weeksStart) in groupedData1" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity1 in quezoncity1"
        :key="quezoncity1.id"
      >
       <button> {{ quezoncity1.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">BB LOYALTY</td>
    <template v-for="(quezoncity2, weeksStart) in groupedData2" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity2 in quezoncity2"
        :key="quezoncity2.id"
      >
       <button> {{ quezoncity2.total_target }}</button>
      </td>
      td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">AT&T BB + TELCO SERVICE</td>
    <template v-for="(quezoncity3, weeksStart) in groupedData3" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity3 in quezoncity3"
        :key="quezoncity3.id"
      >
       <button> {{ quezoncity3.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">AT&T MSS</td>
    <template v-for="(quezoncity4, weeksStart) in groupedData4" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity4 in quezoncity4"
        :key="quezoncity4.id"
      >
       <button> {{ quezoncity4.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">OCEAN X SALES PART TIME</td>
    <template v-for="(quezoncity5, weeksStart) in groupedData5" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity5 in quezoncity5"
        :key="quezoncity5.id"
      >
       <button> {{ quezoncity5.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">OCEAN X SALES FULL TIME</td>
    <template v-for="(quezoncity6, weeksStart) in groupedData6" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity6 in quezoncity6"
        :key="quezoncity6.id"
      >
       <button> {{ quezoncity6.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">OCEAN X CS</td>
    <template v-for="(quezoncity7, weeksStart) in groupedData7" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity7 in quezoncity7"
        :key="quezoncity7.id"
      >
       <button> {{ quezoncity7.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">ALIEXPRESS EN</td>
    <template v-for="(quezoncity8, weeksStart) in groupedData8" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity8 in quezoncity8"
        :key="quezoncity8.id"
      >
       <button> {{ quezoncity8.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">ALIEXPRESS SOS1 (CHOICE STORE)</td>
    <template v-for="(quezoncity9, weeksStart) in groupedData9" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity9 in quezoncity9"
        :key="quezoncity9.id"
      >
       <button> {{ quezoncity9.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">ALIEXPRESS SOS2 (STORE SERVICE)</td>
    <template v-for="(quezoncity10, weeksStart) in groupedData10" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity10 in quezoncity10"
        :key="quezoncity10.id"
      >
       <button> {{ quezoncity10.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">AT&T UV ESTABLISHED</td>
    <template v-for="(quezoncity11, weeksStart) in groupedData11" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity11 in quezoncity11"
        :key="quezoncity11.id"
      >
       <button> {{ quezoncity11.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">AT&T MSS</td>
    <template v-for="(quezoncity12, weeksStart) in groupedData12" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity12 in quezoncity12"
        :key="quezoncity12.id"
      >
       <button> {{ quezoncity12.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">OVERSTOCK VOICE</td>
    <template v-for="(quezoncity13, weeksStart) in groupedData13" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity13 in quezoncity13"
        :key="quezoncity13.id"
      >
       <button> {{ quezoncity13.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">OVERSTOCK MSS</td>
    <template v-for="(quezoncity14, weeksStart) in groupedData14" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity14 in quezoncity14"
        :key="quezoncity14.id"
      >
       <button> {{ quezoncity14.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">ROCKET CS</td>
    <template v-for="(quezoncity15, weeksStart) in groupedData15" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity15 in quezoncity15"
        :key="quezoncity15.id"
      >
       <button> {{ quezoncity15.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">PAYPAL CSIS</td>
    <template v-for="(quezoncity16, weeksStart) in groupedData16" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity16 in quezoncity16"
        :key="quezoncity16.id"
      >
       <button> {{ quezoncity16.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">PAYPAL APFS</td>
    <template v-for="(quezoncity17, weeksStart) in groupedData17" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity17 in quezoncity17"
        :key="quezoncity17.id"
      >
       <button> {{ quezoncity17.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">COMCAST NED X1(3P)</td>
    <template v-for="(quezoncity18, weeksStart) in groupedData18" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity18 in quezoncity18"
        :key="quezoncity18.id"
      >
       <button> {{ quezoncity18.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">COMCAST NED BILLING</td>
    <template v-for="(quezoncity19, weeksStart) in groupedData19" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity19 in quezoncity19"
        :key="quezoncity19.id"
      >
       <button> {{ quezoncity19.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">COMCAST WEST BILLING</td>
    <template v-for="(quezoncity20, weeksStart) in groupedData20" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity20 in quezoncity20"
        :key="quezoncity20.id"
      >
       <button> {{ quezoncity20.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">COMCAST CENTRAL</td>
    <template v-for="(quezoncity21, weeksStart) in groupedData21" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity21 in quezoncity21"
        :key="quezoncity21.id"
      >
       <button> {{ quezoncity21.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">AT&T MOBILITY A</td>
    <template v-for="(quezoncity22, weeksStart) in groupedData22" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity22 in quezoncity22"
        :key="quezoncity22.id"
      >
       <button> {{ quezoncity22.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">QUEZON CITY</td>
    <td class="px-1 py-2 border">AT&T MOBILITY B</td>
    <template v-for="(quezoncity23, weeksStart) in groupedData23" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="quezoncity23 in quezoncity23"
        :key="quezoncity23.id"
      >
       <button> {{ quezoncity23.total_target }}</button>
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
    this.getQuezonCity();
  },
  methods: {
    async getQuezonCity() {
      await axios
        .get("http://127.0.0.1:8000/api/quezoncity")
        .then((response) => {
          this.groupedData1 = response.data.quezoncity1;
          this.groupedData2 = response.data.quezoncity2;
          this.groupedData3 = response.data.quezoncity3;
          this.groupedData4 = response.data.quezoncity4;
          this.groupedData5 = response.data.quezoncity5;
          this.groupedData6 = response.data.quezoncity6;
          this.groupedData7 = response.data.quezoncity7;
          this.groupedData8 = response.data.quezoncity8;
          this.groupedData9 = response.data.quezoncity9;
          this.groupedData10 = response.data.quezoncity10;
          this.groupedData11 = response.data.quezoncity11;
          this.groupedData12 = response.data.quezoncity12;
          this.groupedData13 = response.data.quezoncity13;
          this.groupedData14 = response.data.quezoncity14;
          this.groupedData15 = response.data.quezoncity15;
          this.groupedData16 = response.data.quezoncity16;
          this.groupedData17 = response.data.quezoncity17;
          this.groupedData18 = response.data.quezoncity18;
          this.groupedData19 = response.data.quezoncity19;
          this.groupedData20 = response.data.quezoncity20;
          this.groupedData21 = response.data.quezoncity21;
          this.groupedData22 = response.data.quezoncity22;
          this.groupedData23 = response.data.quezoncity23;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
