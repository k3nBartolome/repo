<!-- eslint-disable vue/valid-v-for -->
<template>
  <thead>
    <tr class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
      <th class="px-1 py-2 border" colspan="2">BRIDGETOWNE</th>
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
    <td class="px-1 py-2 border">BRIDGETOWNE</td>
    <td class="px-1 py-2 border">AT&T MOBILITY</td>
    <template v-for="(bridgetowne1, weeksStart) in groupedData1" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="bridgetowne1 in bridgetowne1"
        :key="bridgetowne1.id"
      >
        {{ bridgetowne1.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">BRIDGETOWNE</td>
    <td class="px-1 py-2 border">AT&T UV Established</td>
    <template v-for="(bridgetowne2, weeksStart) in groupedData2" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="bridgetowne2 in bridgetowne2"
        :key="bridgetowne2.id"
      >
        {{ bridgetowne2.total_target }}
      </td>
      td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">BRIDGETOWNE</td>
    <td class="px-1 py-2 border">Westwood</td>
    <template v-for="(bridgetowne3, weeksStart) in groupedData3" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="bridgetowne3 in bridgetowne3"
        :key="bridgetowne3.id"
      >
        {{ bridgetowne3.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">BRIDGETOWNE</td>
    <td class="px-1 py-2 border">MSI</td>
    <template v-for="(bridgetowne4, weeksStart) in groupedData4" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="bridgetowne4 in bridgetowne4"
        :key="bridgetowne4.id"
      >
        {{ bridgetowne4.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">BRIDGETOWNE</td>
    <td class="px-1 py-2 border">LG - Order Entry (Email)</td>
    <template v-for="(bridgetowne5, weeksStart) in groupedData5" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="bridgetowne5 in bridgetowne5"
        :key="bridgetowne5.id"
      >
        {{ bridgetowne5.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">BRIDGETOWNE</td>
    <td class="px-1 py-2 border">LG - Order Management (Email)</td>
    <template v-for="(bridgetowne6, weeksStart) in groupedData6" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="bridgetowne6 in bridgetowne6"
        :key="bridgetowne6.id"
      >
        {{ bridgetowne6.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">BRIDGETOWNE</td>
    <td class="px-1 py-2 border">LG - Social Media</td>
    <template v-for="(bridgetowne7, weeksStart) in groupedData7" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="bridgetowne7 in bridgetowne7"
        :key="bridgetowne7.id"
      >
        {{ bridgetowne7.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">BRIDGETOWNE</td>
    <td class="px-1 py-2 border">Pindoudou - TEMU</td>
    <template v-for="(bridgetowne8, weeksStart) in groupedData8" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="bridgetowne8 in bridgetowne8"
        :key="bridgetowne8.id"
      >
        {{ bridgetowne8.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">BRIDGETOWNE</td>
    <td class="px-1 py-2 border">ULTRA MINT - MOBILE</td>
    <template v-for="(bridgetowne9, weeksStart) in groupedData9" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="bridgetowne9 in bridgetowne9"
        :key="bridgetowne9.id"
      >
        {{ bridgetowne9.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">BRIDGETOWNE</td>
    <td class="px-1 py-2 border">ULTRA MINT - LIVE CHAT</td>
    <template v-for="(bridgetowne10, weeksStart) in groupedData10" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="bridgetowne10 in bridgetowne10"
        :key="bridgetowne10.id"
      >
        {{ bridgetowne10.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">BRIDGETOWNE</td>
    <td class="px-1 py-2 border">ULTRA MINT - VOICE</td>
    <template v-for="(bridgetowne11, weeksStart) in groupedData11" :key="weeksStart">
      <td
        class="px-1 py-2 border"
        v-for="bridgetowne11 in bridgetowne11"
        :key="bridgetowne11.id"
      >
        {{ bridgetowne11.total_target }}
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
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.getBridgetowne();
  },
  methods: {
    async getBridgetowne() {
      await axios
        .get("http://127.0.0.1:8000/api/bridgetowne")
        .then((response) => {
          this.groupedData1 = response.data.bridgetowne1;
          this.groupedData2 = response.data.bridgetowne2;
          this.groupedData3 = response.data.bridgetowne3;
          this.groupedData4 = response.data.bridgetowne4;
          this.groupedData5 = response.data.bridgetowne5;
          this.groupedData6 = response.data.bridgetowne6;
          this.groupedData7 = response.data.bridgetowne7;
          this.groupedData8 = response.data.bridgetowne8;
          this.groupedData9 = response.data.bridgetowne9;
          this.groupedData10 = response.data.bridgetowne10;
          this.groupedData11 = response.data.bridgetowne11;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
