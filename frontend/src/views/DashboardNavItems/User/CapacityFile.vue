<template>
  <table class="min-w-full divide-y divide-gray-200">
    <thead>
      <th
        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-white uppercase bg-orange-500"
      >
        Site
      </th>
      <th
        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-white uppercase bg-orange-500"
      >
        Program
      </th>
      <th
        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-white uppercase bg-orange-500"
      ></th>
      <template v-for="(programClasses, programSiteId) in classes" :key="programSiteId">
        <template
          v-for="(dateRangeClasses, programName) in programClasses"
          :key="programName"
        >
          <template
            v-for="(classClasses, dateRange) in dateRangeClasses"
            :key="dateRange"
          >
            <template
              v-for="(classItemClasses, classSite) in classClasses"
              :key="classSite"
            >
              <th
                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50"
              >
                {{ classClasses.date_range }}
              </th>
            </template>
          </template>
        </template>
      </template>
    </thead>

    <template v-for="(programClasses, programSiteId) in classes" :key="programSiteId">
      <template
        v-for="(dateRangeClasses, programName) in programClasses"
        :key="programName"
      >
        <template v-for="(classClasses, dateRange) in dateRangeClasses" :key="dateRange">
          <template
            v-for="(classItemClasses, classSite) in classClasses"
            :key="classSite"
          >
            <tbody class="truncate bg-white divide-y divide-gray-200">
              <tr>
                <td class="px-6 py-4 text-sm text-gray-500 truncate">
                  {{ classClasses.site_id }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 truncate">
                  {{ classClasses.program_name }}
                </td>
              </tr>
            </tbody>
          </template>
        </template>
      </template>
    </template>
  </table>
</template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      classes: {},
    };
  },
  mounted() {
    this.fetchClassesData();
  },
  methods: {
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
