<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h1 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Program Manager
      </h1>
    </div>
  </header>
  <div class="py-8">
    <div
      class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
    >
      <form
        @submit.prevent="addProgram"
        class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5"
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
          class="px-4 py-1 font-bold text-white bg-orange-500 rounded hover:bg-gray-600"
        >
          <i class="fa fa-building"></i> Add
        </button>
      </form>
    </div>
  </div>
  <div class="py-2">
    <div class="px-8">
      <div class="overflow-x-auto">
        <DataTable
          :data="programs"
          :columns="columns"
          class="min-w-full divide-y divide-gray-200"
          :options="{
            responsive: true,
            autoWidth: true,
            dom: 'Bfrtip',
            language: {
              search: 'Search',
              zeroRecords: 'No data available',
              info: 'Showing from _START_ to _END_ of _TOTAL_ records',
              infoFiltered: '(Filtrados de _MAX_ registros.)',
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

DataTable.use(DataTableLib);
//DataTable.use(pdfmake);
DataTable.use(ButtonsHtml5);
export default {
  components: { DataTable },
  data() {
    return {
      programs: [],
      programs2: [],
      name: "",
      description: "",
      program_group: "",
      sites_selected: "",
      sites: [],
      columns: [
        { data: "id", title: "ID" },
        { data: "name", title: "Name" },
        { data: "site.name", title: "Site" },
        { data: "description", title: "Description" },
        { data: "created_by_user.name", title: "Created By" },
        { data: "created_at", title: "Created Date" },
        { data: "updated_by", title: "Updated By" },
        { data: "updated_at", title: "Updated Date" },
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
            return `<button class="btn btn-danger w-36" data-id="${data}"  onclick="window.vm.deactivateProgram(${data})">Deactivate</button>
                    <button class="btn btn-primary w-36" data-id="${data}" onclick="window.vm.navigateToEdit(${data})">Edit</button>
  `;
          },
        },
      ],
    };
  },

  mounted() {
    this.getPrograms();
    this.getPrograms2();
    this.getSites();
    window.vm = this;
  },
  computed: {},
  methods: {
    navigateToEdit(id) {
      this.$router.push(`/program_management/edit/${id}`);
    },
    activateProgram(id) {
      const form = {
        is_active: 1,
        updated_by: this.$store.state.user_id,
      };
      axios
        .put("http://10.109.2.112:8081/api/programs_activate/" + id, form)
        .then((response) => {
          console.log(response.data);
          this.is_active = "";
          this.getPrograms();
          this.getPrograms2();
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
    deactivateProgram(id) {
      const form = {
        is_active: 0,
        updated_by: this.$store.state.user_id,
      };
      axios
        .put("http://10.109.2.112:8081/api/programs_deactivate/" + id, form)
        .then((response) => {
          console.log(response.data);
          this.is_active = "";
          this.getPrograms();
          this.getPrograms2();
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
    async getPrograms() {
      await axios
        .get("http://10.109.2.112:8081/api/programs")
        .then((response) => {
          this.programs = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getPrograms2() {
      await axios
        .get("http://10.109.2.112:8081/api/programs2")
        .then((response) => {
          this.programs2 = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getSites() {
      console.log(this.sites_selected);
      await axios
        .get("http://10.109.2.112:8081/api/sites")
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
        .post("http://10.109.2.112:8081/api/programs", formData)
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
  },
};
</script>
