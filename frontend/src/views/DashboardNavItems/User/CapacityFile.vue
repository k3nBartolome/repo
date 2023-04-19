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
                    {{ SiteName + 1}}
                  </td>
                  <td class="w-1/4 px-2 py-1 border border-black truncate">
                    {{ classesName}}
                  </td>
                  <template
                    v-for="(classItemAClasses, DateRangeName) in classItemClasses"
                    :key="DateRangeName"
                  >
                    <template
                      v-for="(classItemBClasses, DateRangeNameA) in classItemAClasses"
                      :key="DateRangeNameA"
                    >
                      <td class="w-1/4 border border-black truncate">
                        <router-link
                          :to="{
                            path: `/addcapfile/daterange.id
                            }`,
                            query: {
                              program: 1,
                              daterange: 1,
                              site: 1,
                            },
                          }"
                        >
                          <button class="bg-white h-full w-full text-black">
                            {{ classItemBClasses }}
                          </button>
                        </router-link>

                        <button class="mx-2 bg-blue-500">Add</button>
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
      program: [],
    };
  },
  mounted() {
    this.fetchClassesData();
    this.fetchWeekData();
    this.fetchProgramData();
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
    async fetchProgramData() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/programs");
        this.program = response.data.data;
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
  },
};
</script>
