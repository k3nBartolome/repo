<template>
  <DataTable
    :data="classes"
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
      <tr>
        <!-- Add your table row data here -->
      </tr>
    </tbody>
  </DataTable>
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
      classes: [],
      grandTotal: [],
      grandTotal2: [],
      columns: [{ data: "site.region", title: "Region" },
      { data: "site.name", title: "Site" },
      { data: "program.name", title: "Program" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      { data: "site.name", title: "Site" },
      
    ],
    };
  },
  computed: {},
  mounted() {
    this.getClassesAll();
  },
  methods: {
    async getClassesAll() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/classesdashboard2",
          {
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
  },
};
</script>
