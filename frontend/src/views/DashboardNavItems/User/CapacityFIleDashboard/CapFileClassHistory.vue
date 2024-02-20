<template>
  <div class="py-0">
    <div class="pl-8 pr-8">
      <div class="scroll">
        <div class="w-2/3 mx-auto datatable-container">
          <DataTable
            :data="classes"
            :columns="columns"
            class="table divide-y divide-gray-200 table-auto table-striped"
            :options="{
              responsive: false,
              autoWidth: false,
              pageLength: 10,
              lengthChange: true,
              ordering: true,
              scrollX: true,
              dom: 'frtip',
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
                <!-- ...existing code... -->
              </tr>
            </thead>
          </DataTable>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import DataTable from "datatables.net-vue3";
import DataTableLib from "datatables.net-bs5";
// eslint-disable-next-line no-unused-vars
import Buttons from "datatables.net-buttons-bs5";
import ButtonsHtml5 from "datatables.net-buttons/js/buttons.html5";
// eslint-disable-next-line no-unused-vars
import print from "datatables.net-buttons/js/buttons.print";
//import pdfmake from "pdfmake";
// eslint-disable-next-line no-unused-vars
import pdfFonts from "pdfmake/build/vfs_fonts";
import "datatables.net-responsive-bs5";
import jszip from "jszip";
// eslint-disable-next-line no-unused-vars
import "bootstrap/dist/css/bootstrap.css";

DataTable.use(DataTableLib);
DataTable.use(jszip);
DataTable.use(ButtonsHtml5);

export default {
  components: { DataTable },
  data() {
    return {
      classes: [],
      grandTotal: [],
      grandTotal2: [],
      columns: [
        { data: "id", title: "ID" },
        { data: "site.country", title: "Country" },
        { data: "site.region", title: "Region" },
        { data: "site.name", title: "Site" },
        { data: "program.name", title: "Program" },
        { data: "date_range.date_range", title: "Hiring Week" },
        { data: "external_target", title: "External Target" },
        { data: "internal_target", title: "Internal Target" },
        { data: "total_target", title: "Total Target" },
        { data: "within_sla", title: "Within SLA" },
        { data: "condition", title: "Condition" },
        { data: "original_start_date", title: "Original Start Date" },
        { data: "changes", title: "Changes" },
        { data: "agreed_start_date", title: "Agreed Start Date" },
        { data: "wfm_date_requested", title: "WFM Date Requested" },
        { data: "notice_weeks", title: "Notice Weeks" },
        { data: "notice_days", title: "Notice Days" },
        { data: "pipeline_utilized", title: "Pipeline Utilized" },
        { data: "remarks", title: "Remarks" },
        { data: "status", title: "Status" },
        { data: "category", title: "Category" },
        { data: "type_of_hiring", title: "Type of Hiring" },
        { data: "with_erf", title: "With ERF" },
        { data: "erf_number", title: "ERF Number" },
        { data: "wave_no", title: "Wave No" },
        { data: "ta", title: "TA" },
        { data: "wf", title: "WF" },
        { data: "tr", title: "TR" },
        { data: "cl", title: "CL" },
        { data: "op", title: "OP" },
        { data: "approved_by", title: "Approved By" },
        { data: "cancelled_by", title: "Cancelled By" },
        { data: "requested_by", title: "Requested By" },
        { data: "created_by", title: "Created By" },
        { data: "updated_by", title: "Updated By" },
        { data: "approved_date", title: "Approved Date" },
        { data: "cancelled_date", title: "Cancelled Date" },
        { data: "created_at", title: "Created Date" },
        { data: "updated_at", title: "Updated Date" },
      ],
    };
  },
  computed: {},
  mounted() {
    this.getClassesAll();
  },
  methods: {
    async getClassesAll() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://10.109.2.112:8081/api/classesdashboard2",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.classes = response.data.classes;
        } else {
          console.error(
            "Error fetching classes. Status code:",
            response.status
          );
        }
      } catch (error) {
        console.error("An error occurred:", error);
      }
    },
  },
};
</script>
