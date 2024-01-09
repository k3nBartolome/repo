<template>
  <div>
    <div id="wdr-component"></div>
  </div>
</template>

<script>
import axios from "axios";
import WebDataRocks from "@webdatarocks/webdatarocks";
import "@webdatarocks/webdatarocks/webdatarocks.css";


export default {
  name: "App",
  data() {
    return {
      sr: [],
    };
  },
  methods: {
    async getSr() {
      try {
        const token = this.$store.state.token;
        const config = {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        };

        const response = await axios.get("http://10.109.2.112:8081/api/sr_compliance", config);
        console.log("Response received:", response.data);

        this.sr = response.data.sr;
        console.log("sr data:", this.sr);

        this.initializeWebDataRocks();
      } catch (error) {
        console.log("Error:", error);
      }
    },
    initializeWebDataRocks() {
      new WebDataRocks({
        container: this.$el.querySelector("#wdr-component"),
        toolbar: true,
        report: {
          dataSource: {
            data: this.sr,
          },
          options: {
           grid: {
            showHeaders:false,
          type: "flat"
           }
          }
        },
        afterchartdraw: this.afterchartdraw,
        aftergriddraw: this.aftergriddraw,
        beforegriddraw: this.beforegriddraw,
        beforetoolbarcreated: this.beforetoolbarcreated,
        cellclick: this.cellclick,
        celldoubleclick: this.celldoubleclick,
        componentFolder: this.componentFolder,
        customizeCell: this.customizeCell,
        customizeContextMenu: this.customizeContextMenu,
        datachanged: this.datachanged,
        dataerror: this.dataerror,
        datafilecancelled: this.datafilecancelled,
        dataloaded: this.dataloaded,
        fieldslistclose: this.fieldslistclose,
        fieldslistopen: this.fieldslistopen,
        filterclose: this.filterclose,
        filteropen: this.filteropen,
        fullscreen: this.fullscreen,
        global: this.global,
        height: this.height,
        loadingdata: this.loadingdata,
        loadinglocalization: this.loadinglocalization,
        loadingolapstructure: this.loadingolapstructure,
        loadingreportfile: this.loadingreportfile,
        localizationerror: this.localizationerror,
        localizationloaded: this.localizationloaded,
        olapstructureerror: this.olapstructureerror,
        olapstructureloaded: this.olapstructureloaded,
        openingreportfile: this.openingreportfile,
        querycomplete: this.querycomplete,
        queryerror: this.queryerror,
        ready: this.ready,
        reportchange: this.reportchange,
        reportcomplete: this.reportcomplete,
        reportfilecancelled: this.reportfilecancelled,
        reportfileerror: this.reportfileerror,
        reportfileloaded: this.reportfileloaded,
        runningquery: this.runningquery,
        update: this.update,
        width: this.width,
      });
    },
    // Define your event handlers here
    afterchartdraw() {
      // Handle afterchartdraw event
    },
    aftergriddraw() {
      // Handle aftergriddraw event
    },
    beforegriddraw() {
      // Handle beforegriddraw event
    },
    beforetoolbarcreated() {
      // Handle beforetoolbarcreated event
    },
    cellclick() {
      // Handle cellclick event
    },
    celldoubleclick() {
      // Handle celldoubleclick event
    },
    // ... (define other event handlers)
  },
  mounted() {
    this.getSr();
  },
};
</script>

<style scoped>
@import "~@webdatarocks/webdatarocks/webdatarocks.min.css";
</style>
