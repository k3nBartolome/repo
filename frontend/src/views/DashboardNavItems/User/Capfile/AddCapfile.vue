<!-- eslint-disable vue/require-v-for-key -->
<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full px-4 py-6 max-w-7xl sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        Capacity File Manager
      </h1>
    </div>
  </header>
  <div class="py-8 px-12">
    <form @submit.prevent="addClass">
      <div
        class="px-12 py-6 mx-auto font-semibold bg-white border-2 border-orange-600 max-w-7xl sm:px-2 lg:px-2"
      >
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5">
          <label class="block">
            Site
            <select
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
              type="number"
              v-bind:title="tooltipMessage"
              v-model="external_target"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncTotalTarget"
            />
          </label>
          <label class="block">
            Internal Target
            <input type="number" v-model="internal_target" @change="syncTotalTarget" />
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
              type="date"
              v-model="original_start_date"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncNoticeDays"
            />
          </label>

          <label class="block"
            >WFM Requested Date
            <input
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
              @change="syncWithinSla"
            />
          </label>
          <label class="block">
             Weeks Start
              <select v-model="weeks_start"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100">
              <option disabled value="" selected>Please select one</option>
              <option v-for="option in options" :key="option.id">{{ option.name }}
                {{ option.label }}
              </option>
              <option value="Jan 1-Jan 7</">Jan 1-Jan 7</option>
              <option value="Jan 8-Jan 14">Jan 8-Jan 14</option>
              <option value="Jan 15-Jan 21">Jan 15-Jan 21</option>
              <option value="Jan 22-Jan 28">Jan 22-Jan 28</option>
              <option value="Jan">Jan</option>
              <option value="Jan 29-Feb 4">Jan 29-Feb 4</option>
              <option value="Feb 5-Feb 11">Feb 5-Feb 11</option>
              <option value="Feb 12-Feb 18">Feb 12-Feb 18</option>
              <option value="Feb 19-Feb 25">Feb 19-Feb 25</option>
              <option value="Feb">Feb</option>
              <option value="Feb 26-Mar 4">Feb 26-Mar 4</option>
              <option value="Mar 5-Mar 11">Mar 5-Mar 11</option>
              <option value="Mar 12-Mar 18">Mar 12-Mar 18</option>
              <option value="Mar 19-Mar 25">Mar 19-Mar 25</option>
              <option value="Mar">Mar</option>
              <option value="Mar 26-Apr 1">Mar 26-Apr 1</option>
              <option value="April 2-Apr 8">April 2-Apr 8</option>
              <option value="Apr 9-Feb 15">Apr 9-Feb 15</option>
              <option value="Apr 16-Apr 22">Apr 16-Apr 22</option>
              <option value="Apr 23-Apr 29">Apr 23-Apr 29</option>
              <option value="Apr">Apr</option>
              <option value="Apr 30-May 6">Apr 30-May 6</option>
              <option value="May 7-May 13">May 7-May 13</option>
              <option value="May 14-Jan 20">May 14-Jan 20</option>
              <option value="May 21-May 27">May 21-May 27</option>
              <option value="May">May</option>
              <option value="May 28-Jun 3">May 28-Jun 3</option>
              <option value="jun 4-Feb 10">jun 4-Feb 10</option>
              <option value="Jun 11-Jun 17">Jun 11-Jun 17</option>
              <option value="Jun 18-Jun 24">Jun 18-Jun 24</option>
              <option value="Jun">Jun</option>
              <option value="Jun 25-Jul 1">Jun 25-Jul 1</option>
              <option value="Jul 2-Jul 8</">Jul 2-Jul 8</option>
              <option value="Jul 8-Jul 15">Jul 8-Jul 15</option>
              <option value="Jul 16-Jul 22">Jul 16-Jul 22</option>
              <option value="Jul 23-Jul 29">Jul 23-Jul 29</option>
              <option value="Jul">Jul</option>
              <option value="July 30-Aug 5">July 30-Aug 5</option>
              <option value="Aug 6-Aug 12">Aug 6-Aug 12</option>
              <option value="Aug 13-Aug 19">Aug 13-Aug 19</option>
              <option value="Aug 20-Aug 26">Aug 20-Aug 26</option>
              <option value="Aug">Aug</option>
              <option value="Aug 26-Sep 2">Aug 26-Sep 2</option>
              <option value="Sep 3-Sep 19">Sep 3-Sep 19</option>
              <option value="Sep 10-Sep 16">Sep 10-Sep 16</option>
              <option value="Sep 17-Sep 23">Sep 17-Sep 23</option>
              <option value="Sep 24-Sep 30">Sep 24-Sep 30</option>
              <option value="Sep">Sep</option>
              <option value="Oct 1-Oct 7</">Oct 1-Oct 7</option>
              <option value="Oct 8-Oct 14">Oct 8-Oct 14</option>
              <option value="Oct 15-Oct 21">Oct 15-Oct 21</option>
              <option value="Oct 22-Oct 28">Oct 22-Oct 28</option>
              <option value="Oct">Oct</option>
              <option value="Oct 29-Nov 4">Oct 29-Nov 4</option>
              <option value="Nov 5-Nov 11">Nov 5-Nov 11</option>
              <option value="Nov 12-Nov 18">Nov 12-Nov 18</option>
              <option value="Nov 19-Nov 25">Nov 19-Nov 25</option>
              <option value="Nov">Nov</option>
              <option value="Nov 26-Dec 2">Nov 26-Dec 2</option>
              <option value="Dec 3-Dec 9</">Dec 3-Dec 9</option>
              <option value="Dec 10-Dec 16">Dec 10-Dec 16</option>
              <option value="Dec 17-Dec 23">Dec 17-Dec 23</option>
              <option value="Dec 18-Dec 30">Dec 18-Dec 30</option>
              <option value="Dec">Dec</option>
            </select>
          </label>
          <label class="block">
            Growth
            <input
              type="number"
              v-model="growth"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label>
          <label class="block">
            Backfill
            <input
              type="number"
              v-model="backfill"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label>
          <label class="block"
            >With ERF?
            <select
              v-model="with_erf"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            >
              <option disabled value="" selected>Please select one</option>
              <option value="yes">Yes</option>
              <option value="no">No</option>
            </select>
          </label>
          <label class="block"
            >Category
            <select
              v-model="category"
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            >
              <option disabled value="" selected>Please select one</option>
              <option value="placeholder">Placeholder</option>
              <option value="confirm">Confirm</option>
            </select>
          </label>
          <label class="block"
            >Within SLA?
            <input
              type="text"
              v-model="within_sla"
              readonly
              class="block w-full mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label>
        </div>
        <div class="py-4">
          <label class="block" v-if="within_sla ==='Yes'"
            >Out of SLA Reason<textarea
              type="text"
              v-model="reason"
              class="block w-full h-20 mt-1 border rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label>
          <label class="block"
            >Remarks<textarea
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
      sites_selected: "",
      programs_selected: "",
      type_of_hiring: "",
      external_target: "",
      internal_target: "",
      total_target: 0,
      with_erf: "",
      original_start_date: "",
      wfm_date_requested: "",
      remarks: "",
      reason: [],
      category: "",
      notice_days: 0,
      growth: "",
      backfill: "",
      weeks_start: "",
      options: [
        { label: "Jan 1-Jan 7", value: "Jan 1-Jan 7" },
        { label: "Jan 8-Jan 14", value: "Jan 8-Jan 14" },
        { label: "Jan 15-Jan 21", value: "Jan 15-Jan 21" },
        { label: "Jan 22-Jan 28", value: "Jan 22-Jan 28" },
        { label: "Jan 29-Feb 4", value: "Jan 29-Feb 4" },
        { label: "Feb 5-Feb 11", value: "Feb 5-Feb 11" },
        { label: "Feb 12-Feb 18", value: "Feb 12-Feb 18" },
        { label: "Feb 19-Feb 25", value: "Feb 19-Feb 25" },
        { label: "Feb 26-Mar 4", value: "Feb 26-Mar 4" },
        { label: "Mar 5-Mar 11", value: "Mar 5-Mar 11" },
        { label: "Mar 12-Mar 18", value: "Mar 12-Mar 18" },
        { label: "Mar 19-Mar 25", value: "Mar 19-Mar 25" },
        { label: "Mar 26-Apr 1", value: "Mar 26-Apr 1" },
        { label: "April 2-Apr 8", value: "April 2-Apr 8" },
        { label: "Apr 9-Feb 15", value: "Apr 9-Feb 15" },
        { label: "Apr 16-Apr 22", value: "Apr 16-Apr 22" },
        { label: "Apr 23-Apr 29", value: "Apr 23-Apr 29" },
        { label: "Apr 30-May 6", value: "Apr 30-May 6" },
        { label: "May 7-May 13", value: "May 7-May 13" },
        { label: "May 14-Jan 20", value: "May 14-Jan 20" },
        { label: "May 21-May 27", value: "May 21-May 27" },
        { label: "May 28-Jun 3", value: "May 28-Jun 3" },
        { label: "jun 4-Feb 10", value: "jun 4-Feb 10" },
        { label: "Jun 11-Jun 17", value: "Jun 11-Jun 17" },
        { label: "Jun 18-Jun 24", value: "Jun 18-Jun 24" },
        { label: "Jun 25-Jul 1", value: "Jun 25-Jul 1" },
        { label: "Jul 2-Jul 8", value: "Jul 2-Jul 8" },
        { label: "Jul 8-Jul 15", value: "Jul 8-Jul 15" },
        { label: "Jul 16-Jul 22", value: "Jul 16-Jul 22" },
        { label: "Jul 23-Jul 29", value: "Jul 23-Jul 29" },
        { label: "July 30-Aug 5", value: "July 30-Aug 5" },
        { label: "Aug 6-Aug 12", value: "Aug 6-Aug 12" },
        { label: "Aug 13-Aug 19", value: "Aug 13-Aug 19" },
        { label: "Aug 20-Aug 26", value: "Aug 20-Aug 26" },
        { label: "Aug 26-Sep 2", value: "Aug 26-Sep 2" },
        { label: "Sep 3-Sep 19", value: "Sep 3-Sep 19" },
        { label: "Sep 10-Sep 16", value: "Sep 10-Sep 16" },
        { label: "Sep 17-Sep 23", value: "Sep 17-Sep 23" },
        { label: "Sep 24-Sep 30", value: "Sep 24-Sep 30" },
        { label: "Oct 1-Oct 7", value: "Oct 1-Oct 7" },
        { label: "Oct 8-Oct 14", value: "Oct 8-Oct 14" },
        { label: "Oct 15-Oct 21", value: "Oct 15-Oct 21" },
        { label: "Oct 22-Oct 28", value: "Oct 22-Oct 28" },
        { label: "Oct 29-Nov 4", value: "Oct 29-Nov 4" },
        { label: "Nov 5-Nov 11", value: "Nov 5-Nov 11" },
        { label: "Nov 12-Nov 18", value: "Nov 12-Nov 18" },
        { label: "Nov 19-Nov 25", value: "Nov 19-Nov 25" },
        { label: "Nov 26-Dec 2", value: "Nov 26-Dec 2" },
        { label: "Dec 3-Dec 9", value: "Dec 3-Dec 9" },
        { label: "Dec 10-Dec 16", value: "Dec 10-Dec 16" },
        { label: "Dec 17-Dec 23", value: "Dec 17-Dec 23" },
        { label: "Dec 18-Dec 30", value: "Dec 18-Dec 30" },
      ],
      sites: [],
      programs: [],
      tooltipMessage: "External Target",
    };
  },
  computed: {
    filteredOptions() {
      const searchTerm = this.weeks_start.toLowerCase();
      return this.options.filter((option) =>
        option.label.toLowerCase().includes(searchTerm)
      );
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
      return parseFloat(this.notice_days / 7);
    },
    within_sla() {
      const days = parseFloat(this.notice_days) || 0;
      const days_computed = (days / 7).toFixed(2);
      return days_computed > 5 ? "Yes" : "No";
    },
  },
  mounted() {
    console.log("Component mounted.");
    this.getSites();
    this.getPrograms();
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
    syncWithinSla: function () {
      this.within_sla = this.within_sla_computed;
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
        category: this.category,
        original_start_date: this.original_start_date,
        wfm_date_requested: this.wfm_date_requested,
        within_sla: this.within_sla,
        remarks: this.remarks,
        reason: this.reason,
        growth: this.growth,
        backfill: this.backfill,
        weeks_start: this.weeks_start,
        approved_status: "pending",
        status: "ok",
        is_active: 1,
        created_by: this.$store.state.user_id,
      };
      axios
        .post("http://127.0.0.1:8000/api/classes", formData)
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
          this.category = "";
          this.original_start_date = "";
          this.wfm_date_requested = "";
          this.within_sla = "";
          this.remarks = "";
          this.reason = "";
          this.growth = "";
          this.backfill = "";
          this.weeks_start = "";
          this.approved_status = "";
          this.is_active = "";
          this.created_by = "";
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
