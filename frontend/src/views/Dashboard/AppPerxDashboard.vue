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
          :data="classes"
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
      classes: [],
      sites: [],
      programs: [],
      daterange: [],
      sites_selected: "",
      programs_selected: "",
      month_selected: "",
      week_selected: "",
      columns: [
        { data: "id", title: "ID" },
        {
          data: "id",
          title: "Actions",
          orderable: false,
          searchable: false,
          render: function (data) {
            return `<button class="btn btn-primary text-xs w-40" data-id="${data}"  onclick="window.vm.navigateToEdit(${data})">Edit</button>
                    <button class="btn btn-secondary text-xs  w-40" data-id="${data}" onclick="window.vm.navigateToPushback(${data})">Pushback/Update</button>
                    <button class="btn btn-danger  w-40" data-id="${data}" onclick="window.vm.navigateToCancel(${data})">Cancel</button>
  `;
          },
        },
        { data: "site.country", title: "Country" },
        { data: "site.name", title: "Site" },
        { data: "program.name", title: "Program" },
        { data: "date_range.date_range", title: "Hiring Week" },
        { data: "total_target", title: "Total Target" },
        { data: "original_start_date", title: "Original Start Date" },
        { data: "type_of_hiring", title: "Type of Hiring" },
        { data: "created_at", title: "Created date" },
        { data: "created_by_user.name", title: "Created by" },

      ],
    };
  },
  computed: {
    classExists() {
      return this.classes.some((c) => {
        return (
          c.site.id === this.sites_selected &&
          c.program.id === this.programs_selected &&
          c.date_range.id === this.week_selected
        );
      });
    },
  },
  mounted() {
    window.vm = this;
    this.getSites();
    this.getPrograms();
    this.getDateRange();
    this.getClassesAll();
  },
  methods: {
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
      await axios
        .get("http://10.109.2.112:8081/api/classesall")
        .then((response) => {
          this.classes = response.data.classes;
          console.log(response.data.classes);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getSites() {
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
    async getPrograms() {
      if (!this.sites_selected) {
        return; // do nothing if no site is selected
      }

      await axios
        .get(`http://10.109.2.112:8081/api/programs_selected/${this.sites_selected}`)
        .then((response) => {
          this.programs = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.error(error);
        });
    },

    async getDateRange() {
      if (!this.month_selected) {
        return;
      }

      await axios
        .get(`http://10.109.2.112:8081/api/daterange_selected/${this.month_selected}`)
        .then((response) => {
          this.daterange = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
