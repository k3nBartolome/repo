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

  <div class="py-0">
    <div class="px-8">
      <div class="overflow-x-auto">
        <DataTable
          :data="sites"
          :columns="columns"
          class="table min-w-full divide-y divide-gray-200 table-striped"
          :options="{
            responsive: true,
            autoWidth: true,
            dom: 'Bfrtip',
            language: {
              search: 'Search',
              zeroRecords: 'No data available',
              info: 'Showing from _START_ to _END_ of _TOTAL_ records',
              infoFiltered: '(Filtered from MAX records.)',
              paginate: {
                first: 'First',
                previous: 'Prev',
                next: 'Next',
                last: 'Last',
              },
            },
          }"
        >
          <thead class="truncate">
            <tr>
              <!-- ...existing code... -->
            </tr>
          </thead>
        </DataTable>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import DataTable from "datatables.net-vue3";
import DataTableLib from "datatables.net-bs5";
// eslint-disable-next-line no-unused-vars
import Buttons from "datatables.net-buttons-bs5";
import ButtonsHtml5 from "datatables.net-buttons/js/buttons.html5";
// eslint-disable-next-line no-unused-vars
import print from "datatables.net-buttons/js/buttons.print";
//import pdfmake from "pdfmake";
// eslint-disable-next-line no-unused-vars
import pdfFonts from "pdfmake/build/vfs_fonts";
import "datatables.net-responsive-bs5";
// eslint-disable-next-line no-unused-vars

import "bootstrap/dist/css/bootstrap.css";

DataTable.use(DataTableLib);
//DataTable.use(pdfmake);
DataTable.use(ButtonsHtml5);

export default {
  components: { DataTable },
  data() {
    return {
      sites: [],
      sr: [],
      sites2: [],
      name: "",
      description: "",
      siteDirector: "",
      region: "",
      columns: [
        { data: "id", title: "ID" },
        { data: "name", title: "Name" },
        { data: "description", title: "Description" },
        { data: "site_director", title: "Site Director" },
        { data: "region", title: "Region" },
        { data: "country", title: "Country" },
        { data: "created_by.name", title: "Created by" },
        { data: "created_at", title: "Created date" },
        { data: "updated_by", title: "Updated by" },
        { data: "updated_at", title: "Updated date" },
        {
          data: "is_active",
          title: "Active Status",
          render: function (data) {
            return data === 1 ? "Active" : "Inactive";
          },
        },
        {
          data: "id",
          title: "Actions",
          orderable: false,
          searchable: false,
          render: function (data) {
            return `<button class="btn btn-danger w-36" data-id="${data}"  onclick="window.vm.deactivateSite(${data})">Deactivate</button>
                    <button class="btn btn-primary w-36" data-id="${data}" onclick="window.vm.navigateToEdit(${data})">Edit</button>
  `;
          },
        },
      ],
    };
  },
  mounted() {
    window.vm = this;
    this.getSites();
    this.getSites2();
  },
  methods: {
    navigateToEdit(id) {
      this.$router.push(`/site_management/edit/${id}`);
    },
    activateSite(id) {
      const form = {
        is_active: 1,
        updated_by: this.$store.state.user_id,
      };

      const config = {
        headers: {
          Authorization: `Bearer ${this.$store.state.token}`,
        },
      };

      axios
        .put(`https://10.109.2.112/api//sites_activate/${id}`, form, config)
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

      const config = {
        headers: {
          Authorization: `Bearer ${this.$store.state.token}`,
        },
      };

      axios
        .put(`https://10.109.2.112/api/sites_deactivate/${id}`, form, config)
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
      try {
        const token = this.$store.state.token;
        const config = {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        };

        await axios
          .get("https://10.109.2.112/api/sites", config)
          .then((response) => {
            console.log("Response received:", response.data);
            this.sites = response.data.data;
            console.log("Sites data:", this.sites);
          })
          .catch((error) => {
            console.log("Error:", error);
          });
      } catch (error) {
        console.log("Error:", error);
      }
    },




    async getSites2() {
      try {
        const token = this.$store.state.token;
        const config = {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        };

        await axios
          .get("https://10.109.2.112/api/sites2", config)
          .then((response) => {
            console.log("Response received:", response.data);
            this.sites2 = response.data.data;
            console.log("Sites data:", this.sites);
          })
          .catch((error) => {
            console.log("Error:", error);
          });
      } catch (error) {
        console.log("Error:", error);
      }
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

      const token = this.$store.state.token;
      const config = {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      };

      axios
        .post("https://10.109.2.112/api/sites", formData, config)
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
