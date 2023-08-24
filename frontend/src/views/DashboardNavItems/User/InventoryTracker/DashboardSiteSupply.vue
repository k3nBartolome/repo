<template>
    <header class="w-full">
        <div class="flex items-center w-full max-w-screen-xl sm:px-2 lg:px-2">
          <h1 class="pl-8 text-sm font-bold tracking-tight text-gray-900">
            Report Dashboard
          </h1>
        </div>
    </header>
      <div class="flex flex-col h-screen p-8">
        <div class="flex-grow grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
          <div class="bg-white p-4 rounded shadow-md">
            <div class="scroll">
              <div class="w-2/3 mx-auto datatable-container">
                <h2>Awarded Items</h2>
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
                    <tr></tr>
                  </thead>
                </DataTable>
              </div>
            </div>
          </div>
          <div class="bg-white p-4 rounded shadow-md">
            <div class="scroll">
              <div class="w-2/3 mx-auto datatable-container">
                <h2>Supply Inventory</h2>
                <DataTable
                  :data="items"
                  :columns="columns2"
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
                    <tr></tr>
                  </thead>
                </DataTable>
              </div>
            </div>
          </div>
          <div class="bg-white p-4 rounded shadow-md">
            <div class="scroll">
              <div class="w-2/3 mx-auto datatable-container">
                <h2>Site Inventory</h2>
                <DataTable
                  :data="items2"
                  :columns="columns3"
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
                    <tr></tr>
                  </thead>
                </DataTable>
              </div>
            </div>
          </div>
          <div class="bg-white p-4 rounded shadow-md">
            <div class="scroll">
              <div class="w-2/3 mx-auto datatable-container">
                <h2>Requests</h2>
                <DataTable
                  :data="inventory"
                  :columns="columns4"
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
                    <tr></tr>
                  </thead>
                </DataTable>
              </div>
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
        { data: "items.item_name", title: "Item Name" },
        { data: "awarded_quantity", title: "Awarded Quantity" },
        { data: "awardee_hrid", title: "Awardee ID" },
        { data: "awardee_name", title: "Awardee Name" },
        { data: "released_by.name", title: "Released By" },
        { data: "date_released", title: "Date Released",
    render: (data) => data ? data.slice(0, -3) : "", },
        { data: "remarks", title: "Remarks" },
        
      ],
      columns2: [
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
    render: (data) => data ? data.slice(0, -3) : "",
  },
  
        { data: "created_by.name", title: "Added By" },
      ],
      columns3: [
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
    render: (data) => data ? data.slice(0, -3) : "",
  },
  
        
      ],
      columns4: [
        { data: "id", title: "ID" },
        { data: "site.name", title: "Site" },
        { data: "item.item_name", title: "Item Name" },
        { data: "item.budget_code", title: "Budget Code" },
        { data: "quantity_approved", title: "Quantity Requested" },
        { data: "status", title: "Approval Status" },
        { data: "requested_by.name", title: "Requested By" },
        //{ data: "approved_by.name", title: "Approved By" },
       // { data: "denied_by.name", title: "Denied By" },
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
     this.getItems();
    this.getItems2();
     this.getAward();
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
    async getItems() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/itemsboth", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.items = response.data.items;
          console.log(response.data.items);
        } else {
          console.log("Error fetching items");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getItems2() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/itemsboth2", {
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