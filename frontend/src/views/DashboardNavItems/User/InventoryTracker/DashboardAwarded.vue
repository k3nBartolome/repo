<template>
  <div class="py-0">
    <div class="pl-2 pr-2">
      <div class="flex flex-wrap mb-2">
        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
          <div class="p-2 bg-green-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-shrink pl-1 pr-4">
                <i class="fa fa-wallet fa-2x fa-fw fa-inverse"></i>
              </div>
              <div class="flex-1 text-right">
                <h5 class="text-white">Total Request</h5>
                <h3 class="text-3xl text-white">
                  {{ filteredTotalRequest }}<span class="text-green-400"></span>
                </h3>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
          <div class="p-2 bg-blue-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-shrink pl-1 pr-4">
                <i class="fa fa-wallet fa-2x fa-fw fa-inverse"></i>
              </div>
              <div class="flex-1 text-right">
                <h5 class="text-white">Total Approved</h5>
                <h3 class="text-3xl text-white">{{ filteredTotalApproved }}</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
          <div class="p-2 bg-orange-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-shrink pl-1 pr-4">
                <i class="fa fa-wallet fa-2x fa-fw fa-inverse"></i>
              </div>
              <div class="flex-1 text-right">
                <h5 class="text-white">Total Denied</h5>
                <h3 class="text-3xl text-white">{{ filteredTotalDenied }}</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
          <div class="p-2 bg-purple-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-shrink pl-1 pr-4">
                <i class="fa fa-wallet fa-2x fa-fw fa-inverse"></i>
              </div>
              <div class="flex-1 text-right">
                <h5 class="text-white">Total Pending</h5>
                <h3 class="text-3xl text-white">{{ filteredTotalPending }}</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
          <div class="p-2 bg-red-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-shrink pl-1 pr-4">
                <i class="fa fa-wallet fa-2x fa-fw fa-inverse"></i>
              </div>
              <div class="flex-1 text-right">
                <h5 class="text-white">Partial</h5>
                <h3 class="text-3xl text-white">
                  {{ filteredTotalPartialReceived }}
                </h3>
              </div>
            </div>
          </div>
        </div>

        <div class="w-full px-1 py-3 sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6">
          <div class="p-2 bg-yellow-600 border rounded shadow card-stats">
            <div class="flex flex-row items-center">
              <div class="flex-shrink pl-1 pr-4">
                <i class="fa fa-wallet fa-2x fa-fw fa-inverse"></i>
              </div>
              <div class="flex-1 text-right">
                <h5 class="text-white">Complete</h5>
                <h3 class="text-3xl text-white">
                  {{ filteredTotalCompleteReceived }}
                </h3>
              </div>
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
        {
          data: "image_path",
          title: "Image",
          render: (data, type) => {
            if (type === "display" && data) {
              return `<img src="${data}" alt="Image" width="50" height="50" loading="lazy"/>`;
            }
            return "";
          },
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
.card-stats {
  width: 100%;
  max-width: 400px;
  margin-bottom: 20px;
}

.card-stats .card-body {
  padding: 10px;
}
.link-button {
  text-decoration: none;
}
</style>
