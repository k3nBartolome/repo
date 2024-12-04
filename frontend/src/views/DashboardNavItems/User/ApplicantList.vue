<template>
  <div class="py-0">
    <header class="p-4 py-0 bg-white">
      <div class="max-w-screen-xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900">Applicant Tool SRv2</h2>
      </div>
    </header>
    <!--   <div class="px-1 py-0 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8"> -->
    <div
      class="fixed inset-0 z-50 flex items-center justify-center modal"
      v-if="showModalWorkExp"
    >
      <div class="absolute inset-0 modal-overlay"></div>
      <div class="p-8 bg-white rounded-lg shadow-lg modal-content">
        <!-- Content of your modal -->
        <header class="px-4 py-2 border-b-2 border-gray-200">
          <h2 class="text-lg font-semibold text-gray-800">Work Experience</h2>
        </header>
        <button
          @click="showModalWorkExp = false"
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
        <div class="px-4 py-2 overflow-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr>
                <th class="px-4 py-2 border">Immediate Supervisor HRID</th>
                <th class="px-4 py-2 border">Immediate Supervisor Name</th>
                <th class="px-4 py-2 border">Work Setup</th>
                <th class="px-4 py-2 border">Company Name</th>
                <th class="px-4 py-2 border">Work Exp Type</th>
                <th class="px-4 py-2 border">Last Working Date</th>
                <th class="px-4 py-2 border">Adapter Industry</th>
                <th class="px-4 py-2 border">Position</th>
                <th class="px-4 py-2 border">Salary</th>
                <th class="px-4 py-2 border">Work Tenure Months</th>
                <th class="px-4 py-2 border">Reason for Leaving</th>
                <th class="px-4 py-2 border">Account Type</th>
                <th class="px-4 py-2 border">Experience Type</th>
                <th class="px-4 py-2 border">Total Work Exp</th>
                <th class="px-4 py-2 border">Total Work Exp BPO</th>
                <th class="px-4 py-2 border">Total Work Exp Non-BPO</th>
                <th class="px-4 py-2 border">Segment</th>
                <th class="px-4 py-2 border">Work Type</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, index) in formattedData" :key="index">
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(
                        item, itemIndex
                      ) in row.ImmediateSupervisorHRID.split(';')"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(
                        item, itemIndex
                      ) in row.ImmediateSupervisorName.split(';')"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.WorkSetup.split(';')"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.CompanyName.split(';')"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.WorkExpType.split(';')"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.LastWorkingDate.split(
                        ';'
                      )"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.AdapterIndustry.split(
                        ';'
                      )"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.Position.split(';')"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.Salary.split(';')"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.WorkTenureMonths.split(
                        ';'
                      )"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.ReasonForLeaving.split(
                        ';'
                      )"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.AccountType.split(';')"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.ExperienceType.split(';')"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.TotalWorkExp.split(';')"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.TotalWorkExpBPO.split(
                        ';'
                      )"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.TotalWorkExpNonBPO.split(
                        ';'
                      )"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(item, itemIndex) in row.Segment.split(';')"
                      :key="itemIndex"
                    >
                      {{ item }}
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-2 border">
                  <ul class="p-0 list-none">
                    <li
                      v-for="(workType, workTypeIndex) in row.WorkType.split(
                        ';'
                      )"
                      :key="workTypeIndex"
                    >
                      {{ workType }}
                    </li>
                  </ul>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!--   </div> -->

    <div class="p-4 bg-gray-100">
      <div class="mb-4 md:flex md:space-x-2 md:items-center">
        <div class="relative w-full md:w-1/4">
          <input
            v-model="filterLastName"
            placeholder="Filter by Last Name"
            class="w-full p-2 border rounded-lg"
            @input="validateLastName"
          />
          <div
            v-if="filterLastNameError"
            class="absolute left-0 text-sm text-red-500 top-full"
          >
            {{ filterLastNameError }}
          </div>
        </div>
        <div class="w-full md:w-1/4">
          <input
            v-model="filterFirstName"
            placeholder="Filter by First Name"
            class="w-full p-2 border rounded-lg"
          />
        </div>
        <div class="w-full md:w-1/4">
          <select
            v-model="filterRegion"
            placeholder="Filter by Region"
            class="w-full p-2 border rounded-lg"
            @change="getSites(filterRegion)"
          >
            <option disabled value="" selected>Please select one</option>
            <option value="ALL">All</option>
            <option value="CLARK">CLARK</option>
            <option value="DAVAO">DAVAO</option>
            <option value="L2">L2</option>
            <option value="QC">QC</option>
          </select>
        </div>
        <div class="w-full md:w-1/4">
          <select
            v-model="filterSite"
            placeholder="Filter by Site"
            class="w-full p-2 border rounded-lg"
          >
            <option disabled value="" selected>Please select one</option>
            <option v-for="site in sites" :key="site.Id" :value="site.Name">
              {{ site.Name }}
            </option>
          </select>
        </div>

        <div class="w-full md:w-1/4">
          <input
            v-model="filterStartDate"
            type="date"
            placeholder="Start"
            class="w-full p-2 border rounded-lg"
            @input="updateFilterStartDate"
          />
        </div>
        <div class="w-full md:w-1/4">
          <input
            v-model="filterEndDate"
            type="date"
            placeholder="End by Site"
            class="w-full p-2 border rounded-lg"
            @input="updateFilterEndDate"
          />
        </div>
        <div class="relative w-full md:w-1/4">
          <input
            v-model="filterContact"
            placeholder="Filter by Mobile No."
            class="w-full p-2 border rounded-lg"
            @input="validateContact"
          />
          <div
            v-if="filterContactError"
            class="absolute left-0 text-sm text-red-500 top-full"
          >
            {{ filterContactError }}
          </div>
        </div>
        <div class="w-full md:w-1/4">
          <button
            @click="fetchData"
            class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg"
          >
            Filter
          </button>
        </div>
        <div class="w-full md:w-1/4">
          <button
            @click="exportToExcel"
            class="w-full px-4 py-2 text-white bg-blue-500 rounded-lg"
          >
            Export
          </button>
        </div>
      </div>
      <div
        v-if="filterLoading || exportLoading"
        class="font-bold text-center text-blue-500"
      >
        {{ filterLoading ? "Rendering..." : "Exporting..." }}
      </div>
      <DataTable
        :data="perx"
        :columns="columns"
        class="table divide-y divide-gray-200 table-auto table-striped"
        :options="{
          responsive: false,
          autoWidth: false,
          pageLength: 10,
          lengthChange: true,
          ordering: true,
          scrollX: true,
          dom: 'rtlip',
          language: {
            search: 'Search',
            zeroRecords: 'No data available',
            info: 'Showing from _START_ to _END_ of _TOTAL_ records',
            infoFiltered: '(Filtered from MAX records)',
            paginate: {
              first: 'First',
              previous: 'Prev',
              next: 'Next',
              last: 'Last',
            },
          },
        }"
      >
        <thead class="truncate">
          <tr>
            <!-- Add your table headers here -->
          </tr>
        </thead>
        <tbody class="truncate">
          <tr v-for="item in perx" :key="item.id">
            <!-- Add your table row data here -->
          </tr>
        </tbody>
      </DataTable>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";
import "datatables.net-buttons-bs5/css/buttons.bootstrap5.css";
import "datatables.net-responsive-bs5/css/responsive.bootstrap5.css";

import DataTable from "datatables.net-vue3";
import DataTableLib from "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";
import "datatables.net-buttons-bs5";
import "datatables.net-buttons-bs5/css/buttons.bootstrap5.css";
import "datatables.net-responsive-bs5";
import "datatables.net-responsive-bs5/css/responsive.bootstrap5.css";
import "bootstrap/dist/js/bootstrap.bundle"; // Make sure to include Bootstrap JavaScript

// Import DataTables extensions
import Buttons from "datatables.net-buttons-bs5";
import ButtonsHtml5 from "datatables.net-buttons/js/buttons.html5";
import print from "datatables.net-buttons/js/buttons.print";
import jszip from "jszip";
// eslint-disable-next-line no-unused-vars
import pdfMake from "pdfmake/build/pdfmake";
// eslint-disable-next-line no-unused-vars
import pdfFonts from "pdfmake/build/vfs_fonts";
import "bootstrap/dist/css/bootstrap.css";

// eslint-disable-next-line no-unused-vars
import { format } from "date-fns";

DataTable.use(DataTableLib);
DataTable.use(jszip);
DataTable.use(Buttons);
DataTable.use(ButtonsHtml5);
DataTable.use(print);

export default {
  components: { DataTable },
  data() {
    return {
      filterLastName: "",
      filterFirstName: "",
      filterSite: "",
      filterStartDate: "",
      filterEndDate: "",
      filterContact: "",
      filterRegion: "",
      filterLastNameError: "",
      filterContactError: "",
      showModalWorkExp: false,
      WorkExpId: null,
      workExpData: {
        ImmediateSupervisorHRID: "",
        ImmediateSupervisorName: "",
        WorkSetup: "",
        CompanyName: "",
        WorkExpType: "",
        LastWorkingDate: "",
        AdapterIndustry: "",
        Position: "",
        Salary: "",
        WorkTenureMonths: "",
        ReasonForLeaving: "",
        AccountType: "",
        ExperienceType: "",
        WorkType: "",
        TotalWorkExp: "",
        TotalWorkExpBPO: "",
        TotalWorkExpNonBPO: "",
        Segment: "",
      },
      perx: [],
      applicants: {},
      sites: [],
      columns: [
        { data: "SR_ID", title: "SR_ID" },
        {
          data: null,
          title: "Actions",
          orderable: false,
          searchable: false,
          render: function (data) {
            const isUser = this.isUser;
            return `
      ${
        isUser
          ? `<button class="w-auto text-xs btn btn-primary" onclick="window.vm.openModalForWorkExp(${data.SR_ID})">Work Exp</button>`
          : ""
      }
    `;
          }.bind(this),
        },
        { data: "ApplicationDate", title: "ApplicationDate" },
        { data: "FirstName", title: "FirstName" },
        { data: "LastName", title: "LastName" },
        { data: "MiddleName", title: "MiddleName" },
        { data: "Suffix", title: "Suffix" },
        { data: "Email", title: "Email" },
        { data: "CellphoneNumber", title: "CellphoneNumber" },
        { data: "BirthDate", title: "BirthDate" },
        { data: "CivilStatus", title: "CivilStatus" },
        { data: "MotherFirstName", title: "MotherFirstName" },
        { data: "MotherLastName", title: "MotherLastName" },
        { data: "MotherMiddleName", title: "MotherMiddleName" },
        { data: "C_Country", title: "Country" },
        { data: "C_Region", title: "Region" },
        { data: "C_Province", title: "Province" },
        { data: "C_City", title: "City" },
        { data: "City_Address", title: "City_Address" },
        { data: "ExpectedSalary", title: "ExpectedSalary" },
        { data: "Step", title: "Step" },
        { data: "GeneralSource", title: "GeneralSource" },
        { data: "SpecificSource", title: "SpecificSource" },
        { data: "GeneralStatus", title: "GeneralStatus" },
        { data: "SpecificStatus", title: "SpecificStatus" },
        { data: "PositionApplyingFor", title: "PositionApplyingFor" },
        { data: "SiteApplied", title: "SiteApplied" },
        { data: "KO_Score", title: "KO_Score" },
        { data: "GQ1", title: "GQ1" },
        { data: "GQ2", title: "GQ2" },
        { data: "WQ", title: "WQ" },
        { data: "SVA", title: "SVA" },
        {
          data: "HighestEducationalAttainment",
          title: "HighestEducationalAttainment",
        },
        { data: "SchoolName", title: "SchoolName" },
        { data: "SchoolAddress", title: "SchoolAddress" },
        { data: "Course", title: "Course" },
        { data: "SchoolStartDate", title: "SchoolStartDate" },
        { data: "SchoolEndDate", title: "SchoolEndDate" },
        { data: "GraduationDate", title: "GraduationDate" },
        { data: "ReferralID", title: "ReferralID" },
        { data: "id", title: "id" },
        /*  { data: "ApplicantAppicationsId", title: "ApplicantAppicationsId" },
        { data: "ApplicantId", title: "ApplicantId" }, */
        { data: "Pronunciation", title: "Pronunciation" },
        { data: "Grammar", title: "Grammar" },
        { data: "Fluency", title: "Fluency" },
        { data: "Lexis", title: "Lexis" },
        { data: "CCC", title: "CCC" },
        { data: "Tone", title: "Tone" },
        { data: "PreEmps", title: "PreEmps" },
        { data: "EmpBkgrnd", title: "EmpBkgrnd" },
        { data: "SchedAmenability", title: "SchedAmenability" },
        { data: "EducAttainment", title: "EducAttainment" },
        { data: "HealthCond", title: "HealthCond" },
        { data: "Availability", title: "Availability" },
        { data: "SitePref", title: "SitePref" },
        { data: "CustService", title: "CustService" },
        { data: "Sales", title: "Sales" },
        { data: "StressTol", title: "StressTol" },
        { data: "Integrity", title: "Integrity" },
        { data: "Achievement", title: "Achievement" },
        { data: "ProblemSolving", title: "ProblemSolving" },
        /*  { data: "IntervieweeId", title: "IntervieweeId" },
        { data: "ApplicantApplicationId", title: "ApplicantApplicationId" }, */
        { data: "Interviewer", title: "Interviewer" },
        { data: "LIRemarks", title: "LIRemarks" },
        { data: "RIRemarks", title: "RIRemarks" },
        { data: "BCIRemarks", title: "BCIRemarks" },
        { data: "ProfilingRemarks", title: "ProfilingRemarks" },
        { data: "Class", title: "Class" },
        { data: "OVRemarks", title: "OVRemarks" },
        { data: "OVRecruiter", title: "OVRecruiter" },
        { data: "OVDate", title: "OVDate" },
        /*  { data: "ImmediateSupervisorHRID", title: "ImmediateSupervisorHRID" },
        { data: "ImmediateSupervisorName", title: "ImmediateSupervisorName" },
        { data: "WorkSetup", title: "WorkSetup" },
        { data: "CompanyName", title: "CompanyName" },
        { data: "WorkExpType", title: "WorkExpType" },
        { data: "LastWorkingDate", title: "LastWorkingDate" },
        { data: "AdapterIndustry", title: "AdapterIndustry" },
        { data: "Position", title: "Position" },
        { data: "Salary", title: "Salary" },
        { data: "WorkTenureMonths", title: "WorkTenureMonths" },
        { data: "ReasonForLeaving", title: "ReasonForLeaving" },
        { data: "AccountType", title: "AccountType" },
        { data: "ExperienceType", title: "ExperienceType" },
        { data: "WorkType", title: "WorkType" },
        { data: "TotalWorkExp", title: "TotalWorkExp" },
        { data: "TotalWorkExpBPO", title: "TotalWorkExpBPO" },
        { data: "TotalWorkExpNonBPO", title: "TotalWorkExpNonBPO" },
        { data: "Segment", title: "Segment" } */
      ],
      filterLoading: false,
      exportLoading: false,
    };
  },

  mounted() {
    window.vm = this;
    this.getDates();
    this.getSites();
    this.fetchData();
  },
  computed: {
    formattedData() {
      const maxRows = Math.max(
        ...Object.values(this.workExpData).map((value) =>
          value ? value.split(";").length : 1
        )
      );

      const rows = Array.from({ length: maxRows }).map((_, index) => {
        const row = {};
        for (const key in this.workExpData) {
          const values = this.workExpData[key]
            ? this.workExpData[key].split(";")
            : [];
          row[key] = values[index] || "";
        }
        return row;
      });

      return rows;
    },
    formattedFilterDate() {
      return this.filterDate
        ? new Date(this.filterDate).toLocaleDateString("en-CA")
        : "";
    },
    isUser() {
      const userRole = this.$store.state.role;
      return userRole === "user";
    },
    isRemx() {
      const userRole = this.$store.state.role;
      return userRole === "remx";
    },
    isBudget() {
      const userRole = this.$store.state.role;
      return userRole === "budget";
    },
    isSourcing() {
      const userRole = this.$store.state.role;
      return userRole === "sourcing";
    },
  },

  methods: {
    async openModalForWorkExp(id) {
      console.log("SR_ID:", id);
      this.filterLoading = true;
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          `http://10.109.2.112:8000/api/applicants/${id}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        const data = response.data.applicants;
        this.workExpData.ImmediateSupervisorHRID = data.ImmediateSupervisorHRID;
        this.workExpData.ImmediateSupervisorName = data.ImmediateSupervisorName;
        this.workExpData.WorkSetup = data.WorkSetup;
        this.workExpData.CompanyName = data.CompanyName;
        this.workExpData.WorkExpType = data.WorkExpType;
        this.workExpData.LastWorkingDate = data.LastWorkingDate;
        this.workExpData.AdapterIndustry = data.AdapterIndustry;
        this.workExpData.Position = data.Position;
        this.workExpData.Salary = data.Salary;
        this.workExpData.WorkTenureMonths = data.WorkTenureMonths;
        this.workExpData.ReasonForLeaving = data.ReasonForLeaving;
        this.workExpData.AccountType = data.AccountType;
        this.workExpData.ExperienceType = data.ExperienceType;
        this.workExpData.WorkType = data.WorkType;
        this.workExpData.TotalWorkExp = data.TotalWorkExp;
        this.workExpData.TotalWorkExpBPO = data.TotalWorkExpBPO;
        this.workExpData.TotalWorkExpNonBPO = data.TotalWorkExpNonBPO;
        this.workExpData.Segment = data.Segment;

        console.log(this.workExpData);
        this.showModalWorkExp = true;
      } catch (error) {
        console.error("Error fetching filtered data", error);
      } finally {
        this.filterLoading = false;
      }
    },
    convertToISO(dateStr) {
      console.log("convertToISO called with:", dateStr);

      if (!dateStr) {
        console.error("No date string provided.");
        return "";
      }

      // Split and parse the date
      const [month, day, year] = dateStr.split("/");
      if (!month || !day || !year) {
        console.error(
          "Invalid date format, expected MM/DD/YYYY but got:",
          dateStr
        );
        return "";
      }

      // Ensure month and day are zero-padded
      const formattedMonth = month.padStart(2, "0");
      const formattedDay = day.padStart(2, "0");

      const isoDate = `${year}-${formattedMonth}-${formattedDay}`;
      console.log("Converted ISO date:", isoDate);
      return isoDate;
    },
    convertToMMDDYYYY(dateStr) {
      if (!dateStr) return "";

      const [year, month, day] = dateStr.split("-");
      return `${month}/${day}/${year}`;
    },
    async fetchData() {
      this.filterLoading = true;
      try {
        const token = this.$store.state.token;

        const formatDate = (date) => {
          if (!date) return "";
          const [year, month, day] = date.split("-");
          return `${month}/${day}/${year}`;
        };

        const formattedStartDate = formatDate(this.filterStartDate);
        const formattedEndDate = formatDate(this.filterEndDate);

        if (!formattedStartDate || !formattedEndDate) {
          console.error("Invalid date format");
          return;
        }

        const response = await axios.get(
          "http://10.109.2.112:8000/api/applicants",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
            params: {
              filter_lastname: this.filterLastName,
              filter_firstname: this.filterFirstName,
              filter_site: this.filterSite,
              startDate: formattedStartDate,
              endDate: formattedEndDate,
              filter_contact: this.filterContact,
              filter_region: this.filterRegion,
            },
          }
        );

        this.perx = response.data.applicants;
      } catch (error) {
        console.error("Error fetching filtered data", error);
      } finally {
        this.filterLoading = false;
      }
    },

    async getDates() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://10.109.2.112:8000/api/applicantsDate",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          // Convert dates from MM/DD/YYYY to YYYY-MM-DD
          this.filterStartDate = this.convertToISO(response.data.minDate);
          this.filterEndDate = this.convertToISO(response.data.maxDate);

          console.log(this.filterStartDate); // Should be in YYYY-MM-DD format
          console.log(this.filterEndDate); // Should be in YYYY-MM-DD format
        } else {
          console.log("Error fetching Date");
        }
      } catch (error) {
        console.log(error);
      }
    },
    updateFilterStartDate(event) {
      this.filterStartDate = event.target.value;
    },

    updateFilterEndDate(event) {
      this.filterEndDate = event.target.value;
    },

    formatDateForInput(date) {
      const formattedDate = new Date(date).toISOString().split("T")[0];
      return formattedDate;
    },

    validateLastName() {
      this.filterLastNameError = "";
      if (this.filterLastName && this.filterLastName.length < 2) {
        this.filterLastNameError =
          "Last Name must be at least 2 characters long.";
      }
    },
    validateContact() {
      this.filterContactError = "";
      if (this.filterContact && this.filterContact.length < 4) {
        this.filterContactError =
          "Mobile No must be at least 4 characters long.";
      }
    },
    async getSites(filterRegion = "") {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://10.109.2.112:8000/api/perx_sitev2",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
            params: {
              filter_region: filterRegion === "ALL" ? "" : filterRegion,
            },
          }
        );

        if (response.status === 200) {
          this.sites = response.data.sites;
          console.log(response.data.sites);
        } else {
          console.log("Error fetching sites");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async exportToExcel() {
      this.exportLoading = true;
      try {
        const token = this.$store.state.token;

        // Convert dates from YYYY-MM-DD to MM/DD/YYYY format
        const formatDate = (date) => {
          if (!date) return "";
          const [year, month, day] = date.split("-");
          return `${month}/${day}/${year}`;
        };

        const formattedStartDate = formatDate(this.filterStartDate);
        const formattedEndDate = formatDate(this.filterEndDate);

        if (!formattedStartDate || !formattedEndDate) {
          console.error("Invalid date format");
          return;
        }

        const response = await axios.get(
          "http://10.109.2.112:8000/api/applicantsExport",
          {
            params: {
              filter_lastname: this.filterLastName,
              filter_firstname: this.filterFirstName,
              filter_site: this.filterSite,
              startDate: formattedStartDate,
              endDate: formattedEndDate,
              filter_contact: this.filterContact,
              filter_region:
                this.filterRegion === "ALL" ? "" : this.filterRegion,
            },
            headers: {
              Authorization: `Bearer ${token}`,
            },
            responseType: "blob",
          }
        );

        const blob = new Blob([response.data], {
          type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        });
        const url = window.URL.createObjectURL(blob);

        const link = document.createElement("a");
        link.href = url;
        link.download = "perx_data.xlsx";
        link.click();
      } catch (error) {
        console.error("Error exporting filtered data to Excel", error);
      } finally {
        this.exportLoading = false;
      }
    },
  },
};
</script>
<style scoped>
.modal-content {
  width: 50vw;
  height: 50vh;
  max-width: 80vw;
  max-height: 80vh;
}
</style>
