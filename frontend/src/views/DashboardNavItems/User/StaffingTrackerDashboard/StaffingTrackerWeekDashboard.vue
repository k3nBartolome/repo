<template>
  <div>
    <input v-model="filterName" placeholder="Filter by Name" />
    <input v-model="filterRegion" placeholder="Filter by Region" />
    <input v-model="filterCountry" placeholder="Filter by Country" />
    <button @click="fetchData">Filter</button>
    <button @click="exportToExcel">Export to Excel</button>

    <ul v-if="sites.length > 0">
      <li v-for="site in sites" :key="site.id">
        {{ site.name }} - {{ site.region }} - {{ site.country }}
      </li>
    </ul>
    
    <p v-else>No data available</p>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      filterName: "",
      filterRegion: "",
      filterCountry: "",
      sites: [],
    };
  },
  methods: {
    async fetchData() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/perxfilter", {
          params: {
            filter_name: this.filterName,
            filter_region: this.filterRegion,
            filter_country: this.filterCountry,
          },
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        this.sites = response.data.perx;
      } catch (error) {
        console.error("Error fetching data", error);
      }
    },
    exportToExcel() {
  try {
    const token = this.$store.state.token;
    axios
      .get("http://127.0.0.1:8000/api/export", {
        params: {
          filter_name: this.filterName,
          filter_region: this.filterRegion,
          filter_country: this.filterCountry,
        },
        responseType: "blob",
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then((response) => {
        const blob = new Blob([response.data], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.href = url;
        link.download = "exported-data.xlsx";
        link.click();
      })
      .catch((error) => {
        console.error("Error exporting data", error);
      });
  } catch (error) {
    console.error("Error fetching token", error);
  }
},


  },
};
</script>
