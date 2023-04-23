<template>
  <div class="px-12 overflow-x-auto">
    <table class="border border-black table-auto">
      <thead>
        <tr>
          <th class="px-4 border-black">Sites</th>
          <th class="px-4 border-black">Programs</th>
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
          <template v-for="(classClasses, SiteName) in dateRangeClasses" :key="SiteName">
            <template
              v-for="(classItemClasses, classesName) in classClasses"
              :key="classesName"
            >
              <template
                v-for="(classItemAClasses, DateRangeName) in classItemClasses"
                :key="DateRangeName"
              >
                <tbody class="overflow-y-auto">
                  <tr>
                    <td class="w-1/4 px-2 py-1 truncate border border-black">
                      {{ SiteName + 1 }}
                    </td>
                    <td class="w-1/4 px-2 py-1 truncate border border-black"></td>
                    <template
                      v-for="(classItemBClasses, DateRangeAName) in classItemAClasses"
                      :key="DateRangeAName"
                    >
                      <template
                        v-for="(classItemCClasses, DateRangeBName) in classItemBClasses"
                        :key="DateRangeBName"
                      >
                        <template
                          v-for="(classItemDClasses, DateRangeCName) in classItemCClasses"
                          :key="DateRangeCName"
                        >
                          <template
                            v-for="(
                              classItemEClasses, DateRangeDName
                            ) in classItemDClasses"
                            :key="DateRangeDName"
                          >
                            <td class="w-1/4 truncate border border-black">
                              <router-link
                                :to="{
                                  path: `/addcapfile/
                            }`,
                                  query: {
                                    site: SiteName + 1,
                                    daterange: DateRangeAName + 1,
                                    program: classesName,
                                  },
                                }"
                              >
                                <button class="w-full h-full text -black bg-white">
                                  {{ classClasses }}
                                </button>
                              </router-link>

                              <button class="mx-2 bg-blue-500">Add</button>
                            </td>
                          </template>
                        </template>
                      </template>
                    </template>
                  </tr>
                </tbody>
              </template>
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
      programs: [],
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
        this.programs = response.data.data;
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
