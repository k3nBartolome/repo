<template>
    <header class="w-full bg-white shadow">
      <div class="flex items-center w-full px-4 py-6 max-w-7xl sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">
          Capacity File Manager
        </h1>
      </div>
    </header>
    <div class="px-12 py-12 ">
      <div class="px-12 py-12">
        <table class="table px-20 py-20 border border-black">
          <thead class="">
            <tr class="">
              <th class="px-4">Sites</th>
              <th class="px-4">Programs</th>
              <th
                v-for="daterange in daterange"
                :key="daterange.id"
                class="px-4 py-1 text-white truncate bg-orange-500 border border-black"
              >
                {{ daterange.date_range }}
              </th>
            </tr>
          </thead>
          <template v-for="(programClasses, programSiteId) in classes" :key="programSiteId">
            <template
              v-for="(dateRangeClasses, programName) in programClasses"
              :key="programName"
            >
              <template
                v-for="(classClasses, SiteName) in dateRangeClasses"
                :key="SiteName"
              >
                <template
                  v-for="(classItemClasses, classesName) in classClasses"
                  :key="classesName"
                >
                  <tbody>
                    <tr>
                      <td class="w-1/4 px-2 py-1 truncate border border-black">
                        {{ SiteName }}
                      </td>
                      <td class="w-1/4 px-2 py-1 truncate border border-black">
                        {{ classesName }}
                      </td>
                      <template
                        v-for="(classItemAClasses, DateRangeName) in classItemClasses"
                        :key="DateRangeName"
                      >
                        <template
                          v-for="(classItemBClasses, DateRangeNameA) in classItemAClasses"
                          :key="DateRangeNameA"
                        >
                          <td
                            class="w-1/4 truncate border border-black hoverable"
                            :data-hover-text="
                              SiteName +
                              ' | ' +
                              classesName +
                              ' | ' +
                              classItemBClasses.date_range
                            "
                          >
                            <div class="flex items-center">
                              <button
                                class="w-1/2 h-full text-black bg-gray-200"
                                @click="showButtons(classItemBClasses)"
                              >
                                {{ classItemBClasses.total_target }}
                              </button>
  
                              <div
                                v-if="classItemBClasses.showButtons"
                                class="flex items-center"
                              >
                                <div v-if="classItemBClasses.total_target == 0">
                                  <router-link
                                    :to="{
                                      path: `/addcapfile/
                            }`,
                                      query: {
                                        program: classItemBClasses.program_id,
                                        site: classItemBClasses.site_id,
                                        daterange: classItemBClasses.date_range_id,
                                      },
                                    }"
                                  >
                                    <button class="mx-2 text-white bg-blue-600">ADD</button>
                                  </router-link>
                                </div>
                                <div v-else>
                                  <router-link
                                    :to="`/pushbackcapfile/${classItemBClasses.class_id}`"
                                    ><button class="mx-2 bg-green-500 w-22">
                                      Pushed Back
                                    </button></router-link
                                  >
                                  <router-link
                                    :to="`/cancelcapfile/${classItemBClasses.class_id}`"
                                    ><button class="mx-2 bg-red-500 w-22">
                                      Cancel Class
                                    </button></router-link
                                  >
                                </div>
                              </div>
                            </div>
                          </td>
                        </template>
                      </template>
                    </tr>
                  </tbody>
                </template>
              </template>
            </template>
          </template>
        </table>
      </div>
    </div>
  </template>
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        classes: {},
        daterange: [],
        search: "",
      };
    },
    computed: {
      filteredClasses() {
        return this.classes.filter((programSiteId) =>
          programSiteId.toLowerCase().includes(this.search.toLowerCase())
        );
      },
    },
    mounted() {
      this.fetchClassesData();
      this.fetchWeekData();
    },
    methods: {
      async fetchWeekData() {
        try {
          const response = await axios.get("http://127.0.0.1:8000/api/daterange");
          this.daterange = response.data.data;
        } catch (error) {
          console.error(error);
        }
      },
      async fetchClassesData() {
        axios
          .get("http://127.0.0.1:8000/api/classesall")
          .then((response) => {
            this.classes = response.data;
            console.log(this.classes);
          })
          .catch((error) => {
            console.log(error);
          });
      },
      showButtons(classItemBClasses) {
        classItemBClasses.showButtons = !classItemBClasses.showButtons;
      },
    },
  };
  </script>
  <style>
  .hoverable:hover::before {
    content: attr(data-hover-text);
    position: relative;
    padding: 5px;
    display: none;
    width: 200px;
    border: 1px solid #004970;
    border-radius: 6px 6px;
    background: rgb(236, 241, 243);
    white-space: normal;
    font-size: 10px;
    color: #000000;
    font-weight: bold;
    line-height: 15px;
  }
  
  .hoverable:hover::before {
    display: inline-block;
  }
  </style>