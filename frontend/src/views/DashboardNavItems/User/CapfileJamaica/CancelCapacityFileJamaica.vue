<template>
  <header class="w-full bg-white shadow">
    <div class="items-center w-full py-2">
      <h1 class="text-xl font-bold text-center">
        CANCEL CLASS
      </h2>
        </div>
      </header>
  <div class="px-12 py-8">
    <form @submit.prevent="cancelClass">
      <div
        class="px-12 py-6 mx-auto font-semibold bg-white border-2 border-orange-600 max-w-7xl sm:px-2 lg:px-2"
      >
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5">
          <label class="block">
            Site
            <select
              v-model="sites_selected"
              disabled
              class="block w-full mt-1 bg-gray-300 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
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
              class="block w-full mt-1 bg-gray-300 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
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
              class="block w-full mt-1 bg-gray-300 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
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
              disabled
              type="number"
              v-model="external_target"
              name="external_target"
              class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncTotalTarget"
            />
          </label>
          <label class="block">
            Internal Target
            <input
              disabled
              type="number"
              class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
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
              class="block w-full mt-1 bg-gray-300 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label>
          <label class="block"
            >Original Start Date
            <input
              disabled
              type="date"
              v-model="original_start_date"
              class="block w-full mt-1 bg-gray-300 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label>

          <label class="block"
            >Cancelled Date
            <input
              type="date"
              v-model="cancelled_date"
              class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncNoticeDays"
            />
          </label>
          <label class="block">
            Notice Days
            <input
              type="number"
              v-model="notice_days"
              disabled
              class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncNoticeWeeks"
            />
          </label>
          <label class="block">
            Notice Weeks
            <input
              type="text"
              v-model="notice_weeks"
              disabled
              class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label>
          <label class="block">
            Weeks Start
            <select
              disabled
              v-model="date_selected"
              class="block w-full mt-1 bg-gray-300 border border-2 border-black rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
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
            >Category
            <select
              required
              v-model="category"
              class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
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
              class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
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
              v-model="agreed_start_date"
              class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              @change="syncNoticeDays"
            />
          </label>
          <label class="block">Approved by
            <select required v-model="approved_by"
              class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100">
              <option disabled value="" selected>Please select one</option>
              <option value="VP-Ops">VP-Ops</option>
              <option value="VP-Training">VP-Training</option>
              <option value="VP-WF">VP-WF</option>
              <option value="VP-TA">VP-TA</option>
              <option value="CS">CS</option>
              <option value="WF">WF</option>
              <option value="Ops">Ops</option>
              <option value="Training">Training</option>
              <option value="TA">TA</option>
            </select>
          </label>
        </div>
        <div class="py-6">
          <label class="block py-6"
            >Requested by:
            <input
              type="checkbox"
              v-model="cancelled_by"
              value="Talent Acquisition"
            />Talent Acquisition
            <input type="checkbox" v-model="cancelled_by" value="Workforce" />Workforce
            <input type="checkbox" v-model="cancelled_by" value="Training" />Training
            <input type="checkbox" v-model="cancelled_by" value="Client" />Client
            <input type="checkbox" v-model="cancelled_by" value="Operation" />Operation

            <label class="block py-6" v-if="cancelled_by.includes('Talent Acquisition')"
              >Talent Acquisition
              <input
                type="text"
                v-model="ta"
                class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              />
            </label>

            <label class="block py-6" v-if="cancelled_by.includes('Workforce')"
              >Workforce
              <input
                type="text"
                v-model="wf"
                class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              />
            </label>

            <label class="block py-6" v-if="cancelled_by.includes('Training')"
              >Training
              <input
                type="text"
                v-model="tr"
                class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              />
            </label>

            <label class="block py-6" v-if="cancelled_by.includes('Client')"
              >Client
              <input
                type="text"
                v-model="cl"
                class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              />
            </label>

            <label class="block py-6" v-if="cancelled_by.includes('Operation')"
              >Operation
              <input
                type="text"
                v-model="op"
                class="block w-full mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
              />
            </label>
          </label>

          <label class="block py-6"
            >Reason for Cancellation<textarea
              required
              type="text"
              v-model="remarks"
              class="block w-full h-20 mt-1 border border-2 border-black rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
            />
          </label>
        </div>
        <div class="flex justify-center py-4">
          <button
            type="submit"
            class="self-center px-4 py-1 font-bold text-white bg-orange-500 rounded hover:bg-gray-600"
          >
            <i class="fa fa-save"></i> Cancel
          </button>
        </div>
        <div class="flex justify-between">
          <router-link to="/capfile">
          <button class="px-4 py-1 ml-auto text-white bg-blue-500 rounded hover:bg-gray-600">
            <i class="fa fa-chevron-circle-left "></i> Back</button></router-link>
        </div>
      </div>
    </form>
  </div>
  <div class="px-12 py-6">
    <h2 class="font-bold">History</h2>
    <table class="w-full border table-auto">
      <thead class="font-bold text-left border border-black">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Site</th>
          <th class="px-4 py-2">Line of Business</th>
          <th class="px-4 py-2">Type of Hiring</th>
          <th class="px-4 py-2">Total Target</th>
          <th class="px-4 py-2">Original Start Date</th>
          <th class="px-4 py-2">Movement Date</th>
          <th class="px-4 py-2">Weeks Range</th>
          <th class="px-4 py-2">Within SLA?</th>
          <th class="px-4 py-2">Agreed Start Date</th>
          <th class="px-4 py-2">Requested by</th>
          <th class="px-4 py-2">Approved by</th>
          <th class="px-4 py-2">Transaction Type</th>
        </tr>
      </thead>
      <tbody class="text-gray-700">
        <tr
          v-for="classes in classes"
          :key="classes.id"
          class="border-b border-gray-200 hover:bg-gray-100"
        >
          <td class="px-4 py-3">{{ classes.pushedback_id }}</td>
          <td class="px-4 py-3">{{ classes.site.name }}</td>
          <td class="px-4 py-3">{{ classes.program.name }}</td>
          <td class="px-4 py-3">{{ classes.type_of_hiring }}</td>
          <td class="px-4 py-3">{{ classes.total_target }}</td>
          <td class="px-4 py-3">{{ classes.original_start_date }}</td>
          <td class="px-4 py-3">{{ classes.wfm_date_requested }}</td>
          <td class="px-4 py-3">{{ classes.date_range.date_range }}</td>
          <td class="px-4 py-3">{{ classes.within_sla }}</td>
          <td class="px-4 py-3">{{ classes.agreed_start_date }}</td>
          <td class="px-4 py-3">{{ classes.requested_by }}</td>
          <td class="px-4 py-3">{{ classes.approved_by }}</td>
          <td class="px-4 py-3">{{ classes.changes }}</td>
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
      original_start_date: "",
      agreed_start_date:"",
      cancelled_date: "",
      remarks: "",
      reason: [],
      category: "",
      notice_days: 0,
      sites: [],
      daterange: [],
      programs: [],
      within_sla: "",
      approved_by: "",
      cancelled_by: [],
      classes: [],
      ta: "",
      wf: "",
      tr: "",
      cl: "",
      op: "",
    };
  },
  computed: {
    total_target_computed() {
      const external = parseInt(this.external_target) || 0;
      const internal = parseInt(this.internal_target) || 0;
      return (external + internal).toFixed();
    },
    notice_days_computed() {
      const osd = Date.parse(this.agreed_start_date) || 0;
      const wrd = Date.parse(this.cancelled_date) || 0;
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
        .get("http://127.0.0.1:8000/api/sites5")
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
        .get("http://127.0.0.1:8000/api/programs5")
        .then((response) => {
          this.programs = response.data.data;
          console.log(response.data.data);
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
          this.cancelled_date = classObj.cancelled_date;
          this.notice_days = classObj.notice_days;
          this.notice_weeks = classObj.notice_weeks;
          this.date_selected = classObj.date_range.id;
          this.category = classObj.category;
          this.within_sla = classObj.within_sla;
          this.agreed_start_date = classObj.agreed_start_date;
          this.changes = classObj.changes;
          this.approved_by = classObj.approved_by;

          console.log(classObj);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    cancelClass() {
      const formData = {
        site_id: this.sites_selected,
        program_id: this.programs_selected,
        date_range_id: this.date_selected,
        cancelled_by: this.cancelled_by,
        cancelled_date: this.cancelled_date,
        approved_by: this.approved_by,
        remarks: this.remarks,
        approved_status: "pending",
        status: "cancelled",
        ta: this.ta,
        wf: this.wf,
        tr: this.tr,
        cl: this.cl,
        op: this.op,
      };
      axios
        .put(
          "http://127.0.0.1:8000/api/classes/cancel/" + this.$route.params.id,
          formData
        )
        .then((response) => {
          console.log(response.data);
          this.site_id = "";
          this.program_id = "";
          this.date_range_id = "";
          this.approved_status = "";
          this.approved_by = "";
          this.remarks = "";
          this.cancelled_by = [];
          this.cancelled_date = "";
          this.ta = "";
          this.wf = "";
          this.tr = "";
          this.cl = "";
          this.op = "";
          this.$router.push("http://127.0.0.1:8000/api/capfilejamaica", () => {
            location.reload();
          });
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
  },
};
</script>
