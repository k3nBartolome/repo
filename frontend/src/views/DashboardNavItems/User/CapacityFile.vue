<template>
  <table class="min-w-full divide-y divide-gray-200">
    <template v-for="(programClasses, programSIteId) in classes" :key="programSIteId">
      <template
        v-for="(dateRangeClasses, programName) in programClasses"
        :key="programName"
      >
        <template v-for="(classClasses, dateRange) in dateRangeClasses" :key="dateRange">
          <template v-for="(classItemClasses, classA) in classClasses" :key="classA">
            <template
              v-for="(classItemAClasses, classB) in classItemClasses"
              :key="classB"
            >
              <thead>
                <th
                  class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50"
                >
                  Site
                </th>
                <th
                  class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50"
                >
                  Program
                </th>
                <th
                  class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50"
                ></th>
                <th
                  class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50"
                ></th>
              </thead>
              <tbody class="truncate bg-white divide-y divide-gray-200">
                <tr>
                  <td
                    class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap"
                  ></td>
                  <td class="px-6 py-4 text-sm text-gray-500 truncate">
                    {{ classB }}
                  </td>
                </tr>
              </tbody>
            </template>
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
      classes: [],
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
