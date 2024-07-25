<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h2 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Program Manager
      </h2>
    </div>
  </header>

  <div
    class="fixed inset-0 z-50 flex items-center justify-center modal"
    v-if="ShowDeactivateModal"
  >
    <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
    <div class="max-w-sm p-4 bg-white rounded shadow-lg modal-content">
      <header
        class="flex items-center justify-between px-4 py-2 border-b-2 border-gray-200"
      >
        <h2 class="text-lg font-semibold text-gray-800">
          Do you want to deactivate this Program?
        </h2>
        <button
          @click="ShowDeactivateModal = false"
          class="text-gray-600 hover:text-gray-800"
        >
          <svg
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            ></path>
          </svg>
        </button>
      </header>

      <div class="flex justify-between mt-4">
        <button
          @click="deactivateProgram(deactivateRequestId)"
          class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
        >
          Ok
        </button>
        <button
          @click="ShowDeactivateModal = false"
          class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600"
        >
          Cancel
        </button>
      </div>
    </div>
  </div>
  <div
    class="fixed inset-0 z-50 flex items-center justify-center modal"
    v-if="ShowActivateModal"
  >
    <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
    <div class="max-w-sm p-4 bg-white rounded shadow-lg modal-content">
      <header
        class="flex items-center justify-between px-4 py-2 border-b-2 border-gray-200"
      >
        <h2 class="text-lg font-semibold text-gray-800">
          Do you want to Activate this Program?
        </h2>
        <button
          @click="ShowActivateModal = false"
          class="text-gray-600 hover:text-gray-800"
        >
          <svg
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            ></path>
          </svg>
        </button>
      </header>

      <div class="flex justify-between mt-4">
        <button
          @click="activateProgram(activateRequestId)"
          class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
        >
          Ok
        </button>
        <button
          @click="ShowActivateModal = false"
          class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600"
        >
          Cancel
        </button>
      </div>
    </div>
  </div>

  <div class="px-12 py-8 font-serifs">
    <span v-if="successMessage" class="text-green-500">{{
      successMessage
    }}</span>
    <span v-if="errorMessage" class="text-red-500">{{ errorMessage }}</span>
    <form @submit.prevent="addProgram">
      <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <label class="block font-semibold"
            >Name<input
              type="text"
              v-model="name"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
          /></label>
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <label class="block font-semibold"
            >Description<input
              type="text"
              v-model="description"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
          /></label>
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <label class="block font-semibold"
            >Program Group<input
              type="text"
              v-model="program_group"
              class="w-full px-4 py-2 bg-white border rounded-lg"
          /></label>
        </div>

        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <label class="block font-semibold"
            >ID Creation<select
              v-model="id_creation"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
            >
              <option disabled value="" selected>Please select one</option>
              <option value="Yes" selected>Yes</option>
              <option value="No" selected>No</option>
            </select></label
          >
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <label class="block font-semibold"
            >Pre Emps<select
              v-model="pre_emps"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
            >
              <option disabled value="" selected>Please select one</option>
              <option value="Yes" selected>Yes</option>
              <option value="No" selected>No</option>
            </select></label
          >
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <label class="block font-semibold"
            >Site<select
              v-model="sites_selected"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
              @change="getSites"
            >
              <option disabled value="" selected>Please select one</option>
              <option v-for="site in sites" :key="site.id" :value="site.id">
                {{ site.name }}
              </option>
            </select></label
          >
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <label class="block font-semibold">
            <button
              type="submit"
              class="w-full px-4 py-2 mt-4 font-bold text-white bg-orange-500 rounded hover:bg-gray-600"
            >
              <i class="fa fa-building"></i> Add
            </button>
          </label>
        </div>
      </div>
    </form>
  </div>
  <h3>Active Program</h3>
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
  <h3 class="py-4">Inactive Program</h3>
  <div class="py-0">
    <div class="px-8">
      <div class="overflow-x-auto">
        <DataTable
          :data="programs2"
          :columns="columns2"
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
      errorMessage: "",
      sites: [],
      program_type: "",
      pre_emps: "",
      id_creation: "",
      ShowDeactivateModal: false,
      deactivateRequestId: null,
      ShowActivateModal: false,
      activateRequestId: null,
      successMessage: "",
      columns: [
        { data: "id", title: "ID" },
        { data: "name", title: "Name" },
        { data: "site.name", title: "Site" },
        { data: "description", title: "Description" },

        {
          data: "id_creation",
          title: "ID Creation?",
        },
        {
          data: "pre_emps",
          title: "Pre Emps?",
        },
        { data: "created_by_user.name", title: "Created By" },
        { data: "created_at", title: "Created Date" },
        {
          data: "updated_by_user.name",
          title: "Updated By",
          render: (data, type, row) => {
            return row.updated_by_user ? row.updated_by_user.name : "N/A";
          },
        },
        { data: "updated_at", title: "Updated Date" },
        {
          data: "is_active",
          title: "Active Status",
          render: function (data) {
            return Number(data) ? "Active" : "Inactive";
          },
        },
        {
          data: "id",
          title: "Actions",
          orderable: false,
          searchable: false,
          render: function (data) {
            return `<button class="btn btn-danger w-36" data-id="${data}" onclick="window.vm.openModalForDeactivation(${data})">Deactivate</button>
                    <button class="btn btn-primary w-36" data-id="${data}" onclick="window.vm.navigateToEdit(${data})">Edit</button>
  `;
          },
        },
      ],
      columns2: [
        { data: "id", title: "ID" },
        { data: "name", title: "Name" },
        { data: "site.name", title: "Site" },
        { data: "description", title: "Description" },

        {
          data: "id_creation",
          title: "ID Creation?",
        },
        {
          data: "pre_emps",
          title: "Pre Emps?",
        },

        { data: "created_by_user.name", title: "Created By" },
        { data: "created_at", title: "Created Date" },
        {
          data: "updated_by_user.name",
          title: "Updated By",
          render: (data, type, row) => {
            return row.updated_by_user ? row.updated_by_user.name : "N/A";
          },
        },
        { data: "updated_at", title: "Updated Date" },
        {
          data: "is_active",
          title: "Active Status",
          render: function (data) {
            return Number(data) ? "Active" : "Inactive";
          },
        },
        {
          data: "id",
          title: "Actions",
          orderable: false,
          searchable: false,
          render: function (data) {
            return `<button class="btn btn-danger w-36" data-id="${data}" onclick="window.vm.openModalForActivation(${data})">Activate</button>
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
    openModalForDeactivation(id) {
      this.deactivateRequestId = id;
      this.ShowDeactivateModal = true;
    },
    openModalForActivation(id) {
      this.activateRequestId = id;
      this.ShowActivateModal = true;
    },

    navigateToEdit(id) {
      this.$router.push(`/program_managementjamaica/edit/${id}`);
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
        .put(`http://10.109.2.112:8081/api/programs_activate/${id}`, form, config)
        .then((response) => {
          console.log(response.data);
          this.successMessage = "Program activated successfully!";
          this.getPrograms();
          this.getPrograms2();
          this.ShowActivateModal = false;
        })
        .catch((error) => {
          console.log(error.response.data);
          this.errorMessage = "An error occurred while activating the program.";
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
        .put(
          `http://10.109.2.112:8081/api/programs_deactivate/${id}`,
          form,
          config
        )

        .then((response) => {
          console.log(response.data);
          this.successMessage = "Program deactivated successfully!";
          this.getPrograms();
          this.getPrograms2();
          this.ShowDeactivateModal = false;
        })
        .catch((error) => {
          console.log(error.response.data);
          this.errorMessage =
            "An error occurred while deactivating the program.";
        });
    },
    async getPrograms() {
      try {
        const response = await axios.get(
          "http://10.109.2.112:8081/api/programs5",
          {
            headers: {
              Authorization: `Bearer ${this.$store.state.token}`,
            },
          }
        );

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
          "http://10.109.2.112:8081/api/programs6",
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
        const response = await axios.get("http://10.109.2.112:8081/api/sites5", {
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

        pre_emps: this.pre_emps,
        id_creation: this.id_creation,
        created_by: this.$store.state.user_id,
      };

      axios
        .post("http://10.109.2.112:8081/api/programsother", formData, {
          headers: {
            Authorization: `Bearer ${this.$store.state.token}`,
          },
        })
        .then((response) => {
          console.log(response.data);
          this.successMessage = "Program Added Successfully!";
          // Reset form fields after successful addition
          this.resetForm();
          this.getPrograms();
        })
        .catch((error) => {
          console.log(error.response.data);
          // Handle different types of errors
          if (error.response.status === 400) {
            // Handle validation errors
            this.errorMessage = error.response.data.error.name[0];
          } else {
            // Handle other errors
            this.errorMessage = "An error occurred while adding the program.";
          }
        });
    },

    resetForm() {
      // Reset form fields
      this.name = "";
      this.description = "";
      this.program_group = "";
      this.sites_selected = "";
      this.program_type = "";
      this.id_creation = "";
      this.pre_emps = "";
    },
  },
};
</script>
<style>
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  max-width: 400px;
}
</style>
