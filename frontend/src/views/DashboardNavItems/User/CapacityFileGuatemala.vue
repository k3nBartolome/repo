<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h2 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Capacity File Manager
      </h2>
        </div>
      </header>
  <div class="py-8">
    <div
      class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
    >
      <form
        class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5"
      >
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
        <label class="block">
          Programs
          <select
            v-model="programs_selected"
            class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
          >
            <option disabled value="" selected>Please select one</option>
            <option
              v-for="program in programs"
              :key="program.id"
              :value="program.id"
            >
              {{ program.name }}
            </option>
          </select>
        </label>
        <label class="block">
          Month
          <select
            v-model="month_selected"
            class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            @change="getDateRange"
          >
            <option disabled value="" selected>Please select one</option>
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
          </select>
        </label>
        <label class="block">
          Week Range
          <select
            v-model="week_selected"
            class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
          >
            <option disabled value="" selected>Please select one</option>
            <option
              v-for="daterange in daterange"
              :key="daterange.id"
              :value="daterange.id"
            >
              {{ daterange.date_range }}
            </option>
          </select>
        </label>
        <router-link
          :to="{
            path: `/addcapfileguatemala/}`,
            query: {
              program: programs_selected,
              site: sites_selected,
              daterange: week_selected,
            },
          }"
        >
          <button
            v-if="!classExists"
            type="submit"
            :disabled="classExists"
            class="px-10 py-4 font-bold text-white bg-orange-500 rounded hover:bg-gray-600"
          >
            <i class="fa fa-building"></i> Add
          </button>
        </router-link>
      </form>
    </div>
  </div>
  <div class="py-8">
    <div class="pl-8 pr-8 overflow-x-auto overflow-y-auto">
      <div class="mb-4">
        <input
          type="text"
          v-model="search"
          placeholder="Search..."
          class="px-6 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500"
        />
      </div>

      <table class="w-full text-white table-auto">
        <thead>
          <tr
            class="text-center bg-orange-500 border-2 border-orange-600 border-solid"
          >
            <th class="px-1 py-2">ID</th>
            <th class="px-2 py-2 truncate" colspan="1">Action</th>
            <th class="px-1 py-2">Total Target</th>
            <th class="px-1 py-2">Site</th>
            <th class="px-1 py-2">Program</th>
            <th class="px-1 py-2">Date Range</th>
            <th class="px-1 py-2">Status</th>
          </tr>
        </thead>
        <tbody v-for="classes in filteredClasses" :key="classes.id">
          <tr
            class="font-semibold text-center text-black bg-white border-2 border-gray-400 border-solid align-center"
          >
            <td class="px-1 py-2 border border-black">
              {{ classes.pushedback_id }}
            </td>
            <td class="px-2 py-2 truncate">
              <div class="flex justify-center mt-2">
                <router-link :to="`/pushbackcapfile/${classes.id}`">
                  <button
                    class="flex items-center justify-center px-4 py-2 mr-2 text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-green-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
                  >
                    Update/Pushback
                  </button>
                </router-link>
                <router-link :to="`/cancelcapfile/${classes.id}`">
                  <button
                    class="flex items-center justify-center px-4 py-2 text-xs font-semibold text-white uppercase transition duration-150 ease-in-out bg-red-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
                  >
                    Cancel
                  </button>
                </router-link>
                <router-link :to="`/editcapfile/${classes.id}`">
                  <button
                    class="flex items-center justify-center px-4 py-2 ml-6 mr-2 text-xs font-semibold text-center text-white uppercase transition duration-150 ease-in-out bg-blue-600 border-0 rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25"
                  >
                    Edit
                  </button>
                </router-link>
              </div>
            </td>
            <td class="px-1 py-2 border border-black">
              {{ classes.total_target }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ classes.site.name }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ classes.program.name }}
            </td>
            <td class="px-1 py-2 border border-black">
              {{ classes.date_range.date_range }}
            </td>
            <td class="px-1 py-2 border border-black">{{ classes.status }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
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
      search: "",
    };
  },
  computed: {
    filteredClasses() {
      return this.classes.filter(
        (classes) =>
          classes.date_range.date_range
            .toLowerCase()
            .includes(this.search.toLowerCase()) ||
          classes.program.name
            .toLowerCase()
            .includes(this.search.toLowerCase()) ||
          classes.site.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },
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
    this.getSites();
    this.getPrograms();
    this.getDateRange();
    this.getClassesAll();
  },
  methods: {
    async getClassesAll() {
      await axios
        .get("http://i/classesallgua")
        .then((response) => {
          this.classes = response.data.classes;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getSites() {
      await axios
        .get("http://i/sites7")
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
        .get(
          `http://i/programs_selected/${this.sites_selected}`
        )
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
        .get(
          `http://i/daterange_selected/${this.month_selected}`
        )
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
