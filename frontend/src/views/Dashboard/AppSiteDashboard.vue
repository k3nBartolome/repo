<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full px-4 py-6 mx-auto max-w-screen-xl sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        Site Manager
      </h1>
    </div>
  </header>
  <div class="pl-8 overflow-x-auto overflow-y-auto">
    <table class="w-full table-auto">
      <thead>
        <tr class="text-sm font-medium tracking-tight text-gray-500 uppercase">
          <th class="px-4 py-3 w-1/6">ID</th>
          <th class="px-4 py-3 w-1/6">Name</th>
          <th class="px-4 py-3 w-2/6">Description</th>
          <th class="px-4 py-3 w-1/6">Region</th>
          <th class="px-4 py-3 w-1/6">Created by</th>
          <th class="px-4 py-3 w-1/6">Created date</th>
          <th class="px-4 py-3 w-1/6">Updated by</th>
          <th class="px-4 py-3 w-1/6">Updated date</th>
          <th class="px-4 py-3 w-1/6">is active?</th>
          <th class="px-4 py-3 w-1/6" colspan="3">Action</th>
        </tr>
      </thead>
      <tbody v-for="site in sites" :key="site.id">
        <tr class="text-sm">
          <td class="px-4 py-3 w-1/6">{{ site.id }}</td>
          <td class="px-4 py-3 w-1/6">{{ site.name }}</td>
          <td class="px-4 py-3 w-2/6">{{ site.description }}</td>
          <td class="px-4 py-3 w-1/6">{{ site.region }}</td>
          <td class="px-4 py-3 w-1/6">{{ site.created_by }}</td>
          <td class="px-4 py-3 w-1/6">{{ site.created_at }}</td>
          <td class="px-4 py-3 w-1/6">{{ site.updated_by }}</td>
          <td class="px-4 py-3 w-1/6">{{ site.updated_at }}</td>
          <td class="px-4 py-3 w-1/6">{{ site.is_active }}</td>
          <td class="px-4 py-3 w-1/6"><button @click="getSites(site.id)"
              class="flex items-center h-8 px-4 py-2 text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-blue-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25">
              <i class="fa fa-eye"></i>
            </button>
          </td>
          <td class="px-0 py-2 border">
            <button @click="getSites(site.id)"
              class="flex items-center h-8 px-4 py-2 text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-green-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25">
              <i class="fa fa-edit"></i>
            </button>
          </td>
          <td class="px-0 py-2 border">
            <button
              class="flex items-center h-8 px-4 py-2 text-xs font-semibold text-white uppercase transition duration-150 ease-in-out bg-red-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25">
              <i class="fa fa-trash"></i>
            </button>
          </td>
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