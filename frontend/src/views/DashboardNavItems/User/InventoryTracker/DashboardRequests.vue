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
                <h2>Requests</h2>
                <DataTable
                  :data="inventory"
                  :columns="columns"
                  class="table divide-y divide-gray-200 table-auto table-striped"
                  :options="{
                    responsive: false,
                    autoWidth: false,
                    pageLength: 10,
                    lengthChange: true,
                    ordering: true,
                    scrollX: true,
                    dom: 'fBrtlip',
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
        { data: "item.item_name", title: "Item Name" },
        { data: "item.budget_code", title: "Budget Code" },
        { data: "quantity_approved", title: "Quantity Requested" },
        { data: "status", title: "Approval Status" },
        { data: "requested_by.name", title: "Requested By" },
        { data: "approved_by.name", title: "Approved By" },
        {
    data: "denied_by.name",
    title: "Denied By",
    render: (data, type, row) => {
      return row.denied_by ? row.denied_by.name : "N/A";
    },
  },
        { data: "denial_reason", title: "Denial Reason" },
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
  watch: {
    
    
  },
  mounted() {
    window.vm = this;
    this.getSites();
     this.getInventory();
  },
  methods: {
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
    async getInventory() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/inventoryall", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.inventory = response.data.inventory;
          console.log(response.data.inventory);
        } else {
          console.log("Error fetching inventory");
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
