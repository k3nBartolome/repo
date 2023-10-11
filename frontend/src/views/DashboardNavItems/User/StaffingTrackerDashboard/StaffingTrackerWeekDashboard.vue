<template>
  <div class="p-4">
    <div class="mb-4">
      <input v-model="filterLastName" placeholder="Filter by Last Name" class="p-2 border rounded-lg" />
      <input v-model="filterFirstName" placeholder="Filter by First Name" class="p-2 border rounded-lg" />
      <input v-model="filterMiddleName" placeholder="Filter by Middle Name" class="p-2 border rounded-lg" />
      <button @click="fetchData" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Filter</button>
    </div>

    <table v-if="perx.length > 0" class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-200">
          <th class="border p-2">First Name</th>
          <th class="border p-2">Last Name</th>
          <th class="border p-2">Middle Name</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="person in perx" :key="person.id" class="border">
          <td class="border p-2">{{ person.FirstName }}</td>
          <td class="border p-2">{{ person.LastName }}</td>
          <td class="border p-2">{{ person.MiddleName }}</td>
        </tr>
      </tbody>
    </table>

    <p v-else class="mt-4">No data available</p>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      filterLastName: "",
      filterFirstName: "",
      filterMiddleName: "",
      perx: [],
    };
  },
  methods: {
    async fetchData() {
  console.log("Filter LastName:", this.filterLastName);
  console.log("Filter FirstName:", this.filterFirstName);
  console.log("Filter MiddleName:", this.filterMiddleName);
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://10.109.2.112:8081/api/perxfilter", {
          params: {
            filter_lastname: this.filterLastName,
            filter_firstname: this.filterFirstName,
            filter_middlename: this.filterMiddleName,
          },
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        this.perx = response.data.perx;
      } catch (error) {
        console.error("Error fetching data", error);
      }
    },
  },
};
</script>
