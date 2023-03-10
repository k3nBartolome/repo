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
            <th class="px-16 py-1 truncate whitespace-no-wrap border">Site</th>
            <th class="px-10 py-1 truncate whitespace-no-wrap border">
              Line of Business
            </th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jan 1-Jan 7</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jan 8-Jan 14</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jan 15-Jan 21</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jan 22-Jan 28</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jan</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jan 29-Feb 4</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Feb 5-Feb 11</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Feb 12-Feb 18</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Feb 19-Feb 25</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Feb</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Feb 26-Mar 4</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Mar 5-Mar 11</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Mar 12-Mar 18</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Mar 19-Mar 25</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">March</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Mar 26-Apr 1</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">April 2-Apr 8</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Apr 9-Apr 15</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Apr 16-Apr 22</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Apr 23-Apr 29</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">April</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Apr 30-May 6</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">May 7-May 13</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">May 14-May 20</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">May 21-May 27</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">May</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">May 28-Jun 3</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jun 4-Feb 10</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jun 11-Jun 17</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jun 18-Jun 24</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">June</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jun 25-Jul 1</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jul 2-Jul 8</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jul 8-Jul 15</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jul 16-Jul 22</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Jul 23-Jul 29</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">July</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">July 30-Aug 5</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Aug 6-Aug 12</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Aug 13-Aug 19</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Aug 20-Aug 26</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">August</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Aug 26-Sep 2</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Sep 3-Sep 19</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Sep 10-Sep 16</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Sep 17-Sep 23</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Sep 24-Sep 30</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">September</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Oct 1-Oct 7</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Oct 8-Oct 14</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Oct 15-Oct 21</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Oct 22-Oct 28</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">October</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Oct 29-Nov 4</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Nov 5-Nov 11</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Nov 12-Nov 18</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Nov 19-Nov 25</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Nov</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Nov 26-Dec 2</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Dec 3-Dec 9</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Dec 10-Dec 16</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Dec 17-Dec 23</th>
            <th class="px-4 py-1 truncate whitespace-no-wrap border">Dec 18-Dec 30</th>
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
