<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h1 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Perx
      </h1>
    </div>
  </header>
  <div class="py-4">
    <div class="px-8">
      <div class="overflow-x-auto">
        <DataTable
          :data="perx"
          :columns="columns"
          class="min-w-full divide-y divide-gray-200 table table-striped"
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
      perx: [],
      columns: [
        { data: "DateOfApplication", title: "Date of Application" },
            { data: "LastName", title: "Lastname" },
            { data: "FirstName", title: "Firstname" },
            { data: "MiddleName", title: "MiddleName" },
            { data: "MobileNo", title: "Mobile#" },
            { data: "Site", title: "SITE" },
            { data: "GenSource", title: "Gen Source" },
            { data: "SpecSource", title: "Spec Source" },
            { data: "Step", title: "Step" },
            { data: "AppStep", title: "App Step" },
            { data: "PERX_HRID", title: "PERX HRID" },
            { data: "PERX_NAME", title: "PERX NAME" },
            { data: "OSS_HRID", title: "OSS HRID" },
            { data: "OSS_FNAME", title: "OSS FNAME" },
            { data: "OSS_LNAME", title: "OSS LNAME" },
            { data: "OSS_LOB", title: "OSS LOB" },
            { data: "OSS_SITE", title: "OSS SITE" },
],

    };
  },
  mounted() {
    window.vm = this;
    this.getPerx();
  },
  methods: {
    async getPerx() {
      await axios
        .get("http://127.0.0.1:8000/api/classes")
        .then((response) => {
          this.perx = response.data.perx;
          console.log(response.data.perx);
        })
        .catch((error) => {
          console.log(error);
        });
    },

  },
};
</script>
