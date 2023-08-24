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
          <h3>Awarded Items</h3>
          <DataTable
            :data="award"
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
      sites: [],
      award: [],
      items: [],
      inventory: [],
      items2: [],
      columns: [
        { data: "id", title: "ID" },
        { data: "site.name", title: "Site" },
        { data: "items.item_name", title: "Item Name" },
        { data: "awarded_quantity", title: "Awarded Quantity" },
        { data: "awardee_hrid", title: "Awardee ID" },
        { data: "awardee_name", title: "Awardee Name" },
        { data: "released_by.name", title: "Released By" },
        {
          data: "date_released",
          title: "Date Released",
          render: (data) => (data ? data.slice(0, -3) : ""),
        },
        { data: "remarks", title: "Remarks" },
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
    this.getAward();
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
    async getAward() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/awarded/both", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.award = response.data.awarded;
          console.log(response.data.awarded);
        } else {
          console.log("Error fetching awarded");
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
.dataTables_wrapper .dataTables_filter {
  float: left;
  padding-right: 30px;
}

.dataTables_wrapper .dataTables_Buttons {
  float: left;
  margin-top: 30px;
}

.dataTables_wrapper .dataTables_pagination {
  float: left;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.dataTables_wrapper .dataTables_length {
  float: left;
  padding-right: 15px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.dataTables_wrapper .dt-buttons .btn {
  background-color: #007bff;
  color: #fff;
  border-radius: 4px;
  padding: 8px 12px;
  margin-right: 8px;
  margin-top: 15px;
}
.card-small {
  padding: 0.75rem;
}

.card-small .card-title {
  font-size: 1rem;
}
</style>
