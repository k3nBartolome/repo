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
        @submit.prevent="editSite"
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
          <i class="fa fa-building"></i> Edit
        </button>
      </form>
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      name: "",
      description: "",
      siteDirector: "",
      region: "",
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.getSites();
  },
  methods: {
    async getSites() {
      await axios
        .get("http://127.0.0.1:8000/api/sites/" + this.$route.params.id)
        .then((response) => {
          this.sites = response.data.data;
          const siteObj = this.sites;
          this.name = siteObj.name;
          this.description = siteObj.description;
          this.siteDirector = siteObj.siteDirector;
          this.region = siteObj.region;

          console.log(siteObj);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    editSite() {
      const formData = {
        name: this.name,
        description: this.description,
        site_director: this.siteDirector,
        region: this.region,
        updated_by: this.$store.state.user_id,
      };
      axios
        .put(
          "http://127.0.0.1:8000/api/sites/" + this.$route.params.id,
          formData
        )
        .then((response) => {
          console.log(response.data);
          this.name = "";
          this.description = "";
          this.siteDirector = "";
          this.region = "";
          this.updated_by = "";
          this.$router.push("/site_managementindia", () => {
            location.reload();
          });
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
  },
};
</script>
