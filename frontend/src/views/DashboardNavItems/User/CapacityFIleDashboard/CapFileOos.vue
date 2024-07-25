<template>
  <div v-if="isLoading" class="loader">
    <div aria-label="Loading..." role="status" class="loader">
      <svg class="icon" viewBox="0 0 256 256">
        <line
          x1="128"
          y1="32"
          x2="128"
          y2="64"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="195.9"
          y1="60.1"
          x2="173.3"
          y2="82.7"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="224"
          y1="128"
          x2="192"
          y2="128"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="195.9"
          y1="195.9"
          x2="173.3"
          y2="173.3"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="128"
          y1="224"
          x2="128"
          y2="192"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="60.1"
          y1="195.9"
          x2="82.7"
          y2="173.3"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="32"
          y1="128"
          x2="64"
          y2="128"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="60.1"
          y1="60.1"
          x2="82.7"
          y2="82.7"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
      </svg>
      <span class="loading-text">Loading...</span>
    </div>
  </div>

  <div class="px-2 overflow-x-auto overflow-y-auto">
    <div class="p-4">
      <div
        class="overflow-x-auto overflow-y-auto bg-white rounded-lg shadow-md"
      >
        <div class="py-2">
          <label class="font-bold">OUTSIDE SLA INCREASE IN DEMAND</label>
          <table
            class="min-w-full py-4 border-2 border-collapse border-gray-300"
          >
            <thead class="">
              <tr class="text-center bg-gray-100 border-b-4 border-gray-300">
                <th class="px-1 border-2 border-gray-300">Site Name</th>
                <th class="px-1 border-2 border-gray-300">Jan</th>
                <th class="px-1 border-2 border-gray-300">Feb</th>
                <th class="px-1 border-2 border-gray-300">Mar</th>
                <th class="px-1 border-2 border-gray-300">Apr</th>
                <th class="px-1 border-2 border-gray-300">May</th>
                <th class="px-1 border-2 border-gray-300">Jun</th>
                <th class="px-1 border-2 border-gray-300">Jul</th>
                <th class="px-1 border-2 border-gray-300">Aug</th>
                <th class="px-1 border-2 border-gray-300">Sep</th>
                <th class="px-1 border-2 border-gray-300">Oct</th>
                <th class="px-1 border-2 border-gray-300">Nov</th>
                <th class="px-1 border-2 border-gray-300">Dec</th>
                <th class="px-1 border-2 border-gray-300">Total</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="(item, index) in oos" :key="index">
                <tr
                  class="text-black bg-white border-b-2 border-gray-400 border-solid"
                >
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.Site }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.January }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.February }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.March }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.April }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.May }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.June }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.July }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.August }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.September }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.October }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.November }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.December }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.GrandTotalByProgram }}
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
        <div class="py-2">
          <label class="font-bold">OUTSIDE SLA DECREASE IN DEMAND</label>
          <table
            class="min-w-full py-4 border-2 border-collapse border-gray-300"
          >
            <thead class="">
              <tr class="text-center bg-gray-100 border-b-4 border-gray-300">
                <th class="px-1 border-2 border-gray-300">Site Name</th>
                <th class="px-1 border-2 border-gray-300">Jan</th>
                <th class="px-1 border-2 border-gray-300">Feb</th>
                <th class="px-1 border-2 border-gray-300">Mar</th>
                <th class="px-1 border-2 border-gray-300">Apr</th>
                <th class="px-1 border-2 border-gray-300">May</th>
                <th class="px-1 border-2 border-gray-300">Jun</th>
                <th class="px-1 border-2 border-gray-300">Jul</th>
                <th class="px-1 border-2 border-gray-300">Aug</th>
                <th class="px-1 border-2 border-gray-300">Sep</th>
                <th class="px-1 border-2 border-gray-300">Oct</th>
                <th class="px-1 border-2 border-gray-300">Nov</th>
                <th class="px-1 border-2 border-gray-300">Dec</th>
                <th class="px-1 border-2 border-gray-300">Total</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="(item, index) in oos1" :key="index">
                <tr
                  class="text-black bg-white border-b-2 border-gray-400 border-solid"
                >
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.Site }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.January }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.February }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.March }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.April }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.May }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.June }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.July }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.August }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.September }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.October }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.November }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.December }}
                  </td>
                  <td
                    class="px-2 font-semibold truncate border-4 border-gray-300"
                  >
                    {{ item.GrandTotalByProgram }}
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  components: {},
  data() {
    return {
      oos: [],
      oos1: [],
      isLoading: false,
    };
  },
  computed: {},
  mounted() {
    this.getOos1();
    this.getOos();
  },
  watch: {},
  methods: {
    async getOos() {
      this.isLoading = true;
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://10.109.2.112:8081/api/oosclasses",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.oos = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching data");
        }
      } catch (error) {
        console.log(error);
      } finally {
        this.isLoading = false;
      }
    },
    async getOos1() {
      this.isLoading = true;
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://10.109.2.112:8081/api/ooscclasses",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.oos1 = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching data");
        }
      } catch (error) {
        console.log(error);
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>
<style scoped>
/* Your loader styles here */
.loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* Ensure the loader is on top of other elements */
}

.loader-content {
  /* Style your loader content (SVG, text, etc.) */
  display: flex;
  align-items: center;
}

.icon {
  /* Style your SVG icon */
  height: 3rem; /* Adjust the size as needed */
  width: 3rem; /* Adjust the size as needed */
  animation: spin 1s linear infinite;
  stroke: rgba(107, 114, 128, 1);
}

.loading-text {
  /* Style your loading text */
  font-size: 1.5rem; /* Adjust the size as needed */
  line-height: 2rem; /* Adjust the size as needed */
  font-weight: 500;
  color: rgba(107, 114, 128, 1);
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
