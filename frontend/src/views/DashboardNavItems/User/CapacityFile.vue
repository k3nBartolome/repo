<template>
  <div class="overflow-x-auto px-12">
    <table class="table-auto border border-black">
      <thead>
        <tr>
          <th class="px-4">Sites</th>
          <th class="px-4">Programs</th>
          <th
            v-for="daterange in daterange"
            :key="daterange.id"
            class="px-4 truncate py-1 border border-black bg-orange-500 text-white"
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
          <template v-for="(classClasses, SiteName) in dateRangeClasses" :key="SiteName">
            <template
              v-for="(classItemClasses, classesName) in classClasses"
              :key="classesName"
            >
              <tbody class="overflow-y-auto">
                <tr>
                  <td class="w-1/4 px-2 py-1 border border-black truncate">
                    {{ SiteName + 1 }}
                  </td>
                  <td class="w-1/4 px-2 py-1 border border-black truncate">
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
                      <td class="w-1/4 border border-black truncate ">
                        <button
                          class="h-full w-1/2 text-black bg-gray-200"
                          @click="showButtons(classItemBClasses)"
                        >
                          {{ classItemBClasses.total_target }}
                        </button>
                        <div v-if="classItemBClasses.showButtons" class="flex items-center">
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
                              <button class="mx-2 bg-blue-500">Add</button>
                            </router-link>
                          </div>
                          <div v-else>
                            <router-link :to="`/pushbackcapfile/${classItemBClasses.class_id}`"
                              ><button
                                class="mx-2 bg-green-500 w-22"
                             
                              >
                                Pushed Back
                              </button></router-link
                            >
                            <router-link :to="`/cancelcapfile/${classItemBClasses.class_id}`"
                              ><button
                                class="mx-2 bg-red-500 w-22"
                              
                              >
                                Cancel
                              </button></router-link
                            >
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
</template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      classes: {},
      daterange: [],
    };
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
