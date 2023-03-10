<template>
  <thead>
    <tr class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
      <th class="px-1 py-2 border" colspan="2">DAVAO CENTRAL</th>
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
    <td class="px-1 py-2 border">COMCAST NED 3P</td>
    <td class="px-1 py-2 border">AT&T BB MULTI SERVICE</td>
    <template v-for="(dvcentral1, weeksStart) in groupedData1" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvcentral1 in dvcentral1" :key="dvcentral1.id">
        <button>{{ dvcentral1.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST NED BILLING</td>
    <template v-for="(dvcentral2, weeksStart) in groupedData2" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvcentral2 in dvcentral2" :key="dvcentral2.id">
        <button>{{ dvcentral2.total_target }}</button>
      </td>
      td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST CEN BULK</td>
    <template v-for="(dvcentral3, weeksStart) in groupedData3" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvcentral3 in dvcentral3" :key="dvcentral3.id">
        <button>{{ dvcentral3.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST CEN SMB/B2B</td>
    <template v-for="(dvcentral4, weeksStart) in groupedData4" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvcentral4 in dvcentral4" :key="dvcentral4.id">
        <button>{{ dvcentral4.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST CEN 3P REPAIR</td>
    <template v-for="(dvcentral5, weeksStart) in groupedData5" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvcentral5 in dvcentral5" :key="dvcentral5.id">
        <button>{{ dvcentral5.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST CEN XM CBM</td>
    <template v-for="(dvcentral6, weeksStart) in groupedData6" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvcentral6 in dvcentral6" :key="dvcentral6.id">
        <button>{{ dvcentral6.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST NAT XM T1</td>
    <template v-for="(dvcentral7, weeksStart) in groupedData7" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvcentral7 in dvcentral7" :key="dvcentral7.id">
        <button>{{ dvcentral7.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST NAT XM T2</td>
    <template v-for="(dvcentral8, weeksStart) in groupedData8" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvcentral8 in dvcentral8" :key="dvcentral8.id">
        <button>{{ dvcentral8.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST NED eCHAT REPAIR</td>
    <template v-for="(dvcentral9, weeksStart) in groupedData9" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="dvcentral9 in dvcentral9" :key="dvcentral9.id">
        <button>{{ dvcentral9.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST NED SMB</td>
    <template v-for="(dvcentral10, weeksStart) in groupedData10" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral10 in dvcentral10"
        :key="dvcentral10.id"
      >
       <button> {{ dvcentral10.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST SMB MOBILE ACTIVATIONS</td>
    <template v-for="(dvcentral11, weeksStart) in groupedData11" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral11 in dvcentral11"
        :key="dvcentral11.id"
      >
       <button> {{ dvcentral11.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST CEN SMB BSC</td>
    <template v-for="(dvcentral12, weeksStart) in groupedData12" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral12 in dvcentral12"
        :key="dvcentral12.id"
      >
       <button> {{ dvcentral12.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST WEST SMB BSC</td>
    <template v-for="(dvcentral13, weeksStart) in groupedData13" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral13 in dvcentral13"
        :key="dvcentral13.id"
      >
       <button> {{ dvcentral13.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST NED SMB BSC</td>
    <template v-for="(dvcentral14, weeksStart) in groupedData14" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral14 in dvcentral14"
        :key="dvcentral14.id"
      >
       <button>{{ dvcentral14.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST DIGITAL CARE</td>
    <template v-for="(dvcentral15, weeksStart) in groupedData15" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral15 in dvcentral15"
        :key="dvcentral15.id"
      >
       <button> {{ dvcentral15.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST CEN MIDMARKET OVN</td>
    <template v-for="(dvcentral16, weeksStart) in groupedData16" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral16 in dvcentral16"
        :key="dvcentral16.id"
      >
       <button> {{ dvcentral16.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">COMCAST CEN MIDMARKET BILLING</td>
    <template v-for="(dvcentral17, weeksStart) in groupedData17" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral17 in dvcentral17"
        :key="dvcentral17.id"
      >
       <button> {{ dvcentral17.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">SHOPHQ IB SALES</td>
    <template v-for="(dvcentral18, weeksStart) in groupedData18" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral18 in dvcentral18"
        :key="dvcentral18.id"
      >
       <button> {{ dvcentral18.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">SHOPHQ SERVICE</td>
    <template v-for="(dvcentral19, weeksStart) in groupedData19" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral19 in dvcentral19"
        :key="dvcentral19.id"
      >
       <button> {{ dvcentral19.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">HERTZ REX</td>
    <template v-for="(dvcentral20, weeksStart) in groupedData20" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral20 in dvcentral20"
        :key="dvcentral20.id"
      >
       <button> {{ dvcentral20.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">HERTZ RES</td>
    <template v-for="(dvcentral21, weeksStart) in groupedData21" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral21 in dvcentral21"
        :key="dvcentral21.id"
      >
       <button> {{ dvcentral21.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">ROKU CARE</td>
    <template v-for="(dvcentral22, weeksStart) in groupedData22" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral22 in dvcentral22"
        :key="dvcentral22.id"
      >
       <button> {{ dvcentral22.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">ROKU DEVICE</td>
    <template v-for="(dvcentral23, weeksStart) in groupedData23" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral23 in dvcentral23"
        :key="dvcentral23.id"
      >
       <button> {{ dvcentral23.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">MODIVCARE</td>
    <template v-for="(dvcentral24, weeksStart) in groupedData24" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral24 in dvcentral24"
        :key="dvcentral24.id"
      >
       <button> {{ dvcentral24.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">DAVAO CENTRAL</td>
    <td class="px-1 py-2 border">ILG TAGALOG</td>
    <template v-for="(dvcentral25, weeksStart) in groupedData25" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="dvcentral25 in dvcentral25"
        :key="dvcentral25.id"
      >
       <button> {{ dvcentral23.total_target }}</button>
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
      groupedData24: [],
      groupedData25: [],
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.getClark();
  },
  methods: {
    async getClark() {
      await axios
        .get("http://127.0.0.1:8000/api/dvcentral")
        .then((response) => {
          this.groupedData1 = response.data.dvcentral1;
          this.groupedData2 = response.data.dvcentral2;
          this.groupedData3 = response.data.dvcentral3;
          this.groupedData4 = response.data.dvcentral4;
          this.groupedData5 = response.data.dvcentral5;
          this.groupedData6 = response.data.dvcentral6;
          this.groupedData7 = response.data.dvcentral7;
          this.groupedData8 = response.data.dvcentral8;
          this.groupedData9 = response.data.dvcentral9;
          this.groupedData10 = response.data.dvcentral10;
          this.groupedData11 = response.data.dvcentral11;
          this.groupedData12 = response.data.dvcentral12;
          this.groupedData13 = response.data.dvcentral13;
          this.groupedData14 = response.data.dvcentral14;
          this.groupedData15 = response.data.dvcentral15;
          this.groupedData16 = response.data.dvcentral16;
          this.groupedData17 = response.data.dvcentral17;
          this.groupedData18 = response.data.dvcentral18;
          this.groupedData19 = response.data.dvcentral19;
          this.groupedData20 = response.data.dvcentral20;
          this.groupedData21 = response.data.dvcentral21;
          this.groupedData22 = response.data.dvcentral22;
          this.groupedData23 = response.data.dvcentral23;
          this.groupedData22 = response.data.dvcentral24;
          this.groupedData23 = response.data.dvcentral25;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
