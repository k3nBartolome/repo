<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h2 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Program Manager
      </h2>
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
  <div class="py-0">
    <div class="px-8">
      <div class="overflow-x-auto">
        <DataTable
          :data="programs"
          :columns="columns"
          class="table min-w-full divide-y divide-gray-200 table-striped"
          :options="{
            responsive: true,
            autoWidth: false,
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
            lengthMenu: [10, 25, 50, 100],
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
        { data: "updated_by_user.name", title: "Updated By" ,
        render: (data, type, row) => {
            return row.updated_by_user ? row.updated_by_user.name : "N/A";
          },},
        { data: "updated_at", title: "Updated Date" },
        {
          data: "is_active",
          title: "Active Status",
          render: function (data) {
            return data === 1 ? "Inactive" : "Active";
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
  computed: {
    isUser() {
      const userRole = this.$store.state.role;
      return userRole === "user";
    },
    isRemx() {
      const userRole = this.$store.state.role;
      return userRole === "remx";
    },
    isBudget() {
      const userRole = this.$store.state.role;
      return userRole === "budget";
    },
    isSourcing() {
      const userRole = this.$store.state.role;
      return userRole === "sourcing";
    },
  },
  methods: {
    navigateToEdit(id) {
      this.$router.push(`/program_management/edit/${id}`);
    },
    activateProgram(id) {
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
        .put(`/programs_activate/${id}`, form, config)
        .then((response) => {
          // Handle the response
          console.log(response.data);
          this.is_active = "";
          this.getPrograms();
          this.getPrograms2();
        })
        .catch((error) => {
          // Handle the error
          console.log(error.response.data);
        });
    },

    deactivateProgram(id) {
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
        .put(`http://127.0.0.1:8000/api/programs_deactivate/${id}`, form, config)
        .then((response) => {
          // Handle the response
          console.log(response.data);
          this.is_active = "";
          this.getPrograms();
          this.getPrograms2();
        })
        .catch((error) => {
          // Handle the error
          console.log(error.response.data);
        });
    },
    async getPrograms() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/programs", {
          headers: {
            Authorization: `Bearer ${this.$store.state.token}`,
          },
        });

        if (response.status === 200) {
          this.programs = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching programs");
        }
      } catch (error) {
        console.log(error);
      }
    },

    async getPrograms2() {
      try {
        const response = await axios.get(
          "http://127.0.0.1:8000/api/programs2",
          {
            headers: {
              Authorization: `Bearer ${this.$store.state.token}`,
            },
          }
        );

        if (response.status === 200) {
          this.programs2 = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching programs2");
        }
      } catch (error) {
        console.log(error);
      }
    },

    async getSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/sites", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.sites = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching sites");
        }
      } catch (error) {
        console.log(error);
      }
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
        .post("http://127.0.0.1:8000/api/programs", formData, {
          headers: {
            Authorization: `Bearer ${this.$store.state.token}`,
          },
        })
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
