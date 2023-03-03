<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h1 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Capacity File Dashboard
      </h1>
    </div>
  </header>
  <div class="flex float-right py-8 pr-8">
    <router-link to="/addcapfile">
      <button
        class="self-center px-4 py-1 font-bold text-white bg-orange-600 rounded hover:bg-gray-600"
      >
        <i class="fa fa-folder"></i> Add
      </button>
    </router-link>
  </div>
  <div class="py-8">
    <div class="w-full pl-8 pr-8 overflow-x-scroll overflow-y-hidden">
      <table class="w-full text-white table-auto">
        <thead>
          <tr class="text-left bg-orange-600 border-2 border-orange-600 border-solid">
            <th class="px-10 py-1 truncate whitespace-no-wrap border">Site</th>
            <th class="px-10 py-1 truncate whitespace-no-wrap border">
              Line of Business
            </th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jan 1-7</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jan 8-14</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jan 15-21</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jan 22-28</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jan</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">29-Feb 4</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Feb 5-11</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Feb 12-18</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Feb 19-25</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Feb</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">26-Mar 4</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Mar 5-11</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Mar 12-18</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Mar 19-25</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Mar</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">26-Apr 1</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Apr 2-8</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Apr 9-15</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Apr 16-22</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Apr 23-29</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Apr</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">30-May 6</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">May 7-13</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">May 14-20</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">May 21-27</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">May</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">28-Jun 3</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jun 4-10</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jun 11-17</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jun 18-24</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jun</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">25Jul1</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jul 2-8</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jul 8-15</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jul 16-22</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jul 23-29</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jul</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">30-Aug 5</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Aug 6-12</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Aug 13-19</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Aug 20-26</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Aug</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">26-Sep 2</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Sep 3-19</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Sep 10-16</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Sep 17-23</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Sep 24-30</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Sep</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Oct 1-7</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Oct 8-14</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Oct 15-21</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Oct 22-28</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Oct</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">29-Nov 4</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Nov 5-11</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Nov 12-18</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Nov 19-25</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Nov</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">26-Dec 2</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Dec 3-9</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Dec 10-16</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Dec 17-23</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Dec 18-30</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Dec</th>
          </tr>
        </thead>
        <Clark />
        <QuezonCity />
        <Bridgetowne />
        <Makati />
        <Moa />
        <SmDv />
        <RobDv />
        <DeltaDv />
        <CenDv />
        <Dfc />
      </table>
    </div>
  </div>
</template>
<script>
import Clark from "../User/Capfile/ClarkTable.vue";
import QuezonCity from "../User/Capfile/QuezonCityTable.vue";
import Bridgetowne from "../User/Capfile/BridgetowneTable.vue";
import Makati from "../User/Capfile/MakatiTable.vue";
import Moa from "../User/Capfile/MoaTable.vue";
import SmDv from "../User/Capfile/SmDvTable.vue";
import RobDv from "../User/Capfile/RobDvTable.vue";
import DeltaDv from "../User/Capfile/DeltaDvTable.vue";
import CenDv from "../User/Capfile/CentralDv.vue";
import Dfc from "../User/Capfile/DfcTable.vue";
export default {
  components: {
    Clark,
    QuezonCity,
    Bridgetowne,
    Makati,
    Moa,
    SmDv,
    RobDv,
    DeltaDv,
    CenDv,
    Dfc,
  },
};
</script>
<style></style>
