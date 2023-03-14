<template>
  <thead>
    <tr class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
      <th class="px-1 py-2 border" colspan="2">CLARK</th>
      <th class="px-1 py-2 border"></th>
      <th class="px-4 py-1 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
      <th class="px-1 py-2 border"></th>
    </tr>
  </thead>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">AT&T BB MULTI SERVICE</td>
    <template v-for="(clark1, weeksStart) in groupedData1" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark1Item in clark1"
        :key="clark1Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark1Item)">
            {{ clark1Item.total_target }}
          </button>
          <div v-if="clark1Item.showButtons" class="flex items-center">
            <div v-if="clark1Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark1Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark1Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark1Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark1Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark1Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark1Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark1.reduce((sum, clark1Item) => {
            return sum + parseFloat(clark1Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>

  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">EBAY BUY PHONE</td>
    <template v-for="(clark2, weeksStart) in groupedData2" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark2Item in clark2"
        :key="clark2Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark2Item)">
            {{ clark2Item.total_target }}
          </button>
          <div v-if="clark2Item.showButtons" class="flex items-center">
            <div v-if="clark2Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark2Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark2Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark2Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark2Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark2Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark2Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark2.reduce((sum, clark2Item) => {
            return sum + parseFloat(clark2Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">EBAY BUY CHAT</td>
    <template v-for="(clark3, weeksStart) in groupedData3" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark3Item in clark3"
        :key="clark3Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark3Item)">
            {{ clark3Item.total_target }}
          </button>
          <div v-if="clark3Item.showButtons" class="flex items-center">
            <div v-if="clark3Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark3Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark3Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark3Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark3Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark3Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark3Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark3.reduce((sum, clark3Item) => {
            return sum + parseFloat(clark3Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">EBAY SELL PHONE</td>
    <template v-for="(clark4, weeksStart) in groupedData4" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark4Item in clark4"
        :key="clark4Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark4Item)">
            {{ clark4Item.total_target }}
          </button>
          <div v-if="clark4Item.showButtons" class="flex items-center">
            <div v-if="clark4Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark4Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark4Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark4Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark4Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark4Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark4Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark4.reduce((sum, clark4Item) => {
            return sum + parseFloat(clark4Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">EBAY SELL CHAT</td>
    <template v-for="(clark5, weeksStart) in groupedData5" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark5Item in clark5"
        :key="clark5Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark5Item)">
            {{ clark5Item.total_target }}
          </button>
          <div v-if="clark5Item.showButtons" class="flex items-center">
            <div v-if="clark5Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark5Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark5Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark5Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark5Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark5Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark5Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark5.reduce((sum, clark5Item) => {
            return sum + parseFloat(clark5Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">ZINUS</td>
    <template v-for="(clark6, weeksStart) in groupedData6" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark6Item in clark6"
        :key="clark6Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark6Item)">
            {{ clark6Item.total_target }}
          </button>
          <div v-if="clark6Item.showButtons" class="flex items-center">
            <div v-if="clark6Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark6Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark6Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark6Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark6Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark6Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark6Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark6.reduce((sum, clark6Item) => {
            return sum + parseFloat(clark6Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">MODIVCARE FULL TIME</td>
    <template v-for="(clark7, weeksStart) in groupedData7" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark7Item in clark7"
        :key="clark7Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark7Item)">
            {{ clark7Item.total_target }}
          </button>
          <div v-if="clark7Item.showButtons" class="flex items-center">
            <div v-if="clark7Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark7Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark7Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark7Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark7Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark7Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark7Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark7.reduce((sum, clark7Item) => {
            return sum + parseFloat(clark7Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">COMCAST SPECIAL PROJECT</td>
    <template v-for="(clark8, weeksStart) in groupedData8" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark8Item in clark8"
        :key="clark8Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark8Item)">
            {{ clark8Item.total_target }}
          </button>
          <div v-if="clark8Item.showButtons" class="flex items-center">
            <div v-if="clark8Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark8Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark8Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark8Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark8Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark8Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark8Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark8.reduce((sum, clark8Item) => {
            return sum + parseFloat(clark8Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">LUMEN/CRIS PHQ1</td>
    <template v-for="(clark9, weeksStart) in groupedData9" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark9Item in clark9"
        :key="clark9Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark9Item)">
            {{ clark9Item.total_target }}
          </button>
          <div v-if="clark9Item.showButtons" class="flex items-center">
            <div v-if="clark9Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark9Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark9Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark9Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark9Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark9Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark9Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark9.reduce((sum, clark9Item) => {
            return sum + parseFloat(clark9Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">ENS Q1</td>
    <template v-for="(clark10, weeksStart) in groupedData10" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark10Item in clark10"
        :key="clark10Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark10Item)">
            {{ clark10Item.total_target }}
          </button>
          <div v-if="clark10Item.showButtons" class="flex items-center">
            <div v-if="clark10Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark10Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark10Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark10Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark10Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark10Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark10Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark10.reduce((sum, clark10Item) => {
            return sum + parseFloat(clark10Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">CRIS Q2A</td>
    <template v-for="(clark11, weeksStart) in groupedData11" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark11Item in clark11"
        :key="clark11Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark11Item)">
            {{ clark11Item.total_target }}
          </button>
          <div v-if="clark11Item.showButtons" class="flex items-center">
            <div v-if="clark11Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark11Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark11Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark11Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark11Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark11Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark11Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark11.reduce((sum, clark11Item) => {
            return sum + parseFloat(clark11Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">CRIS Q2B</td>
    <template v-for="(clark12, weeksStart) in groupedData12" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark12Item in clark12"
        :key="clark12Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark12Item)">
            {{ clark12Item.total_target }}
          </button>
          <div v-if="clark12Item.showButtons" class="flex items-center">
            <div v-if="clark12Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark12Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark12Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark12Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark12Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark12Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark12Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark12.reduce((sum, clark12Item) => {
            return sum + parseFloat(clark12Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">FIS</td>
    <template v-for="(clark13, weeksStart) in groupedData13" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark13Item in clark13"
        :key="clark13Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark13Item)">
            {{ clark13Item.total_target }}
          </button>
          <div v-if="clark13Item.showButtons" class="flex items-center">
            <div v-if="clark13Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark13Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark13Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark13Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark13Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark13Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark13Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark13.reduce((sum, clark13Item) => {
            return sum + parseFloat(clark13Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">BRIGHTSPEED T1</td>
    <template v-for="(clark14, weeksStart) in groupedData14" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark14 in clark14" :key="clark14.id">
        <button>{{ clark14.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">BRIGHTSPEED 2A</td>
    <template v-for="(clark14, weeksStart) in groupedData14" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark14Item in clark14"
        :key="clark14Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark14Item)">
            {{ clark14Item.total_target }}
          </button>
          <div v-if="clark14Item.showButtons" class="flex items-center">
            <div v-if="clark14Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark14Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark14Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark14Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark14Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark14Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark14Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark14.reduce((sum, clark14Item) => {
            return sum + parseFloat(clark14Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">BRIGHTSPEED ACQ</td>
    <template v-for="(clark15, weeksStart) in groupedData15" :key="weeksStart">
      <td
        class="px-1 py-2 text-center border place-content-center"
        v-for="clark15Item in clark15"
        :key="clark15Item.id"
      >
        <div class="flex items-center">
          <button class="mr-2 bg-white" @click="showButtons(clark15Item)">
            {{ clark15Item.total_target }}
          </button>
          <div v-if="clark15Item.showButtons" class="flex items-center">
            <div v-if="clark15Item.total_target == 0">
              <router-link :to="`/addcapfile/${clark15Item.id}`"
                ><button class="mx-2 bg-blue-500" @click="add(clark15Item)">
                  Add
                </button></router-link
              >
            </div>
            <div v-else>
              <router-link :to="`/pushbackcapfile/${clark15Item.id}`"
                ><button
                  class="mx-2 bg-green-500 w-22"
                  @click="getClasses(clark15Item.id)"
                >
                  Pushed Back
                </button></router-link
              >
              <router-link :to="`/cancelcapfile/${clark15Item.id}`"
                ><button class="mx-2 bg-red-500 w-22" @click="getClasses(clark15Item.id)">
                  Cancel
                </button></router-link
              >
            </div>
          </div>
        </div>
      </td>
      <td class="px-1 py-2 font-bold text-white bg-blue-600 border">
        {{
          clark15.reduce((sum, clark15Item) => {
            return sum + parseFloat(clark15Item.total_target || 0);
          }, 0)
        }}
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">COMCAST XM T1</td>
    <template v-for="(clark17, weeksStart) in groupedData17" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark17 in clark17" :key="clark17.id">
        <button>{{ clark17.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">COMCAST NED 3P</td>
    <template v-for="(clark18, weeksStart) in groupedData18" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark18 in clark18" :key="clark18.id">
        <button>{{ clark18.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">COMCAST NED BILLING</td>
    <template v-for="(clark19, weeksStart) in groupedData19" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark19 in clark19" :key="clark19.id">
        <button>{{ clark19.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">COMCAST XM BILLING</td>
    <template v-for="(clark20, weeksStart) in groupedData20" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark20 in clark20" :key="clark20.id">
        <button>{{ clark20.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">HYOSUNG</td>
    <template v-for="(clark21, weeksStart) in groupedData21" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark21 in clark21" :key="clark21.id">
        <button>{{ clark21.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">DOORDASH</td>
    <template v-for="(clark22, weeksStart) in groupedData22" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark22 in clark22" :key="clark22.id">
        <button>{{ clark22.total_target }}</button>
      </td>
    </template>
  </tbody>
  <tbody class="font-semibold text-black bg-white border-2 border-gray-400 border-solid">
    <td class="px-1 py-2 border">CLARK</td>
    <td class="px-1 py-2 border">DUN & BRADSTREET</td>
    <template v-for="(clark23, weeksStart) in groupedData23" :key="weeksStart">
      <td class="px-1 py-2 border" v-for="clark23 in clark23" :key="clark23.id">
        <button>{{ clark23.total_target }}</button>
      </td>
    </template>
  </tbody>
</template>
<script>
import axios from "axios";
export default {
  data() {
    return {
      groupedData1: [],
      groupedData2: [],
      groupedData3: [],
      groupedData4: [],
      groupedData5: [],
      groupedData6: [],
      groupedData7: [],
      groupedData8: [],
      groupedData9: [],
      groupedData10: [],
      groupedData11: [],
      groupedData12: [],
      groupedData13: [],
      groupedData14: [],
      groupedData15: [],
      groupedData16: [],
      groupedData17: [],
      groupedData18: [],
      groupedData19: [],
      groupedData20: [],
      groupedData21: [],
      groupedData22: [],
      groupedData23: [],
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.getClark();
  },
  methods: {
    async getClark() {
      await axios
        .get("http://127.0.0.1:8000/api/clark")
        .then((response) => {
          this.groupedData1 = response.data.clark1;
          this.groupedData2 = response.data.clark2;
          this.groupedData3 = response.data.clark3;
          this.groupedData4 = response.data.clark4;
          this.groupedData5 = response.data.clark5;
          this.groupedData6 = response.data.clark6;
          this.groupedData7 = response.data.clark7;
          this.groupedData8 = response.data.clark8;
          this.groupedData9 = response.data.clark9;
          this.groupedData10 = response.data.clark10;
          this.groupedData11 = response.data.clark11;
          this.groupedData12 = response.data.clark12;
          this.groupedData13 = response.data.clark13;
          this.groupedData14 = response.data.clark14;
          this.groupedData15 = response.data.clark15;
          this.groupedData16 = response.data.clark16;
          this.groupedData17 = response.data.clark17;
          this.groupedData18 = response.data.clark18;
          this.groupedData19 = response.data.clark19;
          this.groupedData20 = response.data.clark20;
          this.groupedData21 = response.data.clark21;
          this.groupedData22 = response.data.clark22;
          this.groupedData23 = response.data.clark23;
          console.log(response.data.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    showButtons(clark1Item) {
      clark1Item.showButtons = !clark1Item.showButtons;
    },
  },
};
</script>
