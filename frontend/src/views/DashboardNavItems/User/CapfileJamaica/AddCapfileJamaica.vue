<!-- eslint-disable vue/require-v-for-key -->
<template>
  <header class="w-full bg-white">
    <div class="items-center w-full">
      <h2 class="text-xl font-bold text-center">ADD CLASS</h2>
    </div>
  </header>
  <div class="px-12 py-8 font-serifs">
    <form @submit.prevent="addClass">
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
              <option value="attrition and growth">Attrition and Growth</option>
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
        <!--
        <div class="w-full mt-1 md:w-1/5 md:mt-0 relative border rounded-lg">
          <input
            type="number"
            v-model="notice_days"
            readonly
            class="w-full px-4 py-2 bg-white border-2 pt-6"
            @change="syncNoticeWeeks"
          />
          <label
            class="absolute left-3 top-0 bg-white px-1"
            style="transform: translateY(-50%)"
            >Notice Days</label
          >
        </div> -->

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
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://10.109.2.112:8081/api/sites5", {
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

        const response = await axios.get(
          "http://10.109.2.112:8081/api/programs5",
          {
            headers,
          }
        );

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

    async getDateRange() {
      console.log(this.date_selected);
      try {
        const token = this.$store.state.token;
        const headers = {
          Authorization: `Bearer ${token}`,
        };

        const response = await axios.get(
          "http://10.109.2.112:8081/api/daterange",
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
        date_range_id: this.date_selected,
        approved_status: "pending",
        approved_by: this.approved_by,
        status: "Active",
        created_by: this.$store.state.user_id,
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
          this.$router.push("/capfilejamaica", () => {
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
