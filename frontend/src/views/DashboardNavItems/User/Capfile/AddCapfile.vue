<!-- eslint-disable vue/require-v-for-key -->
<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full px-4 py-6 max-w-7xl sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        Capacity File Manager
      </h1>
    </div>
  </header>
  <div class="px-12 py-8">
    <form @submit.prevent="addClass">
      <div
        class="px-12 py-6 mx-auto font-semibold bg-white border-2 border-orange-600 max-w-7xl sm:px-2 lg:px-2"
      >
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5">
          <label class="block">
            Site
            <select
              disabled
              v-model="sites_selected"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
              @change="getSites"
            >
              <option disabled value="" selected>Please select one</option>
              <option v-for="site in sites" :key="site.id" :value="site.id">
                {{ site.name }}
              </option>
            </select>
          </label>
          <label class="block">
            Line of Business
            <select
              disabled
              v-model="programs_selected"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
              @change="getPrograms"
            >
              <option disabled value="" selected>Please select one</option>
              <option v-for="program in programs" :key="program.id" :value="program.id">
                {{ program.name }}
              </option>
            </select>
          </label>
          <label class="block">
            Type of Hiring
            <select
              required
              v-model="type_of_hiring"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            >
              <option disabled value="" selected>Please select one</option>
              <option value="attrition">Attrition</option>
              <option value="growth">Growth</option>
              <option value="attrition and growth">Attrition and Growth</option>
            </select>
          </label>
          <label class="block">
            External Target
            <input
              required
              type="number"
              v-model="external_target"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncTotalTarget"
            />
          </label>
          <label class="block">
            Internal Target
            <input
              required
              type="number"
              v-model="internal_target"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncTotalTarget"
            />
          </label>
          <label class="block">
            Total Target
            <input
              type="number"
              v-model="total_target"
              readonly
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label>
          <label class="block"
            >Original Start Date
            <input
              required
              type="date"
              v-model="original_start_date"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncNoticeDays"
            />
          </label>

          <label class="block"
            >WFM Date Requested
            <input
              required
              type="date"
              v-model="wfm_date_requested"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncNoticeDays"
            />
          </label>
          <label class="block">
            Notice Days
            <input
              type="number"
              v-model="notice_days"
              readonly
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncNoticeWeeks"
            />
          </label>
          <label class="block">
            Notice Weeks
            <input
              type="text"
              v-model="notice_weeks"
              readonly
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label>
          <label class="block">
            Weeks Start
            <select
              disabled
              v-model="date_selected"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
              @change="getDateRange"
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
          <label class="block"
            >With ERF?
            <select
              required
              v-model="with_erf"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            >
              <option disabled value="" selected>Please select one</option>
              <option value="yes">Yes</option>
              <option value="no">No</option>
            </select>
          </label>
          <label class="block" v-if="with_erf === 'yes'">
            ERF Number
            <input
              type="text"
              v-model="erf_number"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label>
          <label class="block"
            >Category
            <select
              required
              v-model="category"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            >
              <option disabled value="" selected>Please select one</option>
              <option value="placeholder">Placeholder</option>
              <option value="confirmed">Confirmed</option>
            </select>
          </label>
          <label class="block"
            >Within SLA?
            <select
              required
              v-model="within_sla"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            >
              <option disabled value="" selected>Please select one</option>
              <option value="Within Sla">Within Sla</option>
              <option value="Outside Sla-Change in Demand">
                Outside Sla-Change in Demand
              </option>
              <option value="Outside Sla-Change in Start Date">
                Outside Sla-Change in Start Date
              </option>
              <option value="Outside Sla-Change in Profile">
                Outside Sla-Change in Profile
              </option>
              <option value="Outside Sla-Change in Process/Assessments">
                Outside Sla-Change in Process/Assessments
              </option>
              <option value="OV Support">OV Support</option>
            </select>
          </label>
          <label class="block"
            >Approved by
            <select
              required
              v-model="approved_by"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            >
              <option disabled value="" selected>Please select one</option>
              <option value="SD Sheila Y">SD Sheila Y</option>
              <option value="CS/Ops">CS/Ops</option>
              <option value="WF/Ops">WF/Ops</option>
              <option value="Cheryll Punzalan">Cheryll Punzalan</option>
              <option value="Daniel Dela Vega">Daniel Dela Vega</option>
              <option value="Christito Villaprudente">Christito Villaprudente</option>
              <option value="VP Sheryll">VP Sheryll</option>
              <option value="Kim De Guzman">Kim De Guzman</option>
              <option value="Ryan Tomzer">Ryan Tomzer</option>
            </select>
          </label>
        </div>
        <div class="py-4">
          <label class="block">
            Condition
            <label class="flex items-start">
              <input
                type="checkbox"
                v-model="condition"
                value="Filed ERF with necessary approvals and within timeline"
                class="ml-2"
              />
              <span class="ml-2"
                >Filed ERF with necessary approvals and within timeline</span
              >
            </label>
            <label class="flex items-start">
              <input
                type="checkbox"
                v-model="condition"
                value="Adherence to hiring demand from initial sign-off"
                class="ml-2"
              />
              <span class="ml-2">Adherence to hiring demand from initial sign-off</span>
            </label>
            <label class="flex items-start">
              <input
                type="checkbox"
                v-model="condition"
                value="Adherence to hiring timelines from initial sign-off"
                class="ml-2"
              />
              <span class="ml-2"
                >Adherence to hiring timelines from initial sign-off</span
              >
            </label>
            <label class="flex items-start">
              <input
                type="checkbox"
                v-model="condition"
                value="Adherence to agreed hiring profile, process and assessments"
                class="ml-2"
              />
              <span class="ml-2"
                >Adherence to agreed hiring profile, process and assessments</span
              >
            </label>
            <label class="flex items-start">
              <input
                type="checkbox"
                v-model="condition"
                value="Adherence to OV Support based on the required no. of POCs and sched"
                class="ml-2"
              />
              <span class="ml-2"
                >Adherence to OV Support based on the required no. of POCs and sched</span
              >
            </label>
            <label class="flex items-start">
              <input
                type="checkbox"
                v-model="condition"
                value="Program-specific assessment per SOW"
                class="ml-2"
              />
              <span class="ml-2">Program-specific assessment per SOW</span>
            </label>
            <label class="flex items-start">
              <input
                type="checkbox"
                v-model="condition"
                value="Employment requirements prior Day1 per SOW"
                class="ml-2"
              />
              <span class="ml-2">Employment requirements prior Day1 per SOW</span>
            </label>
            <label class="flex items-start">
              <input type="checkbox" v-model="condition" class="ml-2" />
              <span class="ml-2">Specific previous work exp per SOW</span>
            </label>
            <label class="flex items-start">
              <input type="checkbox" v-model="condition" class="ml-2" />
              <span class="ml-2"
                >Roster submission requirement for ID creation prior Day 1</span
              >
            </label>
            <label class="flex items-start">
              <input
                type="checkbox"
                v-model="condition"
                value="Programs following VXI standard hiring process and emp req’ts"
                class="ml-2"
              />
              <span class="ml-2"
                >Programs following VXI standard hiring process and emp req’ts</span
              >
            </label>
            <label class="flex items-start">
              <input
                type="checkbox"
                v-model="condition"
                value="Agreed hiring profile, process and assessments"
                class="ml-2"
              />
              <span class="ml-2">Agreed hiring profile, process and assessments</span>
            </label>

            <label class="flex items-start">
              <input
                type="checkbox"
                v-model="condition"
                value="Sample call recordings, sample transactions"
                class="ml-2"
              /><span class="ml-2">
                Sample call recordings, sample transactions</span
              ></label
            >

            <label class="flex items-start">
              <input
                type="checkbox"
                v-model="condition"
                value="Approved wage rates and job offer/contract template"
                class="ml-2"
              /><span class="ml-2">
                Approved wage rates and job offer/contract template</span
              ></label
            >

            <label class="flex items-start">
              <input
                type="checkbox"
                v-model="condition"
                value="Agreed ramp plan with WF, CS, PMO"
                class="ml-2"
              /><span class="ml-2">Agreed ramp plan with WF, CS, PMO</span></label
            >
          </label>
          <label class="block py-6"
            >Remarks<textarea
              required
              type="text"
              v-model="remarks"
              class="block w-full h-20 mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label>
        </div>
        <div class="flex justify-center py-4">
          <button
            type="submit"
            class="self-center px-4 py-1 font-bold text-white bg-orange-500 rounded hover:bg-gray-600"
          >
            <i class="fa fa-save"></i> Save
          </button>
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
      //sites_selected: "",
      //programs_selected: "",
      //date_selected: "",
      type_of_hiring: "",
      external_target: "",
      internal_target: "",
      total_target: 0,
      with_erf: "",
      original_start_date: "",
      wfm_date_requested: "",
      remarks: "",
      category: "",
      notice_days: 0,
      erf_number: "",
      condition: [],
      within_sla: "",
      approved_by: "",
      sites: [],
      daterange: [],
      programs: [],
    };
  },

  computed: {
    programs_selected() {
      return this.$route.query.program;
    },
    sites_selected() {
      return this.$route.query.site;
    },
    date_selected() {
      return this.$route.query.daterange;
    },
    total_target_computed() {
      const external = parseInt(this.external_target) || 0;
      const internal = parseInt(this.internal_target) || 0;
      return (external + internal).toFixed();
    },
    notice_days_computed() {
      const osd = Date.parse(this.original_start_date) || 0;
      const wrd = Date.parse(this.wfm_date_requested) || 0;
      return Math.round((osd - wrd) / (24 * 60 * 60 * 1000));
    },
    notice_weeks() {
      return parseFloat(this.notice_days / 7).toFixed(1);
    },
  },
  mounted() {
    console.log("Component mounted.");
    this.getSites();
    this.getPrograms();
    this.getDateRange();
  },
  methods: {
    syncTotalTarget: function () {
      this.total_target = this.total_target_computed;
    },

    syncNoticeDays: function () {
      this.notice_days = this.notice_days_computed;
    },
    syncNoticeWeeks: function () {
      this.notice_weeks = this.notice_weeks_computed;
    },
    async getSites() {
      console.log(this.sites_selected);
      await axios
        .get("http://127.0.0.1:8000/api/sites")
        .then((response) => {
          this.sites = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getPrograms() {
      console.log(this.programs_selected);
      await axios
        .get("http://127.0.0.1:8000/api/programs")
        .then((response) => {
          this.programs = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getDateRange() {
      console.log(this.date_selected);
      await axios
        .get("http://127.0.0.1:8000/api/daterange")
        .then((response) => {
          this.daterange = response.data.data;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    addClass() {
      const formData = {
        site_id: this.sites_selected,
        program_id: this.programs_selected,
        type_of_hiring: this.type_of_hiring,
        external_target: this.external_target,
        internal_target: this.internal_target,
        total_target: this.total_target,
        notice_days: this.notice_days,
        notice_weeks: this.notice_weeks,
        with_erf: this.with_erf,
        erf_number: this.erf_number,
        category: this.category,
        original_start_date: this.original_start_date,
        wfm_date_requested: this.wfm_date_requested,
        within_sla: this.within_sla,
        remarks: this.remarks,
        condition: this.condition,
        date_range_id: this.date_selected,
        approved_status: "pending",
        approved_by: this.approved_by,
        status: "Active",
        is_active: 1,
        created_by: this.$store.state.user_id,
      };
      axios
        .post("http://127.0.0.1:8000/api/classes/", formData)
        .then((response) => {
          console.log(response.data);
          this.site_id = "";
          this.program_id = "";
          this.type_of_hiring = "";
          this.external_target = "";
          this.internal_target = "";
          this.total_target = "";
          this.notice_days = "";
          this.notice_weeks = "";
          this.with_erf = "";
          this.erf_number = "";
          this.category = "";
          this.original_start_date = "";
          this.wfm_date_requested = "";
          this.within_sla = "";
          this.remarks = "";
          this.condition = "";
          this.date_range_id = "";
          this.approved_status = "";
          this.is_active = "";
          this.created_by = "";
          this.approved_by = "";
          this.two_dimensional_id = "";
          this.$router.push("/capfile");
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
  },
};
</script>
<style>
[title] {
  position: relative;
}

[title]:after {
  content: attr(title);
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%);
  white-space: nowrap;
  background-color: black;
  color: white;
  padding: 5px;
  border-radius: 5px;
}

[title]:before {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: transparent transparent black transparent;
}
</style>
