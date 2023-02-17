<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full px-4 py-6 max-w-7xl sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        Capacity File Manager
      </h1>
    </div>
  </header>
  <div class="py-8 ">
    <form @submit.prevent="addClass">
      <div class="px-12 py-6 mx-auto bg-white max-w-7xl sm:px-2 lg:px-2 border-2 border-orange-600 font-semibold">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-6">
          <label class="block">
            Site
            <select v-model="sites_selected"
              class="block w-full mt-1 border rounded-md  focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required @change="getSites">
              <option disabled value="" selected>Please select one</option>
              <option v-for="site in sites" :key="site.id" :value="site.id">{{ site.name }}</option>
            </select>
          </label>
          <label class="block">
            Line of Business
            <select v-model="programs_selected"
              class="block w-full mt-1 border rounded-md  focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required @change="getPrograms">
              <option disabled value="" selected>Please select one</option>
              <option v-for="program in programs" :key="program.id" :value="program.id">{{ program.name }}</option>
            </select>
          </label>
          <label class="block">
            Type of Hiring
            <select v-model="type_of_hiring"
              class="block w-full mt-1 border rounded-md  focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100">
              <option disabled value="" selected>Please select one</option>
              <option value="attrition">Attrition</option>
              <option value="growth">Growth</option>
              <option value="attrition and growth">Attrition and Growth</option>
            </select>
          </label>
          <label class="block">
            External Target
            <input type="number" v-model="external_target"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
          <label class="block">
            Internal Target
            <input type="number" v-model="internal_target"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
          <label class="block">
            Total Target
            <input type="text" :value="total_target" readonly
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
          <label class="block">
            Delivery Date
            <input type="date" v-model="delivery_date"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
          <label class="block">
            Notice Days
            <input type="number" v-model="notice_days"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
          <label class="block">
            Notice Weeks
            <input type="text" :value="notice_weeks" readonly
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
          <label class="block">
            Pipeline Utilized
            <input type="text" v-model="pipeline_utilized"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
          <label class="block">With ERF?
            <select v-model="with_erf"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100">

              <option disabled value="" selected>Please select one</option>
              <option value="yes">Yes</option>
              <option value="no">No</option>
            </select>
          </label>
          <label class="block">Original Start Date
            <input type="date" v-model="original_start_date"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
          <label class="block">Supposed Start Date
            <input type="date" v-model="supposed_start_date"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
          <label class="block">WF Requested Start Date
            <input type="date" v-model="wf_requested_start_date"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
          <label class="block">TA Committed Start Date
            <input type="date" v-model="ta_committed_start_date"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
          <label class="block">WFM Requested Date
            <input type="date" v-model="wfm_requested_date"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
          <label class="block">Within SLA?
            <select v-model="within_sla"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100">
              <option value="yes">Yes</option>
              <option value="no">No</option>
            </select>

          </label>
        </div>
        <div class="py-4">
          <label class="block">Reason for Counter Proposal
            <textarea type="text" v-model="reason_for_counter_proposal"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 h-20 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
          <label class="block">Remarks<textarea type="text" v-model="remarks"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 h-20 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
          <label class="block">Out of SLA Reason<textarea type="text" v-model="out_of_sla_reason"
              class=" block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300  h-20 focus:ring focus:ring-indigo-200 focus:ring-opacity-100" />
          </label>
        </div>
        <div class="py-4 flex justify-center">
          <button type="submit"
            class="self-center bg-orange-600 hover:bg-gray-600 text-white font-bold py-1 px-4 rounded">
            <i class="fa fa-save	"></i> Save
          </button>
        </div>
      </div>
    </form>
  </div>
</template>
<script>

import axios from 'axios'
export default {
  data() {
    return {


      sites_selected: "",
      programs_selected: "",
      type_of_hiring: "",
      external_target: "",
      internal_target: "",
      delivery_date: "",
      notice_days: "",
      pipeline_utilized: "",
      with_erf: "",
      original_start_date: "",
      supposed_start_date: "",
      wf_requested_start_date: "",
      ta_committed_start_date: "",
      wfm_requested_date: "",
      within_sla: "",
      reason_for_counter_proposal: "",
      remarks: "",
      out_of_sla_reason: "",
      sites: [],
      programs: [],
    }
  },
  computed: {
    total_target() {
      const external = parseInt(this.external_target) || 0;
      const internal = parseInt(this.internal_target) || 0;
      return (external + internal).toFixed();
    },
    notice_weeks() {
      const days = parseFloat(this.notice_days) || 0;
      return (days / 7).toFixed(2);
    }
  },

  mounted() {
    console.log('Component mounted.')
    this.getSites();
    this.getPrograms();
  },


  methods: {
    async getSites() {
      console.log(this.sites_selected)
      await axios.get('http://127.0.0.1:8000/api/sites')
        .then((response) => {
          this.sites = response.data.data;
          console.log(response.data.data)
        })
        .catch((error) => {
          console.log(error)
        })
    },
    async getPrograms() {
      console.log(this.programs_selected)
      await axios.get('http://127.0.0.1:8000/api/programs')
        .then((response) => {
          this.programs = response.data.data;
          console.log(response.data.data)
        })
        .catch((error) => {
          console.log(error)
        })
    },
    addClass() {
      const formData = {
        site_id: this.site_selected,
        program_id: this.program_selected,
        type_of_hiring: this.type_of_hiring,
        external_target: this.external_target,
        internal_target: this.internal_target,
        total_target: this.total_target,
        delivery_date: this.delivery_date,
        notice_days: this.notice_days,
        notice_weeks: this.notice_weeks,
        pipeline_utilized: this.pipeline_utilized,
        with_erf: this.with_erf,
        original_start_date: this.original_start_date,
        supposed_start_date: this.supposed_start_date,
        wf_requested_start_date: this.wf_requested_start_date,
        ta_committed_start_date: this.ta_committed_start_date,
        wfm_requested_date: this.wfm_requested_date,
        within_sla: this.within_sla,
        reason_for_counter_proposal: this.reason_for_counter_proposal,
        remarks: this.remarks,
        out_of_sla_reason: this.out_of_sla_reason,
        approved_status: "pending",
        is_active: 1,
        created_by: this.$store.state.user_id,
      };
      axios.post("http://127.0.0.1:8000/api/sites", formData)
        .then(response => {
          console.log(response.data);
          this.name = '';
          this.description = '';
          this.siteDirector = '';
          this.region = '';
        })
        .catch(error => {
          console.log(error.response.data);
        });
    }
  }
}
</script>