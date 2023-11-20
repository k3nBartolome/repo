<template>
  <div class="px-8 align-center">
    <div class="px-8 align-center">
      <table class="border-2 border-black align-center px-2">
        <thead>
          <tr class="border-4 border-black px-2">
            <th class="border-2 border-black px-2">Site Name</th>
            <th class="border-2 border-black px-2">Program Name</th>
            <th class="border-2 border-black px-2">January</th>
            <th class="border-2 border-black px-2">February</th>
            <th class="border-2 border-black px-2">March</th>
            <th class="border-2 border-black px-2">April</th>
            <th class="border-2 border-black px-2">May</th>
            <th class="border-2 border-black px-2">June</th>
            <th class="border-2 border-black px-2">July</th>
            <th class="border-2 border-black px-2">August</th>
            <th class="border-2 border-black px-2">September</th>
            <th class="border-2 border-black px-2">October</th>
            <th class="border-2 border-black px-2">November</th>
            <th class="border-2 border-black px-2">December</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="(item, index) in classes" :key="index">
            <tr v-for="(item1, index1) in item" :key="index1">
              <td class="border-4 border-black truncate px-2 font-semibold">
                {{ index }}
              </td>
              <td class="border-4 border-black truncate px-2 font-semibold">
                {{ index1 }}
              </td>
              <template v-for="(item2, index2) in item1" :key="index2">
                <template v-for="(item3, index3) in item2" :key="index3">
                  <td class="border-2 border-black text-center font-semibold">
                    {{ item3 }}
                  </td>
                </template>
              </template>
            </tr>
          </template>

          <td
            class="border-4 border-black truncate px-2 text-center font-bold"
            colspan="2"
          >
            Grand Total
          </td>
          <template v-for="(total, gtotal) in grandTotal" :key="gtotal">
            <td class="border-2 border-black text-center font-bold">
              {{ total }}
            </td>
          </template>
        </tbody>
      </table>
      <table class="border-2 border-black align-center px-2">
        <thead>
          <tr class="border-4 border-black px-2">
            <th class="border-2 border-black px-2">Total</th>
          </tr>
        </thead>
        <tbody>
        
          <template v-for="(total, gtotal) in grandTotal2" :key="gtotal">
            <tr v-for="(total2, gtotal2) in total" :key="gtotal2">
              <td
                class="border-4 border-black truncate px-2 text-center font-bold"
                colspan="2"
              >
                {{ total2 }}
              </td>
            </tr>
          </template>
        
        </tbody>
      </table>
    </div>
  </div>
  
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      classes: [],
      grandTotal: [],
      grandTotal2: [],
    };
  },
  computed: {},
  mounted() {
    this.getClassesAll();
  },
  methods: {
    async getClassesAll() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/classesdashboard",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.classes = response.data.classes;
          this.grandTotal = response.data.grandTotal;
          this.grandTotal2 = response.data.grandTotal2;
        } else {
          console.error(
            "Error fetching classes. Status code:",
            response.status
          );
        }
      } catch (error) {
        console.error("An error occurred:", error);
      }
    },
  },
};
</script>
