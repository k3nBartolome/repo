<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full py-2  max-w-screen-xl sm:px-2 lg:px-2">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900 pl-8">
        Capacity File Dashboard
      </h1>
    </div>
  </header>
  <div class="py-8">
    <div class="pl-8 pr-8  overflow-y-auto overflow-x-auto">
      <table class="w-full table-auto text-white">
        <thead>
          <tr class="text-left bg-orange-600 border-solid border-2 border-orange-600">
            <th class="px-1 py-2 ">Site</th>
            <th class="px-1 py-2 ">Line of Business</th>
            <th class="px-1 py-2 ">Jan 1-Jan 7</th>
            <th class="px-1 py-2 ">Jan 8-Jan 14</th>
            <th class="px-1 py-2 ">Jan 15-Jan 21</th>
            <th class="px-1 py-2 ">Jan 22-Jan 28</th>
            <th class="px-1 py-2 ">Jan</th>
            <th class="px-1 py-2 ">Jan 29-Feb 4</th>
            <th class="px-1 py-2 ">Feb 5-Feb 11</th>
            <th class="px-1 py-2 ">Feb 12-Feb 18</th>
            <th class="px-1 py-2 ">Feb 19-Feb 25</th>
            <th class="px-1 py-2 ">Feb</th>
            <th class="px-1 py-2 ">Feb 26-Mar 4</th>
            <th class="px-1 py-2 ">Mar 5-Mar 11</th>
            <th class="px-1 py-2 ">Mar 12-Mar 18</th>
            <th class="px-1 py-2 ">Mar 19-Mar 25</th>
            <th class="px-1 py-2 ">Mar</th>
            <th class="px-1 py-2 ">Mar 26-Apr 1</th>
            <th class="px-1 py-2 ">April 2-Apr 8</th>
            <th class="px-1 py-2 ">Apr 9-Feb 15</th>
            <th class="px-1 py-2 ">Apr 16-Apr 22</th>
            <th class="px-1 py-2 ">Apr 23-Apr 29</th>
            <th class="px-1 py-2 ">Apr</th>
            <th class="px-1 py-2 ">Apr 30-May 6</th>
            <th class="px-1 py-2 ">May 7-May 13</th>
            <th class="px-1 py-2 ">May 14-Jan 20</th>
            <th class="px-1 py-2 ">May 21-May 27</th>
            <th class="px-1 py-2 ">May</th>
            <th class="px-1 py-2 ">May 28-Jun 3</th>
            <th class="px-1 py-2 ">jun 4-Feb 10</th>
            <th class="px-1 py-2 ">Jun 11-Jun 17</th>
            <th class="px-1 py-2 ">Jun 18-Jun 24</th>
            <th class="px-1 py-2 ">Jun</th>
            <th class="px-1 py-2 ">Jun 25-Jul 1</th>
            <th class="px-1 py-2 ">Jul 2-Jul 8</th>
            <th class="px-1 py-2 ">Jul 8-Jul 15</th>
            <th class="px-1 py-2 ">Jul 16-Jul 22</th>
            <th class="px-1 py-2 ">Jul 23-Jul 29</th>
            <th class="px-1 py-2 ">Jul</th>
            <th class="px-1 py-2 ">July 30-Aug 5</th>
            <th class="px-1 py-2 ">Aug 6-Aug 12</th>
            <th class="px-1 py-2 ">Aug 13-Aug 19</th>
            <th class="px-1 py-2 ">Aug 20-Aug 26</th>
            <th class="px-1 py-2 ">Aug</th>
            <th class="px-1 py-2 ">Aug 26-Sep 2</th>
            <th class="px-1 py-2 ">Sep 3-Sep 19</th>
            <th class="px-1 py-2 ">Sep 10-Sep 16</th>
            <th class="px-1 py-2 ">Sep 17-Sep 23</th>
            <th class="px-1 py-2 ">Sep 24-Sep 30</th>
            <th class="px-1 py-2 ">Sep</th>
            <th class="px-1 py-2 ">Oct 1-Oct 7</th>
            <th class="px-1 py-2 ">Oct 8-Oct 14</th>
            <th class="px-1 py-2 ">Oct 15-Oct 21</th>
            <th class="px-1 py-2 ">Oct 22-Oct 28</th>
            <th class="px-1 py-2 ">Oct</th>
            <th class="px-1 py-2 ">Oct 29-Nov 4</th>
            <th class="px-1 py-2 ">Nov 5-Nov 11</th>
            <th class="px-1 py-2 ">Nov 12-Nov 18</th>
            <th class="px-1 py-2 ">Nov 19-Nov 25</th>
            <th class="px-1 py-2 ">Nov</th>
            <th class="px-1 py-2 ">Nov 26-Dec 2</th>
            <th class="px-1 py-2 ">Dec 3-Dec 9</th>
            <th class="px-1 py-2 ">Dec 10-Dec 16</th>
            <th class="px-1 py-2 ">Dec 17-Dec 23</th>
            <th class="px-1 py-2 ">Dec 18-Dec 30</th>
            <th class="px-1 py-2 ">Dec</th>
          </tr>
        </thead>
        <tbody>
          <tr class="bg-white text-black font-semibold border-2 border-solid border-gray-400">
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
            <td class="px-1 py-2 ">12</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<style>
th {
  padding: 10px;
}
</style>
