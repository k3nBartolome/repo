<template>
  <div class="py-1">
    <div class="pl-2 pr-2">
      <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
          <div class="card card-small">
            <div class="card-body">
              <h6 class="card-title">Total Awards</h6>
              <p class="card-text"></p>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="card card-small">
            <div class="card-body">
              <h6 class="card-title">Total Awards</h6>
              <p class="card-text"></p>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="card card-small">
            <div class="card-body">
              <h6 class="card-title">Total Awards</h6>
              <p class="card-text"></p>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="card card-small">
            <div class="card-body">
              <h6 class="card-title">Total Awards</h6>
              <p class="card-text"></p>
            </div>
          </div>
        </div>
      </div>
      <div class="scroll">
        <div class="w-2/3 mx-auto datatable-container">
          <h2>Site Inventory</h2>
          <DataTable
            :data="items2"
            :columns="columns"
            class="table divide-y divide-gray-200 table-auto table-striped"
            :options="{
              responsive: false,
              autoWidth: false,
              pageLength: 10,
              lengthChange: true,
              ordering: true,
              scrollX: true,
              dom: 'frtlip',
              buttons: ['excel', 'csv'],
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
              <tr></tr>
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
      sites: [],
      award: [],
      items: [],
      inventory: [],
      items2: [],
      columns: [
        { data: "id", title: "ID" },
        { data: "site.name", title: "Site" },
        { data: "item_name", title: "Item" },
        { data: "quantity", title: "Quantity" },
        { data: "cost", title: "Price" },
        { data: "total_cost", title: "Total Price" },
        { data: "budget_code", title: "Budget Code" },
        { data: "type", title: "Type" },
        { data: "category", title: "Category" },
        { data: "date_expiry", title: "Expiration Date" },
        {
          data: "date_added",
          title: "Added Date",
          render: (data) => (data ? data.slice(0, -3) : ""),
        },
      ],
    };
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
  watch: {},
  mounted() {
    window.vm = this;
    this.getSites();
    this.getItems2();
  },
  methods: {
    async getSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://10.109.2.112:8081/api/sites", {
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
    async getItems2() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://10.109.2.112:8081/api/itemsboth2", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.items2 = response.data.items;
          console.log(response.data.items);
        } else {
          console.log("Error fetching items");
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
