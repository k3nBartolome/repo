<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h2 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Site Manager
      </h2>
    </div>
  </header>
  <div class="py-8">
    <div
      class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
    >
      <form
        @submit.prevent="addSite"
        class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5"
      >
        <label class="block">
          Name
          <input
            v-model="name"
            type="text"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Description
          <input
            v-model="description"
            type="text"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Site Director
          <input
            v-model="siteDirector"
            type="text"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <label class="block">
          Region
          <input
            v-model="region"
            type="text"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label>
        <button
          type="submit"
          class="px-4 py-1 font-bold text-white bg-orange-500 rounded hover:bg-gray-600"
        >
          <i class="fa fa-building"></i> Add
        </button>
      </form>
    </div>
  </div>
  <div class="py-8">
    <div class="pl-8 pr-8 overflow-x-auto overflow-y-auto">
      <table class="w-full text-white table-auto">
        <thead>
          <tr
            class="text-left bg-orange-500 border-2 border-orange-600 border-solid"
          >
            <th class="px-1 py-2">ID</th>
            <th class="px-1 py-2">Name</th>
            <th class="px-1 py-2">Description</th>
            <th class="px-1 py-2">Region</th>
            <th class="px-1 py-2">Site Director</th>
            <th class="px-1 py-2">Created by</th>
            <th class="px-1 py-2">Created date</th>
            <th class="px-1 py-2">Updated by</th>
            <th class="px-1 py-2">Updated date</th>
            <th class="px-1 py-2">Active Status</th>
            <th class="px-1 py-2" colspan="3">Action</th>
          </tr>
        </thead>
        <tbody v-for="site in sites" :key="site.id">
          <tr
            class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
          >
            <td class="px-1 py-2">{{ site.id }}</td>
            <td class="px-1 py-2">{{ site.name }}</td>
            <td class="px-1 py-2">{{ site.description }}</td>
            <td class="px-1 py-2">{{ site.region }}</td>
            <td class="px-1 py-2">{{ site.site_director }}</td>
            <td class="px-1 py-2">{{ site.created_by.name }}</td>
            <td class="px-1 py-2">{{ site.created_at }}</td>
            <td class="px-1 py-2">{{ site.updated_by }}</td>
            <td class="px-1 py-2">{{ site.updated_at }}</td>
            <td class="px-1 py-2">
              {{ site.is_active == 1 ? "Active" : "Inactive" }}
            </td>
            <router-link :to="`/site_management/edit/${site.id}`">
              <td class="px-2 py-2">
                <button
                  @click="getSites(site.id)"
                  class="flex items-center h-8 px-1 py-2 text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-blue-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
                >
                  Edit
                </button>
              </td>
            </router-link>
            <td class="px-2 py-2">
              <button
                @click="deactivateSite(site.id)"
                class="flex items-center h-8 px-1 py-2 text-xs font-semibold text-white uppercase transition duration-150 ease-in-out bg-red-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
              >
                Deactivate
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="py-8">
    <div class="pl-8 pr-8 overflow-x-auto overflow-y-auto">
      <table class="w-full text-white table-auto">
        <thead>
          <tr
            class="text-left bg-orange-500 border-2 border-orange-600 border-solid"
          >
            <th class="px-1 py-2">ID</th>
            <th class="px-1 py-2">Name</th>
            <th class="px-1 py-2">Description</th>
            <th class="px-1 py-2">Region</th>
            <th class="px-1 py-2">Site Director</th>
            <th class="px-1 py-2">Created by</th>
            <th class="px-1 py-2">Created date</th>
            <th class="px-1 py-2">Updated by</th>
            <th class="px-1 py-2">Updated date</th>
            <th class="px-1 py-2">Active Status</th>
            <th class="px-1 py-2" colspan="3">Action</th>
          </tr>
        </thead>
        <tbody v-for="site in sites2" :key="site.id">
          <tr
            class="font-semibold text-black bg-white border-2 border-gray-400 border-solid"
          >
            <td class="px-1 py-2">{{ site.id }}</td>
            <td class="px-1 py-2">{{ site.name }}</td>
            <td class="px-1 py-2">{{ site.description }}</td>
            <td class="px-1 py-2">{{ site.region }}</td>
            <td class="px-1 py-2">{{ site.site_director }}</td>
            <td class="px-1 py-2">{{ site.created_by.name }}</td>
            <td class="px-1 py-2">{{ site.created_at }}</td>
            <td class="px-1 py-2">{{ site.updated_by }}</td>
            <td class="px-1 py-2">{{ site.updated_at }}</td>
            <td class="px-1 py-2">
              {{ site.is_active == 1 ? "Active" : "Inactive" }}
            </td>
            <router-link :to="`/site_management/edit/${site.id}`">
              <td class="px-2 py-2">
                <button
                  @click="getSites(site.id)"
                  class="flex items-center h-8 px-1 py-2 text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-blue-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
                >
                  Edit
                </button>
              </td>
            </router-link>
            <td class="px-2 py-2">
              <button
                @click="activateSite(site.id)"
                class="flex items-center h-8 px-1 text-xs font-semibold text-white uppercase transition duration-150 ease-in-out bg-red-600 border-0 rounded-md y-2 hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
              >
                Activate
              </button>
            </td>
          </tr>
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
      sites: false,
      sites2: false,
      name: "",
      description: "",
      siteDirector: "",
      region: "",
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.getSites();
    this.getSites2();
  },
  methods: {
    navigateToEdit(id) {
      // Perform any necessary logic before navigating to the edit route
      this.$router.push(`/site_management/edit/${id}`);
    },
    activateSite(id) {
      const form = {
        is_active: 1,
        updated_by: this.$store.state.user_id,
      };
      axios
        .put("https://10.236.103.190/api/sites_activate/" + id, form)
        .then((response) => {
          console.log(response.data);
          this.is_active = "";
          this.getSites();
          this.getSites2();
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
    deactivateSite(id) {
      const form = {
        is_active: 0,
        updated_by: this.$store.state.user_id,
      };
      axios
        .put("https://10.236.103.190/api/sites_deactivate/" + id, form)
        .then((response) => {
          console.log(response.data);
          this.is_active = "";
          this.getSites();
          this.getSites2();
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
    async getSites() {
      await axios
        .get("https://10.236.103.190/api/sites")
        .then((response) => {
          this.sites = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getSites2() {
      await axios
        .get("https://10.236.103.190/api/sites2")
        .then((response) => {
          this.sites2 = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    addSite() {
      const formData = {
        name: this.name,
        description: this.description,
        site_director: this.siteDirector,
        region: this.region,
        is_active: 1,
        created_by: this.$store.state.user_id,
      };
      axios
        .post("https://10.236.103.190/api/sites", formData)
        .then((response) => {
          console.log(response.data);
          this.name = "";
          this.description = "";
          this.siteDirector = "";
          this.region = "";
          this.getSites();
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
  },
};
</script>
