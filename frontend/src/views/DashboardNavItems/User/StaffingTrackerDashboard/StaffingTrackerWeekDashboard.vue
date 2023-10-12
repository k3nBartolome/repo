<template>
  <div class="p-4">
    <div class="mb-4 flex flex-wrap space-y-2 md:space-y-0 md:space-x-2">
      <input
        v-model="filterLastName"
        placeholder="Filter by Last Name"
        class="p-2 border rounded-lg"
      />
      <input
        v-model="filterFirstName"
        placeholder="Filter by First Name"
        class="p-2 border rounded-lg"
      />
      <input
        v-model="filterMiddleName"
        placeholder="Filter by Middle Name"
        class="p-2 border rounded-lg"
      />
      <input
        v-model="filterContact"
        placeholder="Filter by Mobile No."
        class="p-2 border rounded-lg"
      />
      <button @click="fetchData" class="px-4 py-2 bg-blue-500 text-white rounded-lg">
        Filter
      </button>
      <button @click="exportToExcel" class="px-4 py-2 bg-blue-500 text-white rounded-lg">
        Export
      </button>
    </div>

    <DataTable
      :data="perx"
      :columns="columns"
      class="table divide-y divide-gray-200 table-auto table-striped"
      :options="{
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        lengthChange: true,
        ordering: true,
        scrollX: true,
        dom: 'rtlip',
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
          <!-- Add your table headers here -->
        </tr>
      </thead>
      <tbody class="truncate">
        <tr v-for="item in perx" :key="item.id">
          <!-- Add your table row data here -->
        </tr>
      </tbody>
    </DataTable>
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
        filterLastName: "",
        filterFirstName: "",
        filterMiddleName: "",
        filterContact:"",
        perx: [],
        columns: [
  { data: "ApplicantId", title: "ApplicantId" },
  { data: "ApplicationInfoId", title: "ApplicationInfoId" },
  { data: "DateOfApplication", title: "DateOfApplication" },
  { data: "LastName", title: "LastName" },
  { data: "FirstName", title: "FirstName" },
  { data: "MiddleName", title: "MiddleName" },
  { data: "MobileNo", title: "MobileNo" },
  { data: "Site", title: "Site" },
  { data: "GenSource", title: "GenSource" },
  { data: "SpecSource", title: "SpecSource" },
  { data: "Step", title: "Step" },
  { data: "AppStep", title: "AppStep" },
  { data: "PERX_HRID", title: "PERX_HRID" },
  { data: "PERX_Last_Day", title: "PERX_Last_Day" },
  { data: "PERX_Retract", title: "PERX_Retract" },
  { data: "PERX_NAME", title: "PERX_NAME" },
  { data: "OSS_HRID", title: "OSS_HRID" },
  { data: "OSS_Last_Day", title: "OSS_Last_Day" },
  { data: "OSS_Retract", title: "OSS_Retract" },
  { data: "OSS_FNAME", title: "OSS_FNAME" },
  { data: "OSS_LNAME", title: "OSS_LNAME" },
  { data: "OSS_LOB", title: "OSS_LOB" },
  { data: "OSS_SITE", title: "OSS_SITE" }
      ]
      };
    },
    methods: {
      async exportToExcel() {
    try {
        const token = this.$store.state.token;
        const response = await axios.get("http://i/export", {
            params: {
                filter_lastname: this.filterLastName,
                filter_firstname: this.filterFirstName,
                filter_middlename: this.filterMiddleName,
                filter_contact: this.filterContact,
            },
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        this.perx = response.data.perx;
        } catch (error) {
          console.error("Error Exporting data", error);
        }
},

      async fetchData() {
    console.log("Filter LastName:", this.filterLastName);
    console.log("Filter FirstName:", this.filterFirstName);
    console.log("Filter MiddleName:", this.filterMiddleName);
        try {
          const token = this.$store.state.token;
          const response = await axios.get("http://i/perxfilter", {
            params: {
              filter_lastname: this.filterLastName,
              filter_firstname: this.filterFirstName,
              filter_middlename: this.filterMiddleName,
              filter_contact: this.filterContact,
            },
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });

          this.perx = response.data.perx;
        } catch (error) {
          console.error("Error fetching data", error);
        }
      },
    },
  };
  </script>