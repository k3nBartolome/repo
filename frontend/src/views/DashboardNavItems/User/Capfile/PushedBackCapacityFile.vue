<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full px-4 py-6 max-w-7xl sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        Capacity File Manager
      </h1>
    </div>
  </header>
  <div class="px-12 py-8">
    <form @submit.prevent="pushClass">
      <div class="px-12 py-6 mx-auto font-semibold bg-white max-w-7xl sm:px-2 lg:px-2">
        <div class="py-8 font-bold">
          <label class="block">
            Targets
            <input
              type="radio"
              v-model="changes"
              value="Change Targets"
              required
              class="ml-2 mr-4 border-gray-500 focus:ring-indigo-500 focus:border-indigo-500"
            />
            Dates
            <input
              type="radio"
              v-model="changes"
              value="Change Dates"
              required
              class="ml-2 mr-4 border-gray-500 focus:ring-indigo-500 focus:border-indigo-500"
            />
            Both
            <input
              type="radio"
              v-model="changes"
              required
              checked
              value="Change Targets and Dates"
              class="ml-2 mr-4 border-gray-500 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </label>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5">
          <label class="block">
            Site
            <select
              v-model="sites_selected"
              disabled
              class="block w-full mt-1 bg-gray-300 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
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
              class="block w-full mt-1 bg-gray-300 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
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
              disabled
              v-model="type_of_hiring"
              class="block w-full mt-1 bg-gray-300 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
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
              :disabled="isTargetDisabled"
              v-model="external_target"
              name="external_target"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncTotalTarget"
            />
          </label>
          <label class="block">
            Internal Target
            <input
              required
              type="number"
              :disabled="isTargetDisabled"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              v-model="internal_target"
              @change="syncTotalTarget"
            />
          </label>
          <label class="block">
            Total Target
            <input
              type="number"
              v-model="total_target"
              disabled
              class="block w-full mt-1 bg-gray-300 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label>
          <label class="block"
            >Original Start Date
            <input
              disabled
              type="date"
              v-model="original_start_date"
              class="block w-full mt-1 bg-gray-300 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncNoticeDays"
            />
          </label>

          <label class="block"
            >Movement Date
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
              disabled
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncNoticeWeeks"
            />
          </label>
          <label class="block">
            Notice Weeks
            <input
              type="text"
              v-model="notice_weeks"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label>
          <label class="block">
            Weeks Start
            <select
              disabled
              v-model="date_selected"
              class="block w-full mt-1 bg-gray-300 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
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
          <!--  <label class="block"
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
              type="number"
              v-model="erf_number"
              disabled
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label> -->
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
            >Agreed Start Date
            <input
              type="date"
              required
              v-model="agreed_start_date"
              :disabled="isDateDisabled"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncNoticeDays"
            />
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
        <div class="py-6">
          <label class="block"
            >Condition
            <select
              required
              v-model="condition"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            >
              <option disabled value="" selected>Please select one</option>
              <option value="Filed ERF with necessary approvals and within timeline">
                <label
                  ><input type="checkbox" />Filed ERF with necessary approvals and within
                  timeline</label
                >
              </option>
              <option value="Adherence to hiring demand from initial sign-off">
                <label
                  ><input type="checkbox" />Adherence to hiring demand from initial
                  sign-off</label
                >
              </option>
              <option value="Adherence to hiring timelines from initial sign-off">
                <label
                  ><input type="checkbox" />Adherence to hiring timelines from initial
                  sign-off</label
                >
              </option>
              <option value="Adherence to agreed hiring profile, process and assessments">
                <label
                  ><input type="checkbox" />Adherence to agreed hiring profile, process
                  and assessments</label
                >
              </option>
              <option
                value="Adherence to OV Support based on the required no. of POCs and sched"
              >
                <label
                  ><input type="checkbox" />Adherence to OV Support based on the required
                  no. of POCs and sched</label
                >
              </option>
              <option value="Program-specific assessment per SOW">
                <label
                  ><input type="checkbox" />Program-specific assessment per SOW</label
                >
              </option>
              <option value="Employment requirements prior Day1 per SOW">
                <label
                  ><input type="checkbox" />Employment requirements prior Day1 per
                  SOW</label
                >
              </option>
              <option value="Specific previous work exp per SOW">
                <label><input type="checkbox" />Specific previous work exp per SOW</label>
              </option>
              <option value="Roster submission requirement for ID creation prior Day 1">
                <label
                  ><input type="checkbox" />Roster submission requirement for ID creation
                  prior Day 1</label
                >
              </option>
              <option
                value="Programs following VXI standard hiring process and emp req’ts"
              >
                <label
                  ><input type="checkbox" />Programs following VXI standard hiring process
                  and emp req’ts</label
                >
              </option>
              <option value="Agreed hiring profile, process and assessments">
                <label
                  ><input type="checkbox" />Agreed hiring profile, process and
                  assessments</label
                >
              </option>
              <option value="Sample call recordings, sample transactions">
                <label
                  ><input type="checkbox" />Sample call recordings, sample
                  transactions</label
                >
              </option>
              <option value="Approved wage rates and job offer/contract template">
                <label
                  ><input type="checkbox" />Approved wage rates and job offer/contract
                  template</label
                >
              </option>
              <option value="Agreed ramp plan with WF, CS, PMO">
                <label><input type="checkbox" />Agreed ramp plan with WF, CS, PMO</label>
              </option>
            </select>
          </label>
          <label class="block py-6"
            >Requested by:
            <input
              type="checkbox"
              v-model="requested_by"
              value="Talent Acquisition"
              required
            />Talent Acquisition
            <input type="checkbox" v-model="requested_by" value="Workforce" />Workforce
            <input type="checkbox" v-model="requested_by" value="Training" />Training
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
            class="self-center px-4 py-1 font-bold text-white bg-orange-600 rounded hover:bg-gray-600"
          >
            <i class="fa fa-save"></i> Pushback
          </button>
        </div>
      </div>
    </form>
  </div>
  <div>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Site</th>
          <th>Line of Business</th>
          <th>Total Target</th>
          <th>Transaction</th>

        </tr>
      </thead>
      <tbody>
        <tr v-for="classes in classes" :key="classes.id">
          <td>{{ classes.id }}</td>

          <td>{{ classes.site.name }}</td>
          <td>{{ classes.program.name }}</td>

          <td>{{ classes.total_target }}</td>

          <td>{{ classes.changes }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
import axios from "axios";
export default {
  data() {
    return {
      sites_selected: "",
      programs_selected: "",
      date_selected: "",
      type_of_hiring: "",
      external_target: "",
      internal_target: "",
      total_target: 0,
      with_erf: "",
      original_start_date: "",
      wfm_date_requested: "",
      remarks: "",
      category: "",
      approved_by: "",
      notice_days: 0,
      erf_number: "",
      sites: [],
      daterange: [],
      programs: [],
      agreed_start_date: "",
      condition: "",
      within_sla: "",
      changes: "",
      disableForm: true,
      requested_by: [],
      classes: [],
    };
  },
  computed: {
    isTargetDisabled() {
      if (this.changes === "Change Dates") {
        return true;
      } else if (this.changes === "Change Targets") {
        return false;
      } else {
        return false;
      }
    },
    isDateDisabled() {
      if (this.changes === "Change Targets") {
        return true;
      } else if (this.changes === "Change Dates") {
        return false;
      } else {
        return false;
      }
    },
    total_target_computed() {
      const external = parseInt(this.external_target) || 0;
      const internal = parseInt(this.internal_target) || 0;
      return (external + internal).toFixed();
    },
    notice_days_computed() {
      const osd = Date.parse(this.agreed_start_date) || 0;
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
    this.getClasses();
    this.getTransaction();
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
    async getClasses() {
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
          this.condition = classObj.condition;
          this.erf_number = classObj.erf_number;
          this.approved_by = classObj.approved_by;

          console.log(classObj);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getTransaction() {
      await axios
        .get("http://127.0.0.1:8000/api/transaction/" + this.$route.params.id)
        .then((response) => {
          this.classes = response.data.classes;
          console.log(response.data.classes);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    pushClass() {
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
        category: this.category,
        original_start_date: this.original_start_date,
        wfm_date_requested: this.wfm_date_requested,
        within_sla: this.within_sla,
        remarks: this.remarks,
        requested_by: this.requested_by,
        erf_number: this.erf_number,
        date_range_id: this.date_selected,
        approved_status: "pending",
        status: "1",
        is_active: 1,
        updated_by: this.$store.state.user_id,
        agreed_start_date: this.agreed_start_date,
        condition: this.condition,
        changes: this.changes,
        approved_by: this.approved_by,
      };
      axios
        .put(
          "http://127.0.0.1:8000/api/classes/pushedback/" + this.$route.params.id,
          formData
        )
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
          this.requested_by = "";
          this.date_range_id = "";
          this.approved_status = "";
          this.is_active = "";
          this.updated_by = "";
          this.agreed_start_date = "";
          this.condition = "";
          this.approved_by = "";
          this.changes = "";
          this.$router.push("/capfile");
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
  },
};
</script>
