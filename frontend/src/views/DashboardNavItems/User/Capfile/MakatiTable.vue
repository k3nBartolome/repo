<!-- eslint-disable vue/valid-v-for -->
<template>
  <thead>
    <tr class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
      <th class="px-1 py-2 border" colspan="2">MAKATI</th>
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
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">AT&T UV UBIZ</td>
    <template v-for="(makati1, weeksStart) in groupedData1" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati1 in makati1" :key="makati1.id">
        {{ makati1.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">AT&T UV Established</td>
    <template v-for="(makati2, weeksStart) in groupedData2" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati2 in makati2" :key="makati2.id">
        {{ makati2.total_target }}
      </td>
      td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">DTV DIRECT SALES</td>
    <template v-for="(makati3, weeksStart) in groupedData3" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati3 in makati3" :key="makati3.id">
        {{ makati3.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">DTV DIRECT SALES</td>
    <template v-for="(makati4, weeksStart) in groupedData4" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati4 in makati4" :key="makati4.id">
        {{ makati4.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">AT&T CONNECTED CAR</td>
    <template v-for="(makati5, weeksStart) in groupedData5" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati5 in makati5" :key="makati5.id">
        {{ makati5.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">DTV RECONNECTS</td>
    <template v-for="(makati6, weeksStart) in groupedData6" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati6 in makati6" :key="makati6.id">
        {{ makati6.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">DTV MOVERS</td>
    <template v-for="(makati7, weeksStart) in groupedData7" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati7 in makati7" :key="makati7.id">
        {{ makati7.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">FOX NEWS</td>
    <template v-for="(makati8, weeksStart) in groupedData8" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati8 in makati8" :key="makati8.id">
        {{ makati8.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">DTV ILG</td>
    <template v-for="(makati9, weeksStart) in groupedData9" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati9 in makati9" :key="makati9.id">
        {{ makati9.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">ATT ACP</td>
    <template v-for="(makati10, weeksStart) in groupedData10" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati10 in makati10" :key="makati10.id">
        {{ makati10.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">ATT LEGACY</td>
    <template v-for="(makati11, weeksStart) in groupedData11" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati11 in makati11" :key="makati11.id">
        {{ makati11.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">ATT SAVES</td>
    <template v-for="(makati12, weeksStart) in groupedData12" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati12 in makati12" :key="makati12.id">
        {{ makati12.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">ATT NCCD</td>
    <template v-for="(makati13, weeksStart) in groupedData13" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati13 in makati13" :key="makati13.id">
        {{ makati13.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">ATT B2B</td>
    <template v-for="(makati14, weeksStart) in groupedData14" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati14 in makati14" :key="makati14.id">
        {{ makati14.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">AT&T MOBILITY B</td>
    <template v-for="(makati15, weeksStart) in groupedData15" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati15 in makati15" :key="makati15.id">
        {{ makati15.total_target }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">MAKATI</td>
    <td class="px-1 py-2 border">AT&T MOBILITY</td>
    <template v-for="(makati16, weeksStart) in groupedData16" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="makati16 in makati16" :key="makati16.id">
        {{ makati16.total_target }}
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
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.getMakati();
  },
  methods: {
    async getMakati() {
      await axios
        .get("http://127.0.0.1:8000/api/makati")
        .then((response) => {
          this.groupedData1 = response.data.makati1;
          this.groupedData2 = response.data.makati2;
          this.groupedData3 = response.data.makati3;
          this.groupedData4 = response.data.makati4;
          this.groupedData5 = response.data.makati5;
          this.groupedData6 = response.data.makati6;
          this.groupedData7 = response.data.makati7;
          this.groupedData8 = response.data.makati8;
          this.groupedData9 = response.data.makati9;
          this.groupedData10 = response.data.makati10;
          this.groupedData11 = response.data.makati11;
          this.groupedData12 = response.data.makati12;
          this.groupedData13 = response.data.makati13;
          this.groupedData14 = response.data.makati14;
          this.groupedData15 = response.data.makati15;
          this.groupedData16 = response.data.makati16;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
