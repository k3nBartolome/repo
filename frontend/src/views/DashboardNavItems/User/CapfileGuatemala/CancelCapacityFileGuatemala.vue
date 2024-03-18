<template>
  <header class="w-full bg-white">
    <div class="items-center w-full">
      <h2 class="text-xl font-bold text-center">CANCEL CLASS</h2>
    </div>
  </header>
  <header class="w-full">
    <div class="flex items-center w-full max-w-screen-xl sm:px-2 lg:px-2">
      <h2 class="pl-8 text-sm font-bold tracking-tight text-gray-900">
        <button
          @click="showModal = true"
          class="px-4 py-2 mr-2 text-white bg-blue-500 rounded hover:bg-blue-600"
        >
          View History
        </button>
      </h2>
    </div>
  </header>
  <div class="py-0 px-4">
    <div class="px-4 py-0 bg-white">
      <div
        class="fixed inset-0 z-50 flex items-center justify-center modal mx-4"
        v-if="showModal"
      >
        <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
        <div
          class="min-w-full max-w-3xl w-auto p-4 bg-white rounded shadow-lg modal-content px-4"
        >
          <header class="px-4 py-2 border-b-2 border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Class History</h2>
          </header>
          <button
            @click="showModal = false"
            class="absolute top-0 right-0 m-4 text-gray-600 hover:text-gray-800"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
          <div class="modal-scrollable-content">
            <table class="min-w-full border-collapse border-2 border-gray-300">
              <thead>
                <tr class="border-b-4 border-gray-300 bg-gray-100 text-center">
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    ID
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Site
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Line of Business
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Type of Hiring
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Total Target
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Original Start Date
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Movement Date
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Weeks Range
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Within SLA?
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Agreed Start Date
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Requested by
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Approved by
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Transaction Type
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="classes in classes"
                  :key="classes.id"
                  class="border-2 border-black"
                >
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.pushedback_id }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.site.name }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.program.name }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.type_of_hiring }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.total_target }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.original_start_date }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.wfm_date_requested }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.date_range.date_range }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.within_sla }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.agreed_start_date }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.requested_by }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.approved_by }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.changes }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="px-12 py-8 font-serifs">
    <form @submit.prevent="cancelClass">
      <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block">
            Site
            <select 
              v-model="sites_selected"
              disabled
              class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
              required
              @change="getSites"
            >
              <option disabled value="" selected>Please select one</option>
              <option v-for="site in sites" :key="site.id" :value="site.id">
                {{ site.name }}
              </option>
            </select>
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block">
            Line of Business
            <select
              disabled
              v-model="programs_selected"
              class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
              required
              @change="getPrograms"
            >
              <option disabled value="" selected>Please select one</option>
              <option
                v-for="program in programs"
                :key="program.id"
                :value="program.id"
              >
                {{ program.name }}
              </option>
            </select>
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block">
            Type of Hiring
            <select
              disabled
              v-model="type_of_hiring"
              class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
            >
              <option disabled value="" selected>Please select one</option>
              <option value="attrition">Attrition</option>
              <option value="growth">Growth</option>
              <option value="attrition and growth">Attrition and Growth</option>
            </select>
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block">
            External Target
            <input
              disabled
              type="number"
              v-model="external_target"
              name="external_target"
              class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
              @change="syncTotalTarget"
            />
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block">
            Internal Target
            <input
              disabled
              type="number"
              class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
              v-model="internal_target"
              @change="syncTotalTarget"
            />
          </label>
        </div>
      </div>
      <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block">
            Total Target
            <input
              type="number"
              v-model="total_target"
              disabled
              class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
            />
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block"
            >Original Start Date
            <input
              disabled
              type="date"
              v-model="original_start_date"
              class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
            />
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block">
            Weeks Start
            <select
              disabled
              v-model="date_selected"
              class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
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
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block">
            Notice Days
            <input
              type="number"
              v-model="notice_days"
              disabled
              class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
              @change="syncNoticeWeeks"
            />
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block">
            Notice Weeks
            <input
              type="text"
              v-model="notice_weeks"
              disabled
              class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
            />
          </label>
        </div>
      </div>
      <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block"
            >Cancelled Date
            <input
              type="date"
              v-model="cancelled_date"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              @change="syncNoticeDays"
            />
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block"
            >Category
            <select
              required
              v-model="category"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            >
              <option disabled value="" selected>Please select one</option>
              <option value="placeholder">Placeholder</option>
              <option value="confirmed">Confirmed</option>
            </select>
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block"
            >Within SLA?
            <select
              required
              v-model="within_sla"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            >
              <option disabled value="" selected>Please select one</option>
              <option value="Within SLA">Within SLA</option>
              <option value="Outside SLA-Cancellation">
                Outside SLA-Cancellation
              </option>
              <option value="Outside SLA-Change in Demand">
                Outside SLA-Change in Demand
              </option>
              <option value="Outside SLA-Change in Start Date">
                Outside SLA-Change in Start Date
              </option>
              <option value="Outside SLA-Change in Profile">
                Outside SLA-Change in Profile
              </option>
              <option value="Outside SLA-Change in Process/Assessments">
                Outside SLA-Change in Process/Assessments
              </option>
              <option value="OV Support">OV Support</option>
            </select>
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block"
            >Agreed Start Date
            <input
              type="date"
              v-model="agreed_start_date"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              @change="syncNoticeDays"
            />
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block"
            >Approved by
            <select
              required
              v-model="approved_by"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            >
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
      </div>
      <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block"
            >Pipeline Offered
            <input
              type="number"
              v-model="pipeline_offered"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            />
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block"
            >Pipeline Utilized
            <input
              type="number"
              v-model="pipeline_utilized"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            />
          </label>
        </div>
        <div class="w-full mt-1 md:w-3/5 md:mt-0">
          <div class="py-6 flex space-x-4">
            <label class="block font-bold">Requested By:</label>
            <label
              class="block"
              v-for="requester in [
                'Talent Acquisition',
                'Workforce',
                'Training',
                'Client',
                'Operation',
              ]"
              :key="requester"
            >
              <input
                type="checkbox"
                v-model="cancelled_by"
                :value="requester"
                v-bind:checked="cancelled_by.includes(requester)"
              />
              {{ requester }}
            </label>
          </div>
        </div>
      </div>
      <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
        <div
          class="w-full mt-1 md:w-1/5 md:mt-0"
          v-if="cancelled_by.includes('Talent Acquisition')"
        >
          <label class="block"
            >Talent Acquisition
            <input
              type="text"
              v-model="ta"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            />
          </label>
        </div>
        <div
          class="w-full mt-1 md:w-1/5 md:mt-0"
          v-if="cancelled_by.includes('Workforce')"
        >
          <label class="block"
            >Workforce
            <input
              type="text"
              v-model="wf"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            />
          </label>
        </div>
        <div
          class="w-full mt-1 md:w-1/5 md:mt-0"
          v-if="cancelled_by.includes('Training')"
        >
          <label class="block"
            >Training
            <input
              type="text"
              v-model="tr"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            />
          </label>
        </div>
        <div
          class="w-full mt-1 md:w-1/5 md:mt-0"
          v-if="cancelled_by.includes('Client')"
        >
          <label class="block"
            >Client
            <input
              type="text"
              v-model="cl"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            />
          </label>
        </div>
        <div
          class="w-full mt-1 md:w-1/5 md:mt-0"
          v-if="cancelled_by.includes('Operation')"
        >
          <label class="block"
            >Operation
            <input
              type="text"
              v-model="op"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            />
          </label>
        </div>
      </div>
      <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
        <div class="w-full mt-1 md:w-5/5 md:mt-0">
          <label class="block"
            >Reason for Cancellation<textarea
              required
              type="text"
              v-model="remarks"
              class="block w-full h-15 bg-white border rounded-lg"
            />
          </label>
        </div>
      </div>
      <div class="flex justify-center py-4">
        <button
          type="submit"
          class="self-center px-4 py-1 font-bold text-white bg-orange-500 rounded hover:bg-gray-600"
        >
          <i class="fa fa-save"></i> Cancel
        </button>
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
      date_selected: "",
      type_of_hiring: "",
      external_target: "",
      internal_target: "",
      total_target: 0,
      original_start_date: "",
      cancelled_date: "",
      agreed_start_date: "",
      remarks: "",
      pipeline_offered: 0,
      pipeline_utilized: 0,
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
      showModal: false,
      databaseValue: "",
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
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/sites7", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.sites = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching sites");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getPrograms() {
      console.log(this.programs_selected);
      try {
        const token = this.$store.state.token;
        const headers = {
          Authorization: `Bearer ${token}`,
        };

        const response = await axios.get("http://127.0.0.1:8000/api/programs7", {
          headers,
        });

        if (response.status === 200) {
          this.programs = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching programs");
        }
      } catch (error) {
        console.log(error);
      }
    },

    async getTransaction() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/transaction/" + this.$route.params.id,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        this.classes = response.data.classes;
        console.log(response.data.classes);
      } catch (error) {
        console.log(error);
      }
    },
    async getDateRange() {
      console.log(this.date_selected);
      try {
        const token = this.$store.state.token;
        const headers = {
          Authorization: `Bearer ${token}`,
        };

        const response = await axios.get(
          "http://127.0.0.1:8000/api/daterange",
          { headers }
        );

        if (response.status === 200) {
          this.daterange = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching date range");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getClasses() {
      try {
        const token = this.$store.state.token;
        const headers = {
          Authorization: `Bearer ${token}`,
        };

        const response = await axios.get(
          `http://127.0.0.1:8000/api/classes/${this.$route.params.id}`,
          { headers }
        );

        if (response.status === 200) {
          const data = response.data;
          const classObj = data.class;
          const nestedCancelledByArray = JSON.parse(classObj.requested_by);

          // Ensure cancelledByArray is always an array
          let cancelledByArray = Array.isArray(nestedCancelledByArray)
            ? nestedCancelledByArray.flat()
            : [nestedCancelledByArray];

          console.log("Parsed requested_by array:", cancelledByArray);

          // Assign the concatenated string to requested_by property
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
          this.ta = classObj.ta;
          this.wf = classObj.wf;
          this.tr = classObj.tr;
          this.cl = classObj.cl;
          this.op = classObj.op;
          this.cancelled_by = cancelledByArray;

          this.wave_no = classObj.wave_no;

          console.log(classObj);
        } else {
          console.log("Error fetching classes");
        }
      } catch (error) {
        console.log(error);
      }
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
        pipeline_utilized: this.pipeline_utilized,
        pipeline_offered: this.pipeline_offered,
        ta: this.ta,
        wf: this.wf,
        tr: this.tr,
        cl: this.cl,
        op: this.op,
      };

      const token = this.$store.state.token;

      axios
        .put(
          "http://127.0.0.1:8000/api/classes/cancel/" + this.$route.params.id,
          formData,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        )
        .then((response) => {
          console.log(response.data);
          this.site_id = "";
          this.program_id = "";
          this.date_range_id = "";
          this.approved_status = "";
          this.approved_by = "";
          this.remarks = "";
          this.cancelled_by = "";
          this.cancelled_date = "";
          this.pipeline_utilized = "";
          this.pipeline_offered = "";
          this.ta = "";
          this.wf = "";
          this.tr = "";
          this.cl = "";
          this.op = "";
          this.$router.push("/capfileguatemala", () => {
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
