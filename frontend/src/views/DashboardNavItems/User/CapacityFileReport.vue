<template>
<h5 class="px-2">Dashboard</h5>
</template>
s
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
// eslint-disable-next-line no-unused-vars

import "bootstrap/dist/css/bootstrap.css";

DataTable.use(DataTableLib);
//DataTable.use(pdfmake);
DataTable.use(ButtonsHtml5);

export default {
  data() {
    return {
      classes: [],
      sites: [],
      programs: [],
      daterange: [],
      sites_selected: "",
      programs_selected: "",
      month_selected: "",
      week_selected: "",
      status: "",
      columns: [
        { data: "id", title: "ID" },
        { data: "site.country", title: "Country" },
        { data: "site.region", title: "Region" },
        { data: "status", title: "status" },
        { data: "site.name", title: "Site" },
        { data: "program.name", title: "Program" },
        { data: "date_range.month", title: "Month" },
        { data: "date_range.date_range", title: "Hiring Week" },
        { data: "total_target", title: "Total Target" },
        { data: "original_start_date", title: "Original Start Date" },
        { data: "type_of_hiring", title: "Type of Hiring" },
        { data: "wave_no", title: "Wave#" },
        { data: "erf_number", title: "ERF#" },
        { data: "created_at", title: "Created date" },
        { data: "created_by_user.name", title: "Created by" },
      ],
    };
  },
  computed: {
    /* getTotalTargetsByFilters() {
      let filteredData = [...this.classes];

      if (this.sites_selected) {
        filteredData = filteredData.filter((classes) => {
          return classes.site.id === this.sites_selected;
        });
      }

      if (this.programs_selected) {
        filteredData = filteredData.filter((classes) => {
          return classes.program.id === this.programs_selected;
        });
      }

      if (this.week_selected) {
        filteredData = filteredData.filter((classes) => {
          const weekId = classes.date_range.id;
          return weekId === this.week_selected;
        });
      }
      if (this.status) {
        filteredData = filteredData.filter((classes) => {
          const status = classes.status;
          return status === this.status;
        });
      }

      const totalTargets = filteredData.reduce(
        (total, item) => total + item.total_target,
        0
      );
      return totalTargets;
    },
    classExists() {
      return this.classes.some((c) => {
        return (
          c.site.id === this.sites_selected &&
          c.program.id === this.programs_selected &&
          c.date_range.id === this.week_selected
        );
      });
    },
    filteredData() {
      let filteredData = [...this.classes];
      if (this.sites_selected) {
        filteredData = filteredData.filter((classes) => {
          return classes.site.id === this.sites_selected;
        });
      }

      if (this.programs_selected) {
        filteredData = filteredData.filter((classes) => {
          return classes.program.id === this.programs_selected;
        });
      }

      if (this.week_selected) {
        filteredData = filteredData.filter((classes) => {
          const weekId = classes.date_range.id;
          return weekId === this.week_selected;
        });
      }
      if (this.status) {
        filteredData = filteredData.filter((classes) => {
          const status = classes.status;
          return status === this.status;
        });
      }

      return filteredData;
    },*/
  },
  mounted() {
    window.vm = this;
    this.getSites();
    this.getPrograms();
    this.getDateRange();
    this.getClassesAll();
  },
  methods: {
    resetFilter() {
      this.sites_selected = "";
      this.programs_selected = "";
      this.month_selected = "";
      this.week_selected = "";
      this.status = "";
    },
    navigateToEdit(id) {
      this.$router.push(`/editcapfile/${id}`);
    },
    navigateToCancel(id) {
      this.$router.push(`/cancelcapfile/${id}`);
    },
    navigateToPushback(id) {
      this.$router.push(`/pushbackcapfile/${id}`);
    },
    async getClassesAll() {
  try {
    const token = this.$store.state.token;
    const response = await axios.get("http://127.0.0.1:8000/api/classesdashboard", {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    if (response.status === 200) {
      // Request was successful, update the 'classes' data
      this.classes = response.data.classes;
      console.log("Classes fetched successfully:", this.classes);
    } else {
      // Handle unexpected status codes (e.g., 404, 500, etc.)
      console.error("Error fetching classes. Status code:", response.status);
    }
  } catch (error) {
    // Handle network errors or other exceptions
    console.error("An error occurred:", error);
  }
},

    async getSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/sites", {
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
      if (!this.sites_selected) {
        return;
      }

      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          `http://127.0.0.1:8000/api/programs_selected/${this.sites_selected}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.programs = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching programs");
        }
      } catch (error) {
        console.error(error);
      }
    },

    async getDateRange() {
      if (!this.month_selected) {
        return;
      }

      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          `http://127.0.0.1:8000/api/daterange_selected/${this.month_selected}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
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
  },
};
</script>
<style>
.table-responsive {
  overflow: auto;
}

.datatable-container {
  width: 100%;
}

.table {
  white-space: nowrap;
}

.table thead th {
  padding: 8px;
}

.table tbody td {
  padding: 8px;
}
.dataTables_wrapper .dataTables_filter {
  float: left;
  padding-right: 30px;
}

.dataTables_wrapper .dataTables_Buttons {
  float: left;
  margin-top: 30px;
}

.dataTables_wrapper .dataTables_pagination {
  float: left;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.dataTables_wrapper .dataTables_length {
  float: left;
  padding-right: 15px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.dataTables_wrapper .dt-buttons .btn {
  background-color: #007bff;
  color: #fff;
  border-radius: 4px;
  padding: 8px 12px;
  margin-right: 8px;
  margin-top: 15px;
}
</style>
