<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full py-2 max-w-screen-xl sm:px-2 lg:px-2">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900 pl-8">
        Program Manager
      </h1>
    </div>
  </header>
  <div class="py-8">
    <div
      class="px-4 py-6 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8 border-2 border-orange-600"
    >
      <form
        @submit.prevent="addProgram"
        class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5 font-semibold"
      >
        <label class="block">
          Name
          <input
            type="text"
            v-model="name"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Description
          <input
            type="text"
            v-model="description"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Program Group
          <input
            type="text"
            v-model="program_group"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Site
          <select
            v-model="sites_selected"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
            @change="getSites"
          >
            <option disabled value="" selected>Please select one</option>
            <option v-for="site in sites" :key="site.id" :value="site.id">
              {{ site.name }}
            </option>
          </select>
        </label>
        <button
          type="submit"
          class="bg-orange-500 hover:bg-gray-600 text-white font-bold py-1 px-4 rounded"
        >
          <i class="fa fa-building"></i> Add
        </button>
      </form>
    </div>
  </div>
  <div class="py-8">
    <div class="pl-8 pr-8 overflow-x-auto overflow-y-auto">
      <input type="text" v-model="search" placeholder="Search...">
      <table class="w-full table-auto text-white">
        <thead>
          <tr class="text-left bg-orange-500 border-solid border-2 border-orange-600">
            <th class="px-1 py-1 whitespace-no-wrap truncate border">ID</th>
            <th class="px-1 py-1 whitespace-no-wrap truncate border">Name</th>
            <th class="px-1 py-1 whitespace-no-wrap truncate border">Description</th>
            <th class="px-1 py-1 whitespace-no-wrap truncate border">Program Group</th>
            <th class="px-1 py-1 whitespace-no-wrap truncate border">Site</th>
            <th class="px-1 py-1 whitespace-no-wrap truncate border">Created by</th>
            <th class="px-1 py-1 whitespace-no-wrap truncate border">Created date</th>
            <th class="px-1 py-1 whitespace-no-wrap truncate border">Updated by</th>
            <th class="px-1 py-1 whitespace-no-wrap truncate border">Updated date</th>
            <th class="px-1 py-1 whitespace-no-wrap truncate border">Active Status</th>
            <th class="px-1 py-1 whitespace-no-wrap truncate border" colspan="3">
              Action
            </th>
          </tr>
        </thead>
        <tbody v-for="program in  filteredPrograms" :key="program.id">
          <tr
            class="bg-white text-black font-semibold border-2 border-solid border-gray-400"
          >
            <td class="px-1 py-1">{{ program.id }}</td>
            <td class="px-1 py-1">{{ program.name }}</td>
            <td class="px-1 py-1">{{ program.description }}</td>
            <td class="px-1 py-1">{{ program.program_group }}</td>
            <td class="px-1 py-1">{{ program.site_id }}</td>
            <td class="px-1 py-1">{{ program.created_by }}</td>
            <td class="px-1 py-1">{{ program.created_at }}</td>
            <td class="px-1 py-1">{{ program.updated_by }}</td>
            <td class="px-1 py-1">{{ program.updated_at }}</td>
            <td class="px-1 py-1">
              {{ program.is_active === 1 ? "Active" : "Inactive" }}
            </td>
            <td class="px-2 py-2">
              <button
                @click="getPrograms(program.id)"
                class="flex items-center h-8 px-1 py-1 whitespace-no-wrap truncate border text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-blue-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
              >
                <i class="fa fa-eye"></i>
              </button>
            </td>
            <td class="px-2 py-2">
              <button
                @click="getPrograms(program.id)"
                class="flex items-center h-8 px-1 py-1 whitespace-no-wrap truncate border text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-green-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
              >
                <i class="fa fa-edit"></i>
              </button>
            </td>
            <td class="px-2 py-2">
              <button
                class="flex items-center h-8 px-1 py-1 whitespace-no-wrap truncate border text-xs font-semibold text-white uppercase transition duration-150 ease-in-out bg-red-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
              >
                <i class="fa fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <button @click="previousPage" :disabled="currentPage === 1">Previous</button>
      <span>{{ currentPage }} / {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      programs: [],
      name: "",
      description: "",
      program_group: "",
      sites_selected: "",
      sites: [],
      currentPage: 1,
      perPage: 5,
      search:""
    };
  },

  mounted() {
    console.log("Component mounted.");
    this.getPrograms();
    this.getSites();
  },
  computed: {
    filteredPrograms() {
      return this.programs.filter((programs) =>
        programs.name.toLowerCase().includes(this.search.toLowerCase()
        )
      );
    },
    totalPages() {
      return Math.ceil(this.filteredPrograms.length / this.perPage);
    },
  },
  methods: {
    async getPrograms() {
      await axios
        .get("http://10.109.2.112:8080/api/programs")
        .then((response) => {
          this.programs = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getSites() {
      console.log(this.sites_selected);
      await axios
        .get("http://10.109.2.112:8080/api/sites")
        .then((response) => {
          this.sites = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    addProgram() {
      const formData = {
        name: this.name,
        description: this.description,
        program_group: this.program_group,
        site_id: this.sites_selected,
        is_active: 1,
        created_by: this.$store.state.user_id,
      };
      axios
        .post("http://10.109.2.112:8080/api/programs", formData)
        .then((response) => {
          console.log(response.data);
          this.name = "";
          this.description = "";
          this.program_group = "";
          this.sites_selected = "";
          this.getPrograms();
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
  },
};
</script>
