<template>
  <div v-if="isLoading" class="loader">
    <div aria-label="Loading..." role="status" class="loader">
      <svg class="icon" viewBox="0 0 256 256">
        <line
          x1="128"
          y1="32"
          x2="128"
          y2="64"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="195.9"
          y1="60.1"
          x2="173.3"
          y2="82.7"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="224"
          y1="128"
          x2="192"
          y2="128"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="195.9"
          y1="195.9"
          x2="173.3"
          y2="173.3"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="128"
          y1="224"
          x2="128"
          y2="192"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="60.1"
          y1="195.9"
          x2="82.7"
          y2="173.3"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="32"
          y1="128"
          x2="64"
          y2="128"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="60.1"
          y1="60.1"
          x2="82.7"
          y2="82.7"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
      </svg>
      <span class="loading-text">Loading...</span>
    </div>
  </div>
  <div class="py-0">
    <div class="container px-4 py-0 mx-auto mt-4">
      <div class="flex justify-center">
        <span
          v-if="EmptySelection"
          class="ml-2 text-lg text-red-500"
          style="align-self: center"
        >
          Fill all the selections first
        </span>
      </div>
      <div class="py-0 mb-4 md:flex md:space-x-2 md:items-center">
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <select
            v-model="sites_selected"
            class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
            @change="getPrograms"
          >
            <option disabled value="" selected>Please select Site</option>
            <option v-for="site in sites" :key="site.id" :value="site.id">
              {{ site.name }}
            </option>
          </select>
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <select
            v-model="programs_selected"
            class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
          >
            <option disabled value="" selected>Please select Program</option>
            <option
              v-for="program in programs"
              :key="program.id"
              :value="program.id"
            >
              {{ program.name }}
            </option>
          </select>
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <select
            v-model="month_selected"
            class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
            @change="onMonthSelected"
          >
            <option disabled value="" selected>Please select Month</option>
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
          </select>
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <select
            v-model="week_selected"
            class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
          >
            <option disabled value="" selected>Please select Week</option>
            <option
              v-for="daterange in daterange"
              :key="daterange.id"
              :value="daterange.id"
            >
              {{ daterange.date_range }}
            </option>
          </select>
        </div>
        <div
          class="flex items-center justify-end w-full px-2 mt-4 md:w-1/3 md:mt-0"
        >
          <button
            type="button"
            class="p-2 mr-2 text-white bg-red-500 rounded-lg"
            @click="resetFilter"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 384 512"
              class="w-4 h-4"
            >
              <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
              <path
                fill="#ffffff"
                d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"
              />
            </svg>
          </button>

          <router-link
            :to="{
              path: `/addcapfile/}`,
              query: {
                program: programs_selected,
                site: sites_selected,
                daterange: week_selected,
              },
            }"
          >
            <button
              type="submit"
              :disabled="isButtonDisabled"
              v-if="isButtonVisible && isUser"
              class="p-2 ml-2 text-white bg-orange-500 rounded-lg"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 448 512"
                class="w-4 h-4"
              >
                <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path
                  fill="#ffffff"
                  d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"
                />
              </svg>
            </button>
          </router-link>
        </div>
      </div>
    </div>
  </div>

  <div class="py-0">
    <div class="pl-8 pr-8">
      <div class="scroll">
        <div class="w-2/3 mx-auto datatable-container">
          <DataTable
            :data="classes"
            :columns="columns"
            class="table divide-y divide-gray-200 table-auto table-striped"
            :options="{
              responsive: false,
              autoWidth: false,
              pageLength: 10,
              lengthChange: true,
              ordering: true,
              scrollX: true,
              dom: 'frtip',
              language: {
                search: 'Search',
                zeroRecords: 'No data available',
                info: 'Showing from _START_ to _END_ of _TOTAL_ records',
                infoFiltered: '(Filtered from MAX records)',
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
      classes: [],
      sites: [],
      programs: [],
      daterange: [],
      sites_selected: "",
      programs_selected: "",
      month_selected: "",
      week_selected: "",
      isLoading: false,
      isButtonVisible: true,
      columns: [
        { data: "id", title: "ID" },
        {
          data: "id",
          title: "Actions",
          orderable: false,
          searchable: false,
          render: function (data) {
            const isUser = this.isUser;

            return `
    ${
      isUser
        ? `
            <button class="p-2 text-white transition duration-300 ease-in-out bg-blue-500 rounded-full shadow-md hover:bg-blue-700" data-id="${data}" onclick="window.vm.navigateToEdit(${data})">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-4 h-4"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>
            </button>
            <button class="p-2 text-white transition duration-300 ease-in-out bg-gray-500 rounded-full shadow-md hover:bg-gray-700" data-id="${data}" onclick="window.vm.navigateToPushback(${data})">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-4 h-4"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M267.5 440.6c9.5 7.9 22.8 9.7 34.1 4.4s18.4-16.6 18.4-29V96c0-12.4-7.2-23.7-18.4-29s-24.5-3.6-34.1 4.4l-192 160L64 241V96c0-17.7-14.3-32-32-32S0 78.3 0 96V416c0 17.7 14.3 32 32 32s32-14.3 32-32V271l11.5 9.6 192 160z"/></svg>
            </button>
            <button  class="p-2 text-white transition duration-300 ease-in-out bg-red-500 rounded-full shadow-md hover:bg-red-700" data-id="${data}" onclick="window.vm.navigateToCancel(${data})"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg></button>
            `
        : ""
    }
  `;
          }.bind(this),
        },
        { data: "country", title: "Country" },
        { data: "region", title: "Region" },
        { data: "site_name", title: "Site" },
        { data: "program_name", title: "Program" },
        { data: "month", title: "Month" },
        { data: "date_range", title: "Hiring Week" },
        { data: "total_target", title: "Total Target" },
        { data: "original_start_date", title: "Original Start Date" },
        { data: "type_of_hiring", title: "Type of Hiring" },
        { data: "created_at", title: "Created date" },
        { data: "created_by", title: "Created by" },
      ],
    };
  },
  watch: {
    sites_selected() {
      this.checkClassExists();
      this.getClassesAll();
    },
    programs_selected() {
      this.checkClassExists();
      this.getClassesAll();
    },
    month_selected() {
      this.checkClassExists();
      this.getClassesAll();
    },
    week_selected() {
      this.checkClassExists();
      this.getClassesAll();
    },
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
    isButtonDisabled() {
      return (
        !this.sites_selected || !this.programs_selected || !this.week_selected
      );
    },
    EmptySelection() {
      return (
        !this.sites_selected || !this.programs_selected || !this.week_selected
      );
    },
  },
  mounted() {
    window.vm = this;
    this.getSites();
    this.getPrograms();
    this.getDateRange();
    this.getClassesAll();
    this.checkClassExists();
  },

  methods: {
    async checkClassExists() {
      try {
        const token = this.$store.state.token; // Assuming you store the token in Vuex state
        const response = await axios.get(
          "http://127.0.0.1:8000/api/class_exists",
          {
            params: {
              sites_selected: this.sites_selected,
              programs_selected: this.programs_selected,
              month_selected: this.month_selected,
              week_selected: this.week_selected,
            },
            headers: {
              Authorization: `Bearer ${token}`, // Include the token in the Authorization header
            },
          }
        );

        if (response.status === 200) {
          this.isButtonVisible = !response.data.classExists;
        } else {
          console.log("Error checking class existence");
        }
      } catch (error) {
        console.error(error);
      }
    },

    onMonthSelected() {
      if (this.month_selected) {
        this.getDateRange();
      }
    },
    resetFilter() {
      this.sites_selected = "";
      this.programs_selected = "";
      this.month_selected = "";
      this.week_selected = "";
      this.status = "";
    },
    navigateToEdit(id) {
      this.$router.push(`/editcapfile/${id}`);
    },
    navigateToCancel(id) {
      this.$router.push(`/cancelcapfile/${id}`);
    },
    navigateToPushback(id) {
      this.$router.push(`/pushbackcapfile/${id}`);
    },
    async getClassesAll() {
      try {
        this.isLoading = true;
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/classesall",
          {
            params: {
              sites_selected: this.sites_selected,
              programs_selected: this.programs_selected,
              month_selected: this.month_selected,
              week_selected: this.week_selected,
            },
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.classes = response.data.classes;
          console.log(response.data.classes);
        } else {
          console.log("Error fetching classes");
        }
      } catch (error) {
        console.log(error);
      } finally {
        this.isLoading = false;
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
    async getPrograms() {
      if (!this.sites_selected) {
        return;
      }

      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          `http://127.0.0.1:8000/api/programs_selected/${this.sites_selected}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
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
        console.error(error);
      }
    },

    async getDateRange() {
      if (!this.month_selected) {
        return;
      }

      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          `http://127.0.0.1:8000/api/daterange_selected/${this.month_selected}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.daterange = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching date range");
        }
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>
<style>
.table-responsive {
  overflow: auto;
}

.datatable-container {
  width: 100%;
}

.table {
  white-space: nowrap;
}

.table thead th {
  padding: 8px;
}

.table tbody td {
  padding: 8px;
}
</style>
<style scoped>
/* Your loader styles here */
.loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* Ensure the loader is on top of other elements */
}

.loader-content {
  /* Style your loader content (SVG, text, etc.) */
  display: flex;
  align-items: center;
}

.icon {
  /* Style your SVG icon */
  height: 3rem; /* Adjust the size as needed */
  width: 3rem; /* Adjust the size as needed */
  animation: spin 1s linear infinite;
  stroke: rgba(107, 114, 128, 1);
}

.loading-text {
  /* Style your loading text */
  font-size: 1.5rem; /* Adjust the size as needed */
  line-height: 2rem; /* Adjust the size as needed */
  font-weight: 500;
  color: rgba(107, 114, 128, 1);
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
