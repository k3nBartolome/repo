<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h1 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Staffing Tracker
      </h1>
    </div>
  </header>
  <div class="px-12 py-8">
    <form @submit.prevent="addStaffing">
      <div
        class="px-12 py-6 mx-auto font-semibold bg-white border-2 border-orange-600 max-w-7xl sm:px-2 lg:px-2"
      >
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5">
          <label class="block">
            Classes
            <select
              v-model="class_selected"
              class="block w-full mt-1 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
              @change="getClassesAll"
            >
              <option disabled value="" selected>Please select one</option>
              <option v-for="classesall in classesall" :key="classesall.id" :value="classesall.id">
                {{ classesall.site.name }} {{classesall.program.name}} {{ classesall.date_range.date_range }} {{ classesall.total_target }}
              </option>
            </select>
          </label>

        </div>
      </div>
    </form>
  </div>
</template>
<script>
import axios from "axios";
export default {
  data() {
    return {
      class_selected: "",
      classesall: [],
    };
  },
  mounted() {
    //this.getClasses();
    this.getClassesAll();
  },
  methods: {
    async getClassesAll() {
      await axios
        .get("http://127.0.0.1:8000/api/classesall")
        .then((response) => {
          this.classesall = response.data.classes;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    /* async getClasses() {
      await axios
        .get("http://127.0.0.1:8000/api/classes/" + this.$route.params.id)
        .then((response) => {
          const data = response.data;
          const classObj = data.class;
          this.sites_selected = classObj.site.id;
          this.programs_selected = classObj.program.id;
          this.type_of_hiring = classObj.type_of_hiring;
          this.external_target = classObj.external_target;
          this.internal_target = classObj.internal_target;
          this.total_target = classObj.total_target;
          this.original_start_date = classObj.original_start_date;
          this.notice_days = classObj.notice_days;
          this.notice_weeks = classObj.notice_weeks;
          this.date_selected = classObj.date_range.id;
          this.with_erf = classObj.with_erf;
          this.category = classObj.category;
          this.within_sla = classObj.within_sla;
          this.agreed_start_date = classObj.agreed_start_date;
          this.erf_number = classObj.erf_number;
          this.approved_by = classObj.approved_by;
          this.wfm_date_requested = classObj.wfm_date_requested;
          this.remarks = classObj.remarks;

          console.log(classObj);
        })
        .catch((error) => {
          console.log(error);
        });
    }, */
  },
};
</script>
