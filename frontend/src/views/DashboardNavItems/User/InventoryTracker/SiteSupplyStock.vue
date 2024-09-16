<template>
  <header class="w-full">
    <h2 class="pb-8 pl-8 font-bold tracking-tight text-gray-900">
      Sourcing Supply Manager
    </h2>
    <div class="flex items-center w-full max-w-screen-xl sm:px-2 lg:px-2">
      <h2 class="pl-8 text-sm font-bold tracking-tight text-gray-900">
        <button
          v-if="isUser || isSourcing"
          @click="showModalSupply = true"
          class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
        >
          ADD Supply
        </button>
      </h2>
    </div>
  </header>
  <div class="py-0`">
    <div class="px-0 py-1 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
      <div
        class="fixed inset-0 z-50 flex items-center justify-center modal"
        v-if="showModal"
      >
        <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
        <div class="max-w-sm p-4 bg-white rounded shadow-lg modal-content">
          <header class="px-4 py-2 border-b-2 border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Transfer Supply</h2>
          </header>
          <button
            @click="showModal = false"
            class="absolute top-0 right-0 m-4 text-gray-600 hover:text-gray-800"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
          <span v-if="successMessage" class="text-green-500">{{
            successMessage
          }}</span>
          <div class="modal-scrollable-content">
            <form
              @submit.prevent="transferItems"
              class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-1"
            >
              <div class="col-span-1">
                <label class="block">
                  <input type="file" @change="handleFileChange" />
                  <img :src="previewImage" v-if="previewImage" alt="Preview" />
                  <p v-if="errors.file_name" class="mt-1 text-xs text-red-500">
                    {{ errors.file_name }}
                  </p>
                </label>
              </div>
              <div class="col-span-1">
                <label class="block">
                  Site
                  <select
                    v-model="sites_selected"
                    class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                  >
                    <option disabled value="" selected>
                      Please select one
                    </option>
                    <option
                      v-for="site in sites"
                      :key="site.id"
                      :value="site.id"
                    >
                      {{ site.name }}
                    </option>
                  </select>
                  <p
                    v-if="errors.sites_selected"
                    class="mt-1 text-xs text-red-500"
                  >
                    {{ errors.sites_selected }}
                  </p>
                </label>
              </div>
              <div class="hidden col-span-1">
                <label class="block">
                  Site
                  <select
                    v-model="sites1_selected"
                    class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                  >
                    <option disabled value="" selected>
                      Please select one
                    </option>
                    <option
                      v-for="site in sites"
                      :key="site.id"
                      :value="site.id"
                    >
                      {{ site.name }}
                    </option>
                  </select>
                  <p
                    v-if="errors.sites_selected"
                    class="mt-1 text-xs text-red-500"
                  >
                    {{ errors.sites_selected }}
                  </p>
                </label>
              </div>
              <div class="hidden col-span-1">
                <label class="block">
                  Item Name
                  <select
                    @change="onItemSelected"
                    v-model="items_selected"
                    class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                  >
                    <option disabled value="" selected>
                      Please select one
                    </option>
                    <option
                      v-for="item in items"
                      :key="item.id"
                      :value="item.id"
                    >
                      {{ item.item_name }}
                    </option>
                  </select>
                  <p
                    v-if="errors.items_selected"
                    class="mt-1 text-xs text-red-500"
                  >
                    {{ errors.items_selected }}
                  </p>
                </label>
              </div>
              <div class="col-span-1">
                <label class="block">
                  Quantity Available
                  <input
                    readonly
                    type="number"
                    v-model="quantity"
                    class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                  />
                  <p v-if="errors.quantity" class="mt-1 text-xs text-red-500">
                    {{ errors.quantity }}
                  </p>
                </label>
              </div>
              <div class="col-span-1">
                <label class="block">
                  Quantity to be Transfer
                  <input
                    type="number"
                    v-model="transferred_quantity"
                    class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                  />
                  <p
                    v-if="errors.transferred_quantity"
                    class="mt-1 text-xs text-red-500"
                  >
                    {{ errors.transferred_quantity }}
                  </p>
                </label>
              </div>
              <div class="flex justify-between mt-4">
                <button
                  @click="showModal = false"
                  type="button"
                  class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
                >
                  Submit
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="px-4 py-0">
      <div class="px-4 py-0 bg-white">
        <div
          class="fixed inset-0 z-50 flex items-center justify-center mx-4 modal"
          v-if="showModalTransaction"
        >
          <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
          <div
            class="w-auto max-w-3xl min-w-full p-4 px-4 bg-white rounded shadow-lg modal-content"
          >
            <header class="px-4 py-2 border-b-2 border-gray-200">
              <h2 class="text-lg font-semibold text-gray-800">
                Item Transaction
              </h2>
            </header>
            <button
              @click="showModalTransaction = false"
              class="absolute top-0 right-0 m-4 text-gray-600 hover:text-gray-800"
            >
              <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                ></path>
              </svg>
            </button>
            <div class="modal-scrollable-content">
              <table
                class="min-w-full border-2 border-collapse border-gray-300"
              >
                <thead>
                  <tr
                    class="text-center bg-gray-100 border-b-4 border-gray-300"
                  >
                    <th class="px-2 py-2 truncate border-2 border-gray-300">
                      Transaction Type
                    </th>
                    <th class="px-2 py-2 truncate border-2 border-gray-300">
                      Quantity
                    </th>
                    <th class="px-2 py-2 truncate border-2 border-gray-300">
                      Transferred Quantity
                    </th>
                    <th class="px-2 py-2 truncate border-2 border-gray-300">
                      Transferred To
                    </th>
                    <th class="px-2 py-2 truncate border-2 border-gray-300">
                      Transferred From
                    </th>
                    <th class="px-2 py-2 truncate border-2 border-gray-300">
                      Status
                    </th>
                    <th class="px-2 py-2 truncate border-2 border-gray-300">
                      Received Status
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="inventory in inventory"
                    :key="inventory.id"
                    class="border-2 border-black"
                  >
                    <th class="px-2 py-2 truncate border-2 border-gray-300">
                      {{ inventory.transaction_type }}
                    </th>
                    <th class="px-2 py-2 truncate border-2 border-gray-300">
                      {{ inventory.original_request }}
                    </th>
                    <th class="px-2 py-2 truncate border-2 border-gray-300">
                      {{ inventory.received_quantity }}
                    </th>
                    <th class="px-2 py-2 truncate border-2 border-gray-300">
                      {{ inventory.transferred_from }}
                    </th>

                    <th class="px-2 py-2 truncate border-2 border-gray-300">
                      {{ inventory.transferred_to }}
                    </th>
                    <th class="px-2 py-2 truncate border-2 border-gray-300">
                      {{ inventory.approved_status }}
                    </th>
                    <th class="px-2 py-2 truncate border-2 border-gray-300">
                      {{ inventory.received_status }}
                    </th>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="py-0">
      <div class="px-1 py-0 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
        <div
          class="fixed inset-0 z-50 flex items-center justify-center modal"
          v-if="showModalSupply"
        >
          <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
          <div class="max-w-sm p-4 bg-white rounded shadow-lg modal-content">
            <div v-if="loading" class="loader">
              <div aria-label="Loading..." role="status" class="loader">
                <svg class="icon" viewBox="0 0 256 256">
                  <line
                    x1="128"
                    y1="32"
                    x2="128"
                    y2="64"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="24"
                  ></line>
                  <line
                    x1="195.9"
                    y1="60.1"
                    x2="173.3"
                    y2="82.7"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="24"
                  ></line>
                  <line
                    x1="224"
                    y1="128"
                    x2="192"
                    y2="128"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="24"
                  ></line>
                  <line
                    x1="195.9"
                    y1="195.9"
                    x2="173.3"
                    y2="173.3"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="24"
                  ></line>
                  <line
                    x1="128"
                    y1="224"
                    x2="128"
                    y2="192"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="24"
                  ></line>
                  <line
                    x1="60.1"
                    y1="195.9"
                    x2="82.7"
                    y2="173.3"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="24"
                  ></line>
                  <line
                    x1="32"
                    y1="128"
                    x2="64"
                    y2="128"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="24"
                  ></line>
                  <line
                    x1="60.1"
                    y1="60.1"
                    x2="82.7"
                    y2="82.7"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="24"
                  ></line>
                </svg>
                <span class="loading-text">Loading...</span>
              </div>
            </div>
            <header class="px-4 py-2 border-b-2 border-gray-200">
              <h2 class="text-lg font-semibold text-gray-800">Add Supply</h2>
            </header>
            <button
              @click="showModalSupply = false"
              class="absolute top-0 right-0 m-4 text-gray-600 hover:text-gray-800"
            >
              <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                ></path>
              </svg>
            </button>
            <span v-if="successMessage" class="text-green-500">{{
              successMessage
            }}</span>
            <div class="modal-scrollable-content">
              <form
                @submit.prevent="addItems"
                class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-1"
              >
                <div class="col-span-1">
                  <label class="block">
                    <input type="file" @change="handleFileChange" />
                    <img
                      :src="previewImage"
                      v-if="previewImage"
                      alt="Preview"
                    />
                    <p
                      v-if="errors.file_name"
                      class="mt-1 text-xs text-red-500"
                    >
                      {{ errors.file_name }}
                    </p>
                  </label>
                </div>
                <div class="col-span-1">
                  <label class="block">
                    Site
                    <select
                      v-model="sites_selected"
                      class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                    >
                      <option disabled value="" selected>
                        Please select one
                      </option>
                      <option
                        v-for="site in sites"
                        :key="site.id"
                        :value="site.id"
                      >
                        {{ site.name }}
                      </option>
                    </select>
                    <p
                      v-if="errors.sites_selected"
                      class="mt-1 text-xs text-red-500"
                    >
                      {{ errors.sites_selected }}
                    </p>
                  </label>
                </div>
                <div class="col-span-1">
                  <label class="block">
                    Item Name
                    <input
                      type="text"
                      v-model="item_name"
                      class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                    />
                    <p
                      v-if="errors.item_name"
                      class="mt-1 text-xs text-red-500"
                    >
                      {{ errors.item_name }}
                    </p>
                  </label>
                </div>
                <div class="col-span-1">
                  <label class="block">
                    Quantity
                    <input
                      type="number"
                      v-model="quantity"
                      class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                    />
                    <p v-if="errors.quantity" class="mt-1 text-xs text-red-500">
                      {{ errors.quantity }}
                    </p>
                  </label>
                </div>
                <div class="col-span-1">
                  <label class="block">
                    Price
                    <input
                      type="text"
                      v-model="cost"
                      class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                    />
                    <p v-if="errors.cost" class="mt-1 text-xs text-red-500">
                      {{ errors.cost }}
                    </p>
                  </label>
                </div>
                <div class="col-span-1">
                  <label class="block">
                    Total Price
                    <input
                      type="text"
                      readonly
                      v-model="total_cost"
                      class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                    />
                  </label>
                </div>
                <div class="col-span-1">
                  <label class="block">
                    Budget Code
                    <input
                      type="text"
                      v-model="budget_code"
                      @input="validateBudgetCode"
                      class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                    />
                    <p
                      v-if="errors.budget_code"
                      class="mt-1 text-xs text-red-500"
                    >
                      {{ errors.budget_code }}
                    </p>
                    <p
                      v-else-if="budgetCodeValid"
                      class="mt-1 text-xs text-green-500"
                    >
                      Budget Code is valid.
                    </p>
                  </label>
                </div>
                <div class="col-span-1">
                  <label class="block mb-2"> Category </label>
                  <div class="flex items-center">
                    <div
                      class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]"
                    >
                      <input
                        class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300"
                        type="radio"
                        v-model="category"
                        value="Normal"
                      />
                      <label
                        class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                        >Normal Item</label
                      >
                    </div>
                    <div
                      class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]"
                    >
                      <input
                        class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300"
                        type="radio"
                        v-model="category"
                        value="Premium"
                      />
                      <label
                        class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                        >Premium Item</label
                      >
                    </div>
                  </div>
                </div>
                <div class="col-span-1">
                  <label class="block mb-2"> Type </label>
                  <div class="flex items-center">
                    <div
                      class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]"
                    >
                      <input
                        class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300"
                        type="radio"
                        v-model="type"
                        value="Non-Food"
                      />
                      <label
                        class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                        >Non-Food</label
                      >
                    </div>
                    <div
                      class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]"
                    >
                      <input
                        class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300"
                        type="radio"
                        v-model="type"
                        value="Food"
                      />
                      <label
                        class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                        >Food</label
                      >
                    </div>
                  </div>
                </div>
                <div class="col-span-1" v-if="type === 'Food'">
                  <label class="block">
                    Expiration Date
                    <input
                      type="date"
                      v-model="date_expiry"
                      class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                    />
                  </label>
                </div>
                <div class="flex justify-between mt-4">
                  <button
                    @click="showModalSupply = false"
                    type="button"
                    class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
                  >
                    Submit
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="image-modal">
    <button class="close-button" @click="closeImageModal">Close</button>
    <img class="enlarged-image" @click.stop="" alt="Enlarged Image" />
  </div>
  <div class="py-0">
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
      items: [],
      item_name: "",
      budget_code: "",
      budgetCodeValid: false,
      cost: "",
      total_cost: "",
      type: "Non-Food",
      category: "Normal",
      date_expiry: "",
      sites_selected: "",
      sites1_selected: "",
      items_selected: "",
      quantity: "",
      transferred_quantity: "",
      showModal: false,
      showModalSupply: false,
      errors: {},
      successMessage: "",
      loading: false,
      selectedFile: null,
      previewImage: null,
      showModalTransaction: false,
      columns: [
        {
          title: "No",
          render: function (data, type, row, meta) {
            return meta.row + 1;
          },
        },
        {
          data: "image_path",
          title: "Image",
          render: (data, type) => {
            if (type === "display" && data) {
              return `<button onclick="window.vm.openImageModal('${data}')">
                <img src="${data}" alt="Image" width="50" height="50" loading="lazy"/>
              </button>`;
            }
            return "";
          },
        },
        {
          data: "id",
          title: "Actions",
          orderable: false,
          searchable: false,
          render: function (data) {
            const isUser = this.isUser;

            const isSourcing = this.isSourcing;
            return `
            ${
              isUser || isSourcing
                ? `
                    <button class="w-20 text-xs btn btn-primary" data-id="${data}" onclick="window.vm.openModalForTransfer(${data})">Transfer</button>`
                : ""
            }
          `;
          }.bind(this),
        },
        {
          data: "id",
          title: "Actions",
          orderable: false,
          searchable: false,
          render: function (data) {
            const isUser = this.isUser;
            const isRemx = this.isRemx;

            return `
      ${
        isUser || isRemx
          ? `<button class="w-20 text-xs btn btn-primary" data-id="${data}"
                onclick="window.vm.viewInventory(${data})">View</button>`
          : ""
      }
    `;
          }.bind(this),
        },
        { data: "site_name", title: "Site" },
        { data: "item_name", title: "Item" },
        {
          data: "quantity",
          title: "Quantity",
          render: function (data) {
            return data ? data : 0;
          },
        },
        { data: "original_quantity", title: "Original Quantity" },
        { data: "cost", title: "Price" },
        {
          data: "total_cost",
          title: "Total Price",
          render: function (data, type, row) {
            const cost = row.cost || 0;
            const quantity = row.quantity || 0;
            const totalCost = cost * quantity;
            return totalCost.toFixed(2);
          },
        },
        { data: "budget_code", title: "Budget Code" },
        { data: "type", title: "Type" },
        { data: "category", title: "Category" },
        { data: "date_expiry", title: "Expiration Date" },
        {
          data: "date_received",
          title: "Received Date",
          render: (data, type, row) => {
            return row.date_received ? row.date_received.slice(0, -3) : "N/A";
          },
        },

        {
          data: "received_by",
          title: "Received By",
        },
        {
          data: "transferred_from",
          title: "Transferred From",
        },
        {
          data: "transferred_by",
          title: "Transferred By",
        },
        {
          data: "created_by",
          title: "Added By",
        },
        {
          data: "date_added",
          title: "Added Date",
        },
      ],
    };
  },
  watch: {
    quantity: function () {
      this.updateTotalPrice();
    },
    cost: function () {
      this.updateTotalPrice();
    },
  },
  computed: {
    imageSource() {
      return this.capturedImage ? this.capturedImage : this.selectedImage;
    },
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
  mounted() {
    window.vm = this;
    this.getSites();
    this.getItems();
    this.getInventory();
  },
  methods: {
    async viewInventory(itemId) {
      try {
        await this.getInventory(itemId); // Fetch inventory data
        this.openModalTransaction(this.inventory); // Open modal with the fetched data
      } catch (error) {
        console.error("Error fetching inventory data:", error);
      }
    },

    async getInventory(itemId) {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          `http://127.0.0.1:8000/api/sourcing-item-history/${itemId}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.inventory = response.data.inventory;
          console.log(this.inventory); // Keep this for debugging purposes
        } else {
          console.log("Error fetching inventory");
        }
      } catch (error) {
        console.log(error);
      }
    },

    openModalTransaction(inventoryData) {
      // Logic to open the modal and display the inventory data
      this.modalData = inventoryData; // Bind inventoryData to modal
      this.showModalTransaction = true; // Show modal
    },
    openImageModal(imageUrl) {
      const modal = document.querySelector(".image-modal");
      const enlargedImage = document.querySelector(".enlarged-image");

      enlargedImage.src = imageUrl;
      modal.style.display = "flex";
    },
    closeImageModal() {
      const modal = document.querySelector(".image-modal");

      modal.style.display = "none";
    },
    async handleFileChange(event) {
      const selectedFile = event.target.files[0];

      if (!selectedFile) {
        return;
      }

      // Assign the selected file to this.selectedFile
      this.selectedFile = selectedFile;

      const maxSizeInBytes = 2 * 1024 * 1024; // 2 MB threshold

      if (selectedFile.size > maxSizeInBytes) {
        try {
          const image = new Image();
          const reader = new FileReader();

          reader.onload = (event) => {
            image.src = event.target.result;

            image.onload = async () => {
              const maxWidth = 800;
              const quality = 0.8;

              // Calculate new dimensions to fit within maxWidth
              let width = image.width;
              let height = image.height;
              if (width > maxWidth) {
                height *= maxWidth / width;
                width = maxWidth;
              }

              const canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;

              const ctx = canvas.getContext("2d");
              ctx.drawImage(image, 0, 0, width, height);

              canvas.toBlob(
                async (blob) => {
                  this.selectedFile = blob; // Update this.selectedFile with the resized blob
                  this.previewImage = URL.createObjectURL(blob);

                  console.log("Preview Image URL:", this.previewImage);
                },
                "image/jpeg",
                quality
              );
            };
          };

          reader.readAsDataURL(selectedFile);
        } catch (error) {
          console.error("Error resizing image:", error);
        }
      } else {
        // No need to resize for smaller images
        this.previewImage = URL.createObjectURL(selectedFile);

        console.log("Preview Image URL:", this.previewImage);
      }
    },

    async compressBlob(blob, maxSize) {
      const image = new Image();
      const reader = new FileReader();
      const maxQuality = 0.8;

      const compressedBlob = await new Promise((resolve) => {
        reader.onload = (event) => {
          image.src = event.target.result;

          image.onload = () => {
            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d");

            let newWidth = image.width;
            let newHeight = image.height;

            if (image.size > maxSize) {
              const scaleFactor = Math.sqrt(image.size / maxSize);
              newWidth = Math.floor(image.width / scaleFactor);
              newHeight = Math.floor(image.height / scaleFactor);
            }

            canvas.width = newWidth;
            canvas.height = newHeight;

            ctx.drawImage(image, 0, 0, newWidth, newHeight);

            canvas.toBlob(resolve, "image/jpeg", maxQuality);
          };
        };

        reader.readAsDataURL(blob);
      });

      return compressedBlob;
    },
    validateBudgetCode() {
      this.errors.budget_code = "";
      this.budgetCodeValid = false;
      const budgetCodeRegex = /^REC[a-zA-Z0-9]{3}[0-9]{6}$/;
      if (!budgetCodeRegex.test(this.budget_code)) {
        this.errors.budget_code =
          "Budget Code must start with 'REC', followed by 3 alphanumeric characters, and ending with 6 digits.";
      } else {
        this.budgetCodeValid = true;
      }
    },
    updateTotalPrice() {
      this.total_cost = this.quantity * this.cost;
    },
    openModalForTransfer(id) {
      const clickedItem = this.items.find((item) => item.id === id);
      if (clickedItem) {
        this.quantity = clickedItem.quantity;
        this.sites1_selected = clickedItem.site_id;
        this.items_selected = clickedItem.id;
      }
      // Show the modal
      this.showModal = true;
    },
    async getItems() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/siteinventoryall",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

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
    transferItems() {
      this.errors = {};
      this.loading = true;

      if (!this.sites_selected) {
        this.errors.sites_selected = "Site is required.";
      }
      if (!this.transferred_quantity) {
        this.errors.transferred_quantity = "Quantity Request is required.";
      } else if (
        parseInt(this.transferred_quantity) > parseInt(this.quantity)
      ) {
        this.errors.transferred_quantity =
          "Quantity Request cannot exceed available quantity.";
      }

      if (Object.keys(this.errors).length > 0) {
        this.loading = false;
        return;
      }

      const formData = new FormData();
      formData.append("file_name", this.selectedFile);
      formData.append("inventory_item_id", this.items_selected);
      formData.append("site_id", this.sites_selected);
      formData.append("quantity_approved", this.transferred_quantity);
      formData.append("transferred_by", this.$store.state.user_id);
      formData.append("transferred_to", this.sites_selected);
      formData.append("transferred_from", this.sites1_selected);

      axios
        .post("http://127.0.0.1:8000/api/transfer", formData, {
          headers: {
            Authorization: `Bearer ${this.$store.state.token}`,
          },
        })
        .then((response) => {
          if (response && response.data && response.data.Request) {
            console.log(response.data.Request);
            this.successMessage = "Transferred successfully!";
            this.showModal = false;
            window.location.reload();
          } else {
            console.error("Response or Request property is undefined.");
          }
        })
        .catch((error) => {
          if (error.response && error.response.data) {
            console.log(error.response.data);
          } else {
            console.error(
              "Error response or error.response.data is undefined."
            );
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },

    addItems() {
      this.errors = {};
      this.loading = true;

      if (!this.sites_selected) {
        this.errors.sites_selected = "Site is required.";
      }
      if (!this.item_name) {
        this.errors.item_name = "Item Name is required.";
      }
      if (!this.quantity) {
        this.errors.quantity = "Quantity is required.";
      }
      if (!this.cost) {
        this.errors.cost = "Price is required.";
      }
      if (!this.budget_code) {
        this.errors.budget_code = "Budget Code is required.";
      } else {
        console.log("Validating budget code:", this.budget_code);
        const budgetCodeRegex = /^REC[a-zA-Z0-9]{3}[0-9]{6}$/;
        if (!budgetCodeRegex.test(this.budget_code)) {
          this.errors.budget_code =
            "Budget Code must start with 'REC', followed by 3 alphanumeric characters, and ending with 6 digits.";
        } else {
          console.log("Budget code is valid:", this.budget_code);
        }
      }

      if (Object.keys(this.errors).length > 0) {
        console.log("Form has errors");
        this.loading = false; // Stop loading when there are errors
        return;
      }

      const formData = new FormData();
      formData.append("file_name", this.selectedFile);
      formData.append("item_name", this.item_name);
      formData.append("quantity", this.quantity);
      formData.append("original_quantity", this.quantity);
      formData.append("type", this.type);
      formData.append("cost", this.cost);
      formData.append("total_cost", this.total_cost);
      formData.append("category", this.category);
      formData.append("budget_code", this.budget_code);
      formData.append("date_expiry", this.date_expiry);
      formData.append("site_id", this.sites_selected);
      formData.append("is_active", 1);
      formData.append("created_by", this.$store.state.user_id);

      axios
        .post("http://127.0.0.1:8000/api/items_site_supply", formData, {
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
          this.cost = "";
          this.total_cost = "";
          this.category = "";
          this.budget_code = "";
          this.date_expiry = "";
          this.selectedFile = null;
          this.getItems();
          this.successMessage = "Item Successfully Added!";
        })
        .catch((error) => {
          console.log(error.response.data);
        })
        .finally(() => {
          this.loading = false;
        });
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
  max-width: 100%;
  width: 100%;
  box-sizing: border-box;
}

.modal-content form {
  display: grid;
  grid-template-columns: 1fr;
}
.modal-scrollable-content {
  max-height: 70vh;
  overflow-y: auto;
}
/* Updated Radio Button Styles */
input[type="radio"] {
  /* Hide the default radio button */
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
  /* Add custom styling for the checked state */
  border-color: #3b71ca; /* Blue color for checked state */
}

input[type="radio"]:checked::before {
  /* Add the blue dot inside the checked radio button */
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: #3b71ca; /* Blue color for the dot */
}

/* Optional: Increase the size of the radio button and the blue dot */
input[type="radio"] {
  width: 20px;
  height: 20px;
}

input[type="radio"]:checked::before {
  width: 10px;
  height: 10px;
}
</style>
<style scoped>
/* Your loader styles here */
.loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* Ensure the loader is on top of other elements */
}

.loader-content {
  /* Style your loader content (SVG, text, etc.) */
  display: flex;
  align-items: center;
}

.icon {
  /* Style your SVG icon */
  height: 3rem; /* Adjust the size as needed */
  width: 3rem; /* Adjust the size as needed */
  animation: spin 1s linear infinite;
  stroke: rgba(107, 114, 128, 1);
}

.loading-text {
  /* Style your loading text */
  font-size: 1.5rem; /* Adjust the size as needed */
  line-height: 2rem; /* Adjust the size as needed */
  font-weight: 500;
  color: rgba(107, 114, 128, 1);
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
