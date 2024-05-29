<template>
  <div class="py-0">
    <div class="px-1 py-0 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
      <div
        class="fixed inset-0 z-50 flex items-center justify-center modal"
        v-if="showModalEdit"
      >
        <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
        <div class="max-w-sm p-4 bg-white rounded shadow-lg modal-content">
          <header class="px-4 py-2 border-b-2 border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Edit Classes</h2>
          </header>
          <button
            @click="showModalEdit = false"
            class="absolute top-0 right-0 m-4 text-gray-600 hover:text-gray-800"
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
          <div v-if="loading" class="loader">
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
          <form
            @submit.prevent="editClasses(editId)"
            class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-1"
          >
            <div class="col-span-1">
              <label class="block"
                >Pipeline Offered
                <input
                  type="number"
                  v-model="pipeline_offered"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block"
                >Pipeline Utilized
                <textarea
                  v-model="pipeline_utilized"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="flex justify-end mt-4">
              <button
                type="submit"
                class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
              >
                Submit
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="px-4 py-0">
    <div class="px-4 py-0 bg-white">
      <div
        class="fixed inset-0 z-50 flex items-center justify-center mx-4 modal"
        v-if="showModal"
      >
        <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
        <div
          class="w-auto max-w-3xl min-w-full p-4 px-4 bg-white rounded shadow-lg modal-content"
        >
          <header class="px-4 py-2 border-b-2 border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Class History</h2>
          </header>
          <button
            @click="showModal = false"
            class="absolute top-0 right-0 m-4 text-gray-600 hover:text-gray-800"
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
          <div class="modal-scrollable-content">
            <table class="min-w-full border-2 border-collapse border-gray-300">
              <thead>
                <tr class="text-center bg-gray-100 border-b-4 border-gray-300">
                  <th class="px-2 py-2 truncate border-2 border-gray-300">
                    ID
                  </th>
                  <th class="px-2 py-2 truncate border-2 border-gray-300">
                    Site
                  </th>
                  <th class="px-2 py-2 truncate border-2 border-gray-300">
                    Line of Business
                  </th>
                  <th class="px-2 py-2 truncate border-2 border-gray-300">
                    Type of Hiring
                  </th>
                  <th class="px-2 py-2 truncate border-2 border-gray-300">
                    Total Target
                  </th>
                  <th class="px-2 py-2 truncate border-2 border-gray-300">
                    Original Start Date
                  </th>
                  <th class="px-2 py-2 truncate border-2 border-gray-300">
                    Movement Date
                  </th>
                  <th class="px-2 py-2 truncate border-2 border-gray-300">
                    Weeks Range
                  </th>
                  <th class="px-2 py-2 truncate border-2 border-gray-300">
                    Within SLA?
                  </th>
                  <th class="px-2 py-2 truncate border-2 border-gray-300">
                    Agreed Start Date
                  </th>
                  <th class="px-2 py-2 truncate border-2 border-gray-300">
                    Requested by
                  </th>
                  <th class="px-2 py-2 truncate border-2 border-gray-300">
                    Approved by
                  </th>
                  <th class="px-2 py-2 truncate border-2 border-gray-300">
                    Transaction Type
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="classes2 in classes2"
                  :key="classes2.id"
                  class="border-2 border-black"
                >
                  <td
                    class="px-4 py-2 font-semibold text-left truncate border-2 border-gray-300"
                  >
                    {{ classes2.pushedback_id }}
                  </td>
                  <td
                    class="px-4 py-2 font-semibold text-left truncate border-2 border-gray-300"
                  >
                    {{ classes2.site.name }}
                  </td>
                  <td
                    class="px-4 py-2 font-semibold text-left truncate border-2 border-gray-300"
                  >
                    {{ classes2.program.name }}
                  </td>
                  <td
                    class="px-4 py-2 font-semibold text-left truncate border-2 border-gray-300"
                  >
                    {{ classes2.type_of_hiring }}
                  </td>
                  <td
                    class="px-4 py-2 font-semibold text-left truncate border-2 border-gray-300"
                  >
                    {{ classes2.total_target }}
                  </td>
                  <td
                    class="px-4 py-2 font-semibold text-left truncate border-2 border-gray-300"
                  >
                    {{ classes2.original_start_date }}
                  </td>
                  <td
                    class="px-4 py-2 font-semibold text-left truncate border-2 border-gray-300"
                  >
                    {{ classes2.wfm_date_requested }}
                  </td>
                  <td
                    class="px-4 py-2 font-semibold text-left truncate border-2 border-gray-300"
                  >
                    {{ classes2.date_range.date_range }}
                  </td>
                  <td
                    class="px-4 py-2 font-semibold text-left truncate border-2 border-gray-300"
                  >
                    {{ classes2.within_sla }}
                  </td>
                  <td
                    class="px-4 py-2 font-semibold text-left truncate border-2 border-gray-300"
                  >
                    {{ classes2.agreed_start_date }}
                  </td>
                  <td
                    class="px-4 py-2 font-semibold text-left truncate border-2 border-gray-300"
                  >
                    {{ classes2.requested_by }}
                  </td>
                  <td
                    class="px-4 py-2 font-semibold text-left truncate border-2 border-gray-300"
                  >
                    {{ classes2.approved_by }}
                  </td>
                  <td
                    class="px-4 py-2 font-semibold text-left truncate border-2 border-gray-300"
                  >
                    {{ classes2.changes }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="py-0">
    <div class="mb-4 md:flex md:space-x-2 md:items-center">
      <div class="relative w-full md:w-1/4">
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
      <div class="w-full md:w-1/4">
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
      <div class="w-full md:w-1/4">
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
      <div class="w-full md:w-1/4">
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
      <div class="w-full md:w-1/4">
        <button
          type="button"
          class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg"
          @click="getClassesAll"
        >
          Filter
        </button>
      </div>
      <div class="w-full md:w-1/4">
        <button
          type="button"
          class="w-full px-4 py-2 text-white bg-green-600 rounded-lg"
          @click="exportToExcel"
        >
          Export
        </button>
      </div>
      <div class="w-full md:w-1/4">
        <button
          type="button"
          class="w-full px-4 py-2 text-white bg-red-500 rounded-lg"
          @click="resetFilter"
        >
          Reset Filters
        </button>
      </div>
    </div>
  </div>

  <div class="py-1">
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
import jszip from "jszip";
// eslint-disable-next-line no-unused-vars
import "bootstrap/dist/css/bootstrap.css";

DataTable.use(DataTableLib);
DataTable.use(jszip);
DataTable.use(ButtonsHtml5);

export default {
  components: { DataTable },
  data() {
    return {
      classes2: [],
      classes: [],
      grandTotal: [],
      grandTotal2: [],
      sites: [],
      programs: [],
      daterange: [],
      sites_selected: "",
      programs_selected: "",
      month_selected: "",
      week_selected: "",
      showModal: false,
      showModalEdit: false,
      pipeline_offered: "",
      pipeline_utilized: "",
      loading: "",
      editId: null,
      columns: [
        { data: "id", title: "ID" },
        {
          data: "id",
          title: "Actions",
          render: (data) => {
            const isUser = this.isUser;
            const isSourcing = this.isSourcing;

            return `
      ${
        isUser || isSourcing
          ? `<button class="text-xs w-30 btn btn-primary" data-id="${data}" onclick="window.vm.openModalForHistory(${data})">View History</button>`
          : ""
      }
      ${
        isUser
          ? `<button class="text-xs w-30 btn btn-primary" data-id="${data}" onclick="window.vm.openModalForEdit(${data})">Edit</button>`
          : ""
      }
    `;
          },
        },

        { data: "country", title: "Country" },
        { data: "region", title: "Region" },
        { data: "site_name", title: "Site" },
        { data: "program_name", title: "Program" },
        { data: "date_range", title: "Hiring Week" },
        { data: "external_target", title: "External Target" },
        { data: "internal_target", title: "Internal Target" },
        { data: "total_target", title: "Total Target" },
        { data: "within_sla", title: "Within SLA" },
        { data: "condition", title: "Condition" },
        { data: "original_start_date", title: "Original Start Date" },
        { data: "changes", title: "Changes" },
        { data: "agreed_start_date", title: "Agreed Start Date" },
        { data: "wfm_date_requested", title: "WFM Date Requested" },
        { data: "notice_weeks", title: "Notice Weeks" },
        { data: "notice_days", title: "Notice Days" },
        { data: "pipeline_utilized", title: "Pipeline Utilized" },
        { data: "remarks", title: "Remarks" },
        { data: "status", title: "Status" },
        { data: "category", title: "Category" },
        { data: "type_of_hiring", title: "Type of Hiring" },
        { data: "with_erf", title: "With ERF" },
        { data: "erf_number", title: "ERF Number" },
        { data: "wave_no", title: "Wave No" },
        { data: "ta", title: "TA" },
        { data: "wf", title: "WF" },
        { data: "tr", title: "TR" },
        { data: "cl", title: "CL" },
        { data: "op", title: "OP" },
        { data: "approved_by", title: "Approved By" },
        { data: "cancelled_by", title: "Cancelled By" },
        { data: "requested_by", title: "Requested By" },
        { data: "created_by", title: "Created By" },
        { data: "updated_by", title: "Updated By" },
        { data: "approved_date", title: "Approved Date" },
        { data: "cancelled_date", title: "Cancelled Date" },
        { data: "created_at", title: "Created Date" },
        { data: "updated_at", title: "Updated Date" },
      ],
    };
  },
  watch: {
    sites_selected: "getClassesAll",
    programs_selected: "getClassesAll",
    month_selected: "getClassesAll",
    week_selected: "getClassesAll",
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
    EmptySelection() {
      return (
        !this.sites_selected || !this.programs_selected || !this.week_selected
      );
    },
  },

  mounted() {
    window.vm = this;
    this.getClassesAll();

    this.getSites();
    this.getPrograms();
    this.getDateRange();
  },
  methods: {
    openModalForHistory(id) {
      this.historyId = id;
      this.showModal = true;
      this.getTransaction(id);
    },
    openModalForEdit(id) {
      this.editId = id;
      this.showModalEdit = true;
      this.getClasses(id);
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
    },
    async exportToExcel() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/cancelled_export",
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
            responseType: "blob",
          }
        );

        // Check if the response contains data
        if (response && response.data) {
          // Create a Blob from the response data
          const blob = new Blob([response.data], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          });

          // Create a URL for the Blob
          const url = window.URL.createObjectURL(blob);

          // Create a link element and trigger a download
          const link = document.createElement("a");
          link.href = url;
          link.download = "filtered_cancelled_class_data.xlsx";
          document.body.appendChild(link); // Append the link to the body
          link.click(); // Simulate a click on the link
          document.body.removeChild(link); // Remove the link from the body after download
          window.URL.revokeObjectURL(url); // Revoke the URL to free up memory
        } else {
          console.error("Empty response received when exporting data to Excel");
        }
      } catch (error) {
        console.error("Error exporting filtered data to Excel", error);
      }
    },
    async getClasses(id) {
      try {
        const token = this.$store.state.token;
        const headers = {
          Authorization: `Bearer ${token}`,
        };

        const response = await axios.get(
          `http://127.0.0.1:8000/api/classes/${id}`,
          { headers }
        );

        if (response.status === 200) {
          const data = response.data;
          const classObj = data.class;
          this.pipeline_offered = classObj.pipeline_offered;
          this.pipeline_utilized = classObj.pipeline_utilized;

          console.log(classObj);
        } else {
          console.log("Error fetching classes");
        }
      } catch (error) {
        console.log(error);
      }
    },

    editClasses(id) {
      const token = this.$store.state.token;
      const headers = {
        Authorization: `Bearer ${token}`,
      };
      this.loading = true;
      const formData = {
        pipeline_utilized: this.pipeline_utilized,
        pipeline_offered: this.pipeline_offered,
      };

      axios
        .put(
          `http://127.0.0.1:8000/api/classes/cancelled/edit/${id}`,
          formData,
          {
            headers,
          }
        )
        .then((response) => {
          console.log(response.data);
          this.pipeline_utilized = "";
          this.pipeline_offered = "";
        })
        .catch((error) => {
          console.log(error.response.data);
        })
        .finally(() => {
          this.loading = false;
          this.showModalEdit = false;
        });
    },

    async getTransaction(id) {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          `http://127.0.0.1:8000/api/transaction/${id}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        this.classes2 = response.data.classes;
        console.log(response.data.classes);
      } catch (error) {
        console.log(error);
      }
    },
    async getClassesAll() {
      console.log("Sites Selected:", this.sites_selected);
      console.log("Programs Selected:", this.programs_selected);
      console.log("Month Selected:", this.month_selected);
      console.log("Week Selected:", this.week_selected);
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/classescancelled",
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
        } else {
          console.error(
            "Error fetching classes. Status code:",
            response.status
          );
        }
      } catch (error) {
        console.error("An error occurred:", error);
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
/* Updated Radio Button Styles */
input[type="radio"] {
  /* Hide the default radio button */
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  width: 16px;
  height: 16px;
  border: 2px solid #ccc;
  border-radius: 50%;
  outline: none;
  margin-right: 8px;
  cursor: pointer;
  position: relative;
}

input[type="radio"]:checked {
  /* Add custom styling for the checked state */
  border-color: #3b71ca; /* Blue color for checked state */
}

input[type="radio"]:checked::before {
  /* Add the blue dot inside the checked radio button */
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: #3b71ca; /* Blue color for the dot */
}

/* Optional: Increase the size of the radio button and the blue dot */
input[type="radio"] {
  width: 20px;
  height: 20px;
}

input[type="radio"]:checked::before {
  width: 10px;
  height: 10px;
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
