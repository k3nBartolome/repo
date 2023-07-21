<template>
    <header class="w-full">
        <div class="flex items-center w-full max-w-screen-xl sm:px-2 lg:px-2">
          <h1 class="pl-8 text-sm font-bold tracking-tight text-gray-900">
            Supply Manager
          </h1>
        </div>
    </header>
    <div class="py-1">
        <div
          class="px-1 py-6 mx-auto bg-white  max-w-7xl sm:px-6 lg:px-8"
        >
          <form
            class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-4"
          >
            <div class="col-span-6 md:col-span-1">
              <label class="block">
                Site
                <select
                  v-model="sites_selected"
                  class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                  @change="getPrograms"
                >
                  <option disabled value="" selected>Please select one</option>
                  <option v-for="site in sites" :key="site.id" :value="site.id">
                    {{ site.name }}
                  </option>
                </select>
              </label>
            </div>
            <div class="col-span-4 md:col-span-1">
                <label class="block">
                Item Name
                <input
                type="text"
                v-model="item_name"
                
                class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              />
            </label>
            </div>
            <div class="col-span-4 md:col-span-1">
                <label class="block">
                Quantity
                <input
                type="number"
                v-model="quantity"
                
                class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              /></label>
            </div>
            <div class="col-span-4 md:col-span-1">
                <label class="block">
                Budget Code
                <input
                type="number"
                v-model="budget_code"
                
                class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              /></label>
            </div>
            <div class="col-span-4 md:col-span-1">
                <label class="block">
                Type
                <input
                type="number"
                v-model="type"
                
                class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              /></label>
            </div>
            <div class="col-span-4 md:col-span-1">
                <label class="block">
                Expiration Date
                <input
                type="date"
                v-model="date_expiry"
                
                class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              /></label>
            </div>
            <div class="col-span-4 md:col-span-1">
                <label class="block">
                    Category
                    <select
                      required
                      v-model="category"
                      class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                    >
                      <option disabled value="" selected>Please select one</option>
                      <option value="Normal Item">Normal Item</option>
                      <option value="Premium">Premium</option>
                    </select>
                  </label>
            </div>
            <div class="col-span-4 md:col-span-1">
                <label class="block">
                    Description
                    <input type="text"
                      required
                      v-model="description"
                      class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                    >
                  </label>
            </div>
            
        </form>
        </div>
    </div>
    <div class="py-2">
        <div class="pl-8 pr-8">
          <div class="scroll">
            <div class="w-2/3 mx-auto datatable-container">
              <DataTable
                :data="items"
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
          items: [],
          sites_selected: "",
          item_name: "",
          quantity: "",
          budget_code: "",
          type: "",
          category: "",
          description: "",
          date_expiry: "",
          columns: [
            { data: "id", title: "ID" },
            { data: "site.name", title: "Site" },
            { data: "item_name", title: "Item" },
            { data: "quantity", title: "Quantity" },
            { data: "budget_code", title: "Budget Code" },
            { data: "type", title: "Type" },
            { data: "category", title: "Category" },
            { data: "description", title: "Description" },
            { data: "date_expiry", title: "Expiration Date" },
            { data: "created_at", title: "Added Date" },
            { data: "created_by_user.name", title: "Added By" },
          ],
        };
      },
      computed: {
        
      },
      mounted() {
        window.vm = this;
        this.getSites();
        
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