<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        Site Manager
      </h1>
    </div>
  </header>
  <div class="mx-auto max-w-7xl">
    <table class="w-full text-left table-collapse">
      <thead>
        <tr class="text-sm font-medium tracking-tight text-gray-500 uppercase">
          <th class="px-4 py-3">ID</th>
          <th class="px-4 py-3">Name</th>
          <th class="px-4 py-3">Description</th>
          <th class="px-4 py-3">Region</th>
          <th class="px-4 py-3">is active?</th>
          <th class="px-4 py-3">Action</th>
        </tr>
      </thead>
      <tbody v-for="site in sites" :key="site.id">
        <tr class="text-sm">
          <td class="px-4 py-3">{{ site.id }}</td>
          <td class="px-4 py-3">{{ site.name }}</td>
          <td class="px-4 py-3">{{ site.description }}</td>
          <td class="px-4 py-3">{{ site.region }}</td>
          <td class="px-4 py-3">{{ site.is_active }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
import axios from 'axios';

export default {
  data() {
    return {
      sites: false,
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.getSites();
  },
  methods: {
    async getSites() {
      await axios
        .get("http://127.0.0.1:8000/api/sites")
        .then((response) => {
          this.sites = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  }
};
</script>