<!-- eslint-disable vue/require-v-for-key -->
<template>
  <header class="w-full bg-white">
    <div class="items-center w-full">
      <h2 class="text-xl font-bold text-center">ADD CLASS</h2>
    </div>
  </header>
  <div v-if="loading" class="loader">
    <div aria-label="Loading..." role="status" class="loader">
      <svg class="icon" viewBox="0 0 256 256">
        <line
          x1="128"
          y1="32"
          x2="128"
          y2="64"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="195.9"
          y1="60.1"
          x2="173.3"
          y2="82.7"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="224"
          y1="128"
          x2="192"
          y2="128"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="195.9"
          y1="195.9"
          x2="173.3"
          y2="173.3"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="128"
          y1="224"
          x2="128"
          y2="192"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="60.1"
          y1="195.9"
          x2="82.7"
          y2="173.3"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="32"
          y1="128"
          x2="64"
          y2="128"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="60.1"
          y1="60.1"
          x2="82.7"
          y2="82.7"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
      </svg>
      <span class="loading-text">Loading...</span>
    </div>
  </div>
  <div class="px-12 py-8 font-serifs">
    <form @submit.prevent="addClass">
      <div class="border-red-500 border-2">
        <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block">
              Site
              <select
                disabled
                v-model="sites_selected"
                class="w-full px-4 py-2 bg-white border rounded-lg"
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
                class="w-full px-4 py-2 bg-white border rounded-lg"
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
                required
                v-model="type_of_hiring"
                class="w-full px-4 py-2 bg-white border rounded-lg"
              >
                <option disabled value="" selected>Please select one</option>
                <option value="attrition">Attrition</option>
                <option value="growth">Growth</option>
                <option value="attrition and growth">
                  Attrition and Growth
                </option>
              </select>
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block">
              External Target
              <input
                required
                type="number"
                v-model="external_target"
                class="w-full px-4 py-2 bg-white border rounded-lg"
                @change="syncTotalTarget"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block">
              Internal Target
              <input
                required
                type="number"
                v-model="internal_target"
                class="w-full px-4 py-2 bg-white border rounded-lg"
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
                readonly
                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Original Start Date
              <input
                required
                type="date"
                v-model="original_start_date"
                class="w-full px-4 py-2 bg-white border rounded-lg"
                @change="syncNoticeDays"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >WFM Date Requested
              <input
                required
                type="date"
                v-model="wfm_date_requested"
                class="w-full px-4 py-2 bg-white border rounded-lg"
                @change="syncNoticeDays"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block">
              Notice Days
              <input
                type="text"
                v-model="notice_days"
                readonly
                @change="syncNoticeWeeks"
                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block">
              Notice Weeks
              <input
                type="text"
                v-model="notice_weeks"
                readonly
                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
        </div>
        <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block">
              Weeks Start
              <select
                disabled
                v-model="date_selected"
                class="w-full px-4 py-2 bg-white border rounded-lg"
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
            <label class="block"
              >With ERF?
              <select
                required
                v-model="with_erf"
                class="w-full px-4 py-2 bg-white border rounded-lg"
              >
                <option disabled value="" selected>Please select one</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
              </select>
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block" v-if="with_erf === 'yes'">
              ERF Number
              <input
                type="text"
                v-model="erf_number"
                class="w-full px-4 py-2 bg-white border rounded-lg"
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
                <option value="Within SLA - Decrease in Demand (Cancellation)">
                  Within SLA - Decrease in Demand (Cancellation)
                </option>
                <option value="Within SLA - Increase in Demand">
                  Within SLA - Increase in Demand
                </option>
                <option value="Outside SLA - Decrease in Demand (Cancellation)">
                  Outside SLA - Decrease in Demand (Cancellation)
                </option>
                <option value="Outside SLA-Cancellation">
                  Outside SLA-Cancellation
                </option>
                <option value="Outside SLA - Increase in Demand">
                  Outside SLA - Increase in Demand
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
                <option value="Outside SLA-New class added">
                  Outside SLA-New class added
                </option>
                <option value="OV Support">OV Support</option>
              </select>
            </label>
          </div>
        </div>
        <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
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
      </div>
      <!-- SR -->
      <div v-if="category === 'confirmed'" class="border-red-500 border-2">
        <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Hire Date
              <input
                type="date"
                v-model="hire_date"

                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Start Date
              <input
                type="date"
                v-model="start_date"

                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >End Date
              <input
                type="date"
                v-model="end_date"

                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Team
              <input
                type="text"
                v-model="team"
                readonly
                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Immediate Supervisor HRID
              <input
                type="text"
                v-model="immediate_supervisor_hrid"
                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
        </div>
        <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Immediate Supervisor Name
              <input
                type="text"
                v-model="immediate_supervisor_name"
                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Work Setup
              <select

                v-model="work_setup"
                class="w-full px-4 py-2 bg-white border rounded-lg"
              >
                <option disabled value="" selected>Please select one</option>
                <option value="WAS">WAS</option>
                <option value="WAH">WAH</option>
              </select>
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Offer Target
              <input
                type="text"
                v-model="offer_target"

                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Offer Category Doc
              <input
                type="text"
                v-model="offer_category_doc"

                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Required Program Specific
              <input
                type="text"
                v-model="required_program_specific"

                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
        </div>
        <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Program Specific Id
              <input
                type="text"
                v-model="program_specific_id"

                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Basic Pay Training
              <input
                type="text"
                v-model="basic_pay_training"
                readonly
                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>

          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Basic Pay Production
              <input
                type="text"
                v-model="basic_pay_production"
                readonly
                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Night Differential Training
              <input
                type="text"
                v-model="night_differential_training"
                readonly
                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Night Differential Production
              <input
                type="text"
                v-model="night_differential_production"
                readonly
                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
        </div>
        <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Bonus Training
              <input
                type="text"
                v-model="bonus_training"
                readonly
                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
          <div class="w-full mt-1 md:w-1/5 md:mt-0">
            <label class="block"
              >Bonus Production
              <input
                type="text"
                v-model="bonus_production"
                readonly
                class="w-full px-4 py-2 bg-white border rounded-lg"
              />
            </label>
          </div>
        </div>
      </div>

      <!-- Remarks -->
      <div class="border-red-500 border-2">
        <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
          <div class="w-full mt-1 md:w-5/5 md:mt-0">
            <label class="block"
              >Remarks<textarea
                required
                type="text"
                v-model="remarks"
                class="block w-full h-15 bg-white border rounded-lg"
              />
            </label>
          </div>
        </div>
      </div>

      <div class="flex justify-center py-4">
        <button
          type="submit"
          class="self-center px-4 py-1 font-bold text-white bg-orange-500 rounded hover:bg-gray-600"
        >
          <i class="fa fa-save"></i> Save
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
      //=sites_selected: "",
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
      within_sla: "",
      approved_by: "",
      loading: false,
      sites: [],
      daterange: [],
      programs: [],
      start_date: "",
      end_date: "",
      hire_date: "",
      immediate_supervisor_hrid: "",
      immediate_supervisor_name: "",
      work_setup: "",
      offer_target: "",
      offer_category_doc: "",
      required_program_specific: "",
      program_specific_id: "",
      basic_pay_training: "",
      basic_pay_production: "",
      night_differential_training: "",
      night_differential_production: "",
      bonus_training: "",
      bonus_production: "",
      siteNames: {},
      programNames: {},
      dateNames: {},
    };
  },
  watch: {
    category(newValue) {
      if (newValue !== "confirmed") {
        this.team = "";
        this.immediate_supervisor_hrid = "";
        this.immediate_supervisor_name = "";
        this.work_setup = "";
        this.offer_target = "";
        this.offer_category_doc = "";
        this.required_program_specific = "";
        this.program_specific_id = "";
        this.start_date = "";
        this.end_date = "";
        this.hire_date = "";
      }
    },
  },
  computed: {
    team() {
      const siteNamesList = this.siteNames[this.sites_selected] || "";
      const programNamesList = this.programNames[this.programs_selected] || "";
      const dateName = this.dateNames[this.date_selected] || "";
      return `${siteNamesList} ${programNamesList} WS ${dateName}`;
    },
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
    this.getSites();
    this.getPrograms();
    this.getDateRange();
    this.getPayrate();
  },
  methods: {
    async getPayrate() {
      try {
        const token = this.$store.state.token;
        const headers = {
          Authorization: `Bearer ${token}`,
        };

        const response = await axios.get(
          `http://10.109.2.112:8081/api/get_payrate/${this.$route.query.program}`,
          { headers }
        );

        if (response.status === 200) {
          const payRates = response.data.payRates;

          if (payRates.length > 0) {
            const classObj = payRates[0]; // Access the first object in the array

            // Bind the retrieved data to the component's data properties
            this.basic_pay_production = classObj.BasicPayProduction;
            this.basic_pay_training = classObj.BasicPayTraining;
            this.night_differential_training = classObj.NightDifferentialTraining;
            this.night_differential_production = classObj.NightDifferentialProduction;
            this.bonus_training = classObj.BonusTraining;
            this.bonus_production = classObj.BonusProduction;
          } else {
            console.log("No pay rates found for the given LOB ID");
          }
        } else {
          console.log("Error fetching pay rates");
        }
      } catch (error) {
        console.log("Error:", error);
      }
    },
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
        const response = await axios.get("http://10.109.2.112:8081/api/sites", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.sites = response.data.data;

          this.siteNames = this.sites.reduce((map, site) => {
            map[site.id] = site.name;
            return map;
          }, {});
        } else {
          console.log("Error fetching sites");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getPrograms() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://10.109.2.112:8081/api/programs", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.programs = response.data.data;

          this.programNames = this.programs.reduce((map, program) => {
            map[program.id] = program.name;
            return map;
          }, {});
        } else {
          console.log("Error fetching programs");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getDateRange() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://10.109.2.112:8081/api/daterange",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.daterange = response.data.data;

          // Create a mapping of date IDs to formatted week_start names
          this.dateNames = this.daterange.reduce((map, date) => {
            // Parse the date string into a Date object
            const weekStartDate = new Date(date.week_start);

            // Format the date to 'MMM DD' (e.g., 'Feb 02')
            const formattedDate = new Intl.DateTimeFormat("en-US", {
              month: "short",
              day: "2-digit",
            }).format(weekStartDate);

            // Add to the map
            map[date.id] = formattedDate;
            return map;
          }, {});
        } else {
          console.log("Error fetching date range");
        }
      } catch (error) {
        console.log(error);
      }
    },

    addClass() {
      this.loading = true;
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
        date_range_id: this.date_selected,
        approved_status: "pending",
        approved_by: this.approved_by,
        status: "Active",
        created_by: this.$store.state.user_id,
        start_date: this.start_date,
        end_date: this.end_date,
        hire_date: this.hire_date,
        team: this.team,
        immediate_supervisor_hrid: this.immediate_supervisor_hrid,
        immediate_supervisor_name: this.immediate_supervisor_name,
        work_setup: this.work_setup,
        offer_target: this.offer_target,
        offer_category_doc: this.offer_category_doc,
        required_program_specific: this.required_program_specific,
        program_specific_id: this.program_specific_id,
        basic_pay_training: this.basic_pay_training,
        basic_pay_production: this.basic_pay_production,
        night_differential_training: this.night_differential_training,
        night_differential_production: this.night_differential_production,
        bonus_training: this.bonus_training,
        bonus_production: this.bonus_production,
      };
      const token = this.$store.state.token;
      const headers = {
        Authorization: `Bearer ${token}`,
      };
      axios
        .post("http://10.109.2.112:8081/api/classes/", formData, { headers })
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
          this.date_range_id = "";
          this.approved_status = "";
          this.created_by = "";
          this.approved_by = "";
          this.two_dimensional_id = "";
          this.start_date = "";
          this.end_date = "";
          this.hire_date = "";
          this.team = "";
          this.immediate_supervisor_hrid = "";
          this.immediate_supervisor_name = "";
          this.work_setup = "";
          this.offer_target = "";
          this.offer_category_doc = "";
          this.required_program_specific = "";
          this.program_specific_id = "";
          this.basic_pay_training = "";
          this.basic_pay_production = "";
          this.night_differential_training = "";
          this.night_differential_production = "";
          this.bonus_training = "";
          this.bonus_production = "";
          this.$router.push("/capfile", () => {
            location.reload();
          });
        })
        .catch((error) => {
          console.log(error.response.data);
        })
        .finally(() => {
          this.loading = false;
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
<style scoped>
/* Your loader styles here */
.loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* Ensure the loader is on top of other elements */
}

.loader-content {
  /* Style your loader content (SVG, text, etc.) */
  display: flex;
  align-items: center;
}

.icon {
  /* Style your SVG icon */
  height: 3rem; /* Adjust the size as needed */
  width: 3rem; /* Adjust the size as needed */
  animation: spin 1s linear infinite;
  stroke: rgba(107, 114, 128, 1);
}

.loading-text {
  /* Style your loading text */
  font-size: 1.5rem; /* Adjust the size as needed */
  line-height: 2rem; /* Adjust the size as needed */
  font-weight: 500;
  color: rgba(107, 114, 128, 1);
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
