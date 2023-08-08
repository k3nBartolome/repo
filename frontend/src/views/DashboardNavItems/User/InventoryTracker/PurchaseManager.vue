<template>
  <div class="px-2 pt-1 border-b border-gray-200 dark:border-gray-700">
    <ul class="flex -mb-px text-sm font-medium text-center">
      <router-link to="/purchase_manager/pending">
      <li class="mr-2" role="presentation">
        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 focus:outline-none" type="button" role="tab" >Pending Request</button>
      </li>
    </router-link>
    <router-link to="/purchase_manager/approved">
      <li class="mr-2" role="presentation">
        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 focus:outline-none" type="button" role="tab" >Approved Request</button>
      </li>
    </router-link>
    <router-link to="/purchase_manager/denied">
      <li role="presentation">
        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 focus:outline-none"  type="button" role="tab" >Denied Request</button>
      </li>
    </router-link>
    </ul>
  </div>
  <main class="flex flex-col h-screen">
    <div class="flex flex-1 px-4 py-2 md:px-1 ">
      <div class="w-full py-6 ">
        <router-view />
      </div>
    </div>
  </main>
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
          type: "Non-Food",
          category: "Normal",
          date_expiry: "",
          showModal: false,
          columns: [
            { data: "id", title: "ID" },
            { data: "site.name", title: "Site" },
            { data: "item_name", title: "Item" },
            { data: "quantity", title: "Quantity" },
            { data: "budget_code", title: "Budget Code" },
            { data: "type", title: "Type" },
            { data: "category", title: "Category" },
            { data: "date_expiry", title: "Expiration Date" },
            { data: "created_at", title: "Added Date" },
            { data: "created_by.name", title: "Added By" },
          ],
        };
      },
      computed: {

      },
      mounted() {
        window.vm = this;
        this.getSites();
        this.getItems();

      },
      methods: {
        async getItems() {
          try {
            const token = this.$store.state.token;
            const response = await axios.get("http://10.109.2.112:8081/api/items", {
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
        addItems() {
  const formData = {
    item_name: this.item_name,
    quantity: this.quantity,
    type: this.type,
    category: this.category,
    budget_code: this.budget_code,
    date_expiry: this.date_expiry,
    site_id: this.sites_selected,
    is_active: 1,
    created_by: this.$store.state.user_id,
  };
  axios
    .post("http://10.109.2.112:8081/api/items", formData, {
      headers: {
        Authorization: `Bearer ${this.$store.state.token}`,
      },
    })
    .then((response) => {
      console.log(response.data);
      this.item_name = "";
      this.quantity = "";
      this.sites_selected = "";
      this.type = "";
      this.category = "";
      this.budget_code = "";
      this.date_expiry = "";
    })
    .catch((error) => {
      console.log(error.response.data);
    });
}
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
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      max-width: 400px;
    }
input[type="radio"] {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  width: 16px;
  height: 16px;
  border: 2px solid #ccc;
  border-radius: 50%;
  outline: none;
  margin-right: 8px;
  cursor: pointer;
  position: relative;
}

input[type="radio"]:checked {
  border-color: #3b71ca;
}

input[type="radio"]:checked::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: #3b71ca; /
}
input[type="radio"] {
  width: 20px;
  height: 20px;
}

input[type="radio"]:checked::before {
  width: 10px;
  height: 10px;
}

    </style>
