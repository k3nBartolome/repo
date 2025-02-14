<template>
  <header class="w-full bg-white">
    <div class="items-center w-full">
      <h2 class="text-xl font-bold text-center">PUSHBACK CLASS</h2>
    </div>
  </header>
  <div v-if="loading" class="loader">
    <div aria-label="Loading..." role="status" class="loader">
      <svg class="icon" viewBox="0 0 256 256">
        <line
          x1="128"
          y1="32"
          x2="128"
          y2="64"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="195.9"
          y1="60.1"
          x2="173.3"
          y2="82.7"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="224"
          y1="128"
          x2="192"
          y2="128"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="195.9"
          y1="195.9"
          x2="173.3"
          y2="173.3"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="128"
          y1="224"
          x2="128"
          y2="192"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="60.1"
          y1="195.9"
          x2="82.7"
          y2="173.3"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="32"
          y1="128"
          x2="64"
          y2="128"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
        <line
          x1="60.1"
          y1="60.1"
          x2="82.7"
          y2="82.7"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="24"
        ></line>
      </svg>
      <span class="loading-text">Loading...</span>
    </div>
  </div>
  <header class="w-full">
    <div class="flex items-center w-full max-w-screen-xl sm:px-2 lg:px-2">
      <h2 class="pl-8 text-sm font-bold tracking-tight text-gray-900">
        <button
          @click="showModal = true"
          class="px-4 py-2 mr-2 text-white bg-blue-500 rounded hover:bg-blue-600"
        >
          View History
        </button>
      </h2>
    </div>
  </header>
  <div class="py-0 px-4">
    <div class="px-4 py-0 bg-white">
      <div
        class="fixed inset-0 z-50 flex items-center justify-center modal mx-4"
        v-if="showModal"
      >
        <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
        <div
          class="min-w-full max-w-3xl w-auto p-4 bg-white rounded shadow-lg modal-content px-4"
        >
          <header class="px-4 py-2 border-b-2 border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Class History</h2>
          </header>
          <button
            @click="showModal = false"
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
          <div class="modal-scrollable-content">
            <table class="min-w-full border-collapse border-2 border-gray-300">
              <thead>
                <tr class="border-b-4 border-gray-300 bg-gray-100 text-center">
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    ID
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Site
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Line of Business
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Type of Hiring
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Total Target
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Original Start Date
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Movement Date
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Weeks Range
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Within SLA?
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Agreed Start Date
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Requested by
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Approved by
                  </th>
                  <th class="border-2 border-gray-300 px-2 py-2 truncate">
                    Transaction Type
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="classes in classes"
                  :key="classes.id"
                  class="border-2 border-black"
                >
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.pushedback_id }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.site.name }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.program.name }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.type_of_hiring }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.total_target }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.original_start_date }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.wfm_date_requested }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.date_range.date_range }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.within_sla }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.agreed_start_date }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.requested_by }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.approved_by }}
                  </td>
                  <td
                    class="border-2 border-gray-300 px-4 py-2 text-left font-semibold truncate"
                  >
                    {{ classes.changes }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="px-12 py-8 font-serifs">
    <form @submit.prevent="pushClass">
      <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
        <div class="py-6 flex space-x-4">
          <label class="block">
            Target and Dates
            <input
              type="radio"
              v-model="changes"
              required
              checked
              value="Change Targets and Dates"
              class="ml-2 mr-4 border-gray-500 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </label>
          <label class="block">
            Targets
            <input
              type="radio"
              v-model="changes"
              value="Change Targets"
              required
              class="ml-2 mr-4 border-gray-500 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </label>
          <label class="block">
            Dates
            <input
              type="radio"
              v-model="changes"
              value="Change Dates"
              required
              class="ml-2 mr-4 border-gray-500 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </label>
          <label class="block">
            Type of Hiring
            <input
              type="radio"
              v-model="changes"
              value="Change Type of Hiring"
              required
              class="ml-2 mr-4 border-gray-500 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </label>
          <label class="block">
            Category
            <input
              type="radio"
              v-model="changes"
              value="Change Category"
              required
              class="ml-2 mr-4 border-gray-500 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </label>
        </div>
      </div>
      <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block">
            Site
            <select
              disabled
              v-model="sites_selected"
              class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
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
              class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
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
              v-model="type_of_hiring"
              :disabled="isTypeOfHiringDisabled"
              class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
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
              :disabled="isTargetDisabled"
              v-model="external_target"
              name="external_target"
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
              :disabled="isTargetDisabled"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              v-model="internal_target"
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
              disabled
              class="w-full px-4 py-2 bg-white border rounded-lg"
            />
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block"
            >Original Start Date
            <input
              type="date"
              v-model="original_start_date"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              @change="syncNoticeDays"
            />
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block"
            >Date of Request
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
              type="number"
              v-model="notice_days"
              disabled
              class="w-full px-4 py-2 bg-white border rounded-lg"
              @change="syncNoticeWeeks"
            />
          </label>
        </div>
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block">
            Notice Weeks
            <input
              type="text"
              v-model="notice_weeks"
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
              class="w-full px-4 py-2 bg-gray-100 border rounded-lg"
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
            >Category
            <select
              :disabled="isCategoryDisabled"
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
        <div class="w-full mt-1 md:w-1/5 md:mt-0">
          <label class="block"
            >Agreed Start Date
            <input
              type="date"
              required
              v-model="agreed_start_date"
              :disabled="isDateDisabled"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              @change="syncNoticeDays"
            />
          </label>
        </div>
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
        <div class="w-full mt-1 md:w-4/5 md:mt-0">
          <div class="py-6 flex space-x-4">
            <label class="block font-bold">Requested By:</label>
            <label
              class="block"
              v-for="requester in [
                'Talent Acquisition',
                'Workforce',
                'Training',
                'Client',
                'Operation',
              ]"
              :key="requester"
            >
              <input
                type="checkbox"
                v-model="requested_by"
                :value="requester"
                v-bind:checked="requested_by.includes(requester)"
              />
              {{ requester }}
            </label>
          </div>
        </div>
      </div>
      <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
        <div
          class="w-full mt-1 md:w-1/5 md:mt-0"
          v-if="requested_by.includes('Talent Acquisition')"
        >
          <label class="block"
            >Talent Acquisition
            <input
              type="text"
              v-model="ta"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            />
          </label>
        </div>
        <div
          class="w-full mt-1 md:w-1/5 md:mt-0"
          v-if="requested_by.includes('Workforce')"
        >
          <label class="block"
            >Workforce
            <input
              type="text"
              v-model="wf"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            />
          </label>
        </div>
        <div
          class="w-full mt-1 md:w-1/5 md:mt-0"
          v-if="requested_by.includes('Training')"
        >
          <label class="block"
            >Training
            <input
              type="text"
              v-model="tr"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            />
          </label>
        </div>
        <div
          class="w-full mt-1 md:w-1/5 md:mt-0"
          v-if="requested_by.includes('Client')"
        >
          <label class="block"
            >Client
            <input
              type="text"
              v-model="cl"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            />
          </label>
        </div>
        <div
          class="w-full mt-1 md:w-1/5 md:mt-0"
          v-if="requested_by.includes('Operation')"
        >
          <label class="block"
            >Operation
            <input
              type="text"
              v-model="op"
              class="w-full px-4 py-2 bg-white border rounded-lg"
            />
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
          <i class="fa fa-save"></i> Pushback
        </button>
      </div>
    </form>
    <form class="" @submit.prevent="updateClass">
      <div class="py-2 bg-gray-100">
        <div
          class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
        >
          <div class="mb-2">
            <h2 class="text-base font-bold text-left text-orange-600">
              Day Show-ups
            </h2>
          </div>

          <div
            class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-3"
          >
            <label class="block">
              Day 1
              <input
                type="number"
                v-model="day_1"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Day 2
              <input
                type="number"
                v-model="day_2"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Day 3
              <input
                type="number"
                v-model="day_3"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Day 4
              <input
                type="number"
                v-model="day_4"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Day 5
              <input
                type="number"
                v-model="day_5"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <!--   <label class="block">
          Day 6
          <input
            type="number"
            v-model="day_6"
            class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
            required
          />
        </label> -->
            <label class="block">
              Total_Endorsed
              <input
                disabled
                type="number"
                v-model="total_endorsed"
                class="block w-full mt-1 bg-gray-200 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
          </div>
        </div>
      </div>

      <div class="py-2 bg-gray-100">
        <div
          class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
        >
          <div class="mb-2">
            <h2 class="text-base font-bold text-left text-orange-600">
              Show-ups
            </h2>
          </div>
          <div
            class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5"
          >
            <label class="block">
              Show-ups Internal
              <input
                type="number"
                v-model="show_ups_internal"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
                @change="syncShowUpsTotal"
              />
            </label>
            <label class="block">
              Show-ups External
              <input
                type="number"
                v-model="show_ups_external"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
                @change="syncShowUpsTotal"
              />
            </label>
            <label class="block">
              Show-ups Total
              <input
                disabled
                type="number"
                v-model="show_ups_total"
                class="block w-full mt-1 bg-gray-200 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Deficit
              <input
                disabled
                type="number"
                v-model="deficit"
                class="block w-full mt-1 bg-gray-200 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Percentage
              <input
                disabled
                type="text"
                v-model="percentage"
                class="block w-full mt-1 bg-gray-200 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Status
              <select
                v-model="status"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              >
                <option disabled value="" selected>Please select one</option>
                <option value="open">Open</option>
                <option value="filled">Filled</option>
              </select>
            </label>
            <label class="block">
              Additional Extended JO
              <input
                type="number"
                v-model="additional_extended_jo"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Overhires
              <input
                disabled
                type="number"
                v-model="over_hires"
                class="block w-full mt-1 bg-gray-200 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Open
              <input
                disabled
                type="number"
                v-model="open"
                class="block w-full mt-1 bg-gray-200 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Filled
              <input
                disabled
                type="number"
                v-model="filled"
                class="block w-full mt-1 bg-gray-200 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
          </div>
        </div>
      </div>
      <div class="py-2 bg-gray-100">
        <div
          class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
        >
          <div class="mb-2">
            <h2 class="text-base font-bold text-left text-orange-600">
              Pipeline
            </h2>
          </div>
          <div
            class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-3"
          >
            <label class="block">
              With JO
              <input
                type="number"
                v-model="with_jo"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Internals
              <input
                type="number"
                v-model="internals_hires"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Pending Jo
              <input
                type="number"
                v-model="pending_jo"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Pending Berlitz
              <input
                type="number"
                v-model="pending_berlitz"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Pending OV
              <input
                type="number"
                v-model="pending_ov"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Pending Pre-Emps
              <input
                type="number"
                v-model="pending_pre_emps"
                class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
          </div>
        </div>
      </div>
      <div class="py-2 bg-gray-100">
        <div
          class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
        >
          <div
            class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-5"
          >
            <label class="block">
              Classes
              <input
                disabled
                type="number"
                v-model="classes_number"
                class="block w-full mt-1 bg-gray-200 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Total Pipeline
              <input
                disabled
                type="number"
                v-model="pipeline_total"
                class="block w-full mt-1 bg-gray-200 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Cap Starts
              <input
                disabled
                type="number"
                v-model="cap_starts"
                class="block w-full mt-1 bg-gray-200 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              All Internals
              <input
                disabled
                type="number"
                v-model="internals_hires_all"
                class="block w-full mt-1 bg-gray-200 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <label class="block">
              Pipeline Target
              <input
                disabled
                type="number"
                v-model="pipeline_target"
                class="block w-full mt-1 bg-gray-200 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                required
              />
            </label>
            <!--   <label class="block">
                Total Deficit
                <input
                  type="number"
                  v-model="deficit_total"
                  class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
                  required
                />
              </label> -->
          </div>
          <label class="block">
            Additional Remarks
            <textarea
              type="text"
              v-model="additional_remarks"
              class="block w-full mt-1 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100"
              required
            />
          </label>
        </div>
      </div>
      <div class="py-2 bg-gray-100">
        <div
          class="px-4 py-6 mx-auto bg-white border-2 border-orange-600 max-w-7xl sm:px-6 lg:px-8"
        >
          <div class="grid grid-cols-1 gap-4 font-semibold">
            <label class="block">
              Pipeline
              <textarea
                disabled
                v-model="pipeline"
                class="block w-full mt-1 bg-gray-200 border rounded-md focus:border-orange-600 focus:ring focus:ring-orange-600 focus:ring-opacity-100 h-100"
                required
              />
            </label>
          </div>
          <div class="flex justify-center py-8">
            <button
              type="submit"
              class="self-center px-4 py-1 font-bold text-white bg-orange-500 rounded hover:bg-gray-600 wi"
            >
              <i class="fa fa-save"></i> Save
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import axios from "axios";
export default {
  data() {
    return {
      sites_selected: "",
      programs_selected: "",
      date_selected: "",
      type_of_hiring: "",
      external_target: "",
      internal_target: "",
      total_target: 0,
      with_erf: "",
      original_start_date: "",
      wfm_date_requested: "",
      remarks: "",
      category: "",
      approved_by: "",
      notice_days: 0,
      erf_number: "",
      loading: false,
      sites: [],
      daterange: [],
      programs: [],
      agreed_start_date: "",
      within_sla: "",
      changes: "Change Targets and Dates",
      wave_no: "",
      ta: "",
      wf: "",
      tr: "",
      cl: "",
      op: "",
      disableForm: true,
      requested_by: [],
      classes: [],
      databaseValue: "",
      showModal: false,
      day_1: "",
      day_2: "",
      day_3: "",
      day_4: "",
      day_5: "",
      day_6: "",
      total_endorsed: "",
      show_ups_internal: "",
      show_ups_external: "",
      show_ups_total: "",
      deficit: "",
      classes_id: "",
      percentage: "",
      status: "",
      open: "",
      filled: "",
      internals_hires: "",
      externals_hires: "",
      additional_extended_jo: "",
      with_jo: "",
      pending_jo: "",
      pending_berlitz: "",
      pending_ov: "",
      pending_pre_emps: "",
      classes_number: "",
      pipeline_total: "",
      cap_starts: "",
      internals_hires_all: "",
      externals_hires_all: "",
      pipeline_target: "",
      deficit_total: "",
      over_hires: "",
    };
  },
  computed: {
    isTargetDisabled() {
      if (this.changes === "Change Dates") {
        return true;
      } else if (this.changes === "Change Targets") {
        return false;
      } else if (this.changes === "Change Type of Hiring") {
        return false;
      } else if (this.changes === "Change Category") {
        return false;
      } else {
        return false;
      }
    },
    isDateDisabled() {
      if (this.changes === "Change Targets") {
        return true;
      } else if (this.changes === "Change Dates") {
        return false;
      } else if (this.changes === "Change Type of Hiring") {
        return false;
      } else if (this.changes === "Change Category") {
        return false;
      } else {
        return false;
      }
    },
    isCategoryDisabled() {
      if (this.changes === "Change Type of Hiring") {
        return true;
      } else if (this.changes === "Change Dates") {
        return true;
      } else if (this.changes === "Change Category") {
        return false;
      } else if (this.changes === "Change Targets") {
        return true;
      } else {
        return true;
      }
    },
    isTypeOfHiringDisabled() {
      if (this.changes === "Change Category") {
        return true;
      } else if (this.changes === "Change Dates") {
        return true;
      } else if (this.changes === "Change Type of Hiring") {
        return false;
      } else if (this.changes === "Change Targets") {
        return true;
      } else {
        return true;
      }
    },
    total_target_computed() {
      const external = parseInt(this.external_target) || 0;
      const internal = parseInt(this.internal_target) || 0;
      return (external + internal).toFixed();
    },
    notice_days_computed() {
      const osd = Date.parse(this.agreed_start_date) || 0;
      const wrd = Date.parse(this.wfm_date_requested) || 0;
      return Math.round((osd - wrd) / (24 * 60 * 60 * 1000));
    },
    notice_weeks() {
      return parseFloat(this.notice_days / 7).toFixed(1);
    },
    show_ups_total_computed() {
      const show_ups_internal = parseInt(this.show_ups_internal) || 0;
      const show_ups_external = parseInt(this.show_ups_external) || 0;
      return (show_ups_internal + show_ups_external).toFixed();
    },
    deficit_computed() {
      const targetValue = Number(parseFloat(this.total_target));
      const showUpsValue = Number(parseFloat(this.show_ups_total));

      if (isNaN(targetValue) || isNaN(showUpsValue)) {
        return 0;
      }

      if (targetValue - showUpsValue < 0) {
        return 0;
      } else {
        return targetValue - showUpsValue;
      }
    },
    percentage_computed() {
      const total_target = Number(parseInt(this.total_target)) || 0;
      const show_ups_total = Number(parseInt(this.show_ups_total)) || 0;

      if (total_target === 0) {
        return "0%";
      }

      const percentage = (show_ups_total / total_target) * 100;
      return percentage.toFixed(2) + "%";
    },
    total_endorsed_computed() {
      const days = [
        parseInt(this.day_1) || 0,
        parseInt(this.day_2) || 0,
        parseInt(this.day_3) || 0,
        parseInt(this.day_4) || 0,
        parseInt(this.day_5) || 0,
        parseInt(this.day_6) || 0,
      ];

      return days.reduce((total, day) => total + day, 0).toFixed();
    },
    classes_number_computed() {
      const target = this.total_target;

      if (target % 15 > 1) {
        return Math.floor(target / 15) + 1;
      } else {
        return Math.floor(target / 15);
      }
    },
    over_hires_computed() {
      const difference = this.show_ups_total - this.total_target;
      if (difference < 0) {
        return 0;
      } else {
        return difference;
      }
    },
    pipeline_total_computed() {
      const pipeline = [
        parseInt(this.internals_hires) || 0,
        parseInt(this.externals_hires) || 0,
        parseInt(this.with_jo) || 0,
        parseInt(this.pending_jo) || 0,
        parseInt(this.pending_berlitz) || 0,
        parseInt(this.pending_ov) || 0,
        parseInt(this.pending_pre_emps) || 0,
      ];

      return pipeline
        .reduce((total, pipeline) => total + pipeline, 0)
        .toFixed();
    },
    capstart_computed() {
      const show_ups_total = this.show_ups_total;
      const total_target = this.total_target;

      return show_ups_total > total_target ? total_target : show_ups_total || 0;
    },
    pipeline_computed() {
      const internals_hires = this.internals_hires;
      const with_jo = this.with_jo;
      const pending_jo = this.pending_jo;
      const pending_berlitz = this.pending_berlitz;
      const pending_ov = this.pending_ov;
      const pending_pre_emps = this.pending_pre_emps;
      const additional_remarks = this.additional_remarks;

      let result = "";

      if (internals_hires !== "" && internals_hires !== 0) {
        result += `${internals_hires} Internals ; `;
      }

      if (with_jo !== "" && with_jo !== 0) {
        result += `${with_jo} With JO ; `;
      }

      if (pending_jo !== "" && pending_jo !== 0) {
        result += `${pending_jo} Pending JO ; `;
      }

      if (pending_berlitz !== "" && pending_berlitz !== 0) {
        result += `${pending_berlitz} Pending Berlitz ; `;
      }

      if (pending_ov !== "" && pending_ov !== 0) {
        result += `${pending_ov} Pending OV ; `;
      }

      if (pending_pre_emps !== "" && pending_pre_emps !== 0) {
        result += `${pending_pre_emps} Pending Pre Emps ; `;
      }

      if (additional_remarks !== "" && additional_remarks !== "") {
        result += `${additional_remarks} Additional Remarks ; `;
      }

      return result.trim();
    },
    all_internals_hires_computed() {
      if (
        this.show_ups_internal >= this.total_target &&
        (this.deficit === "" || this.deficit === 0)
      ) {
        return 1;
      } else {
        return 0;
      }
    },
    deficit_total_computed() {
      const total_target = this.total_target;
      const show_ups_total = this.show_ups_total;
      const internals_hires = this.internals_hires;
      const with_jo = this.with_jo;

      const minValue = Math.min(
        total_target,
        show_ups_total + internals_hires + with_jo
      );
      const result = total_target - minValue;

      return result;
    },

    pipeline_target_computed() {
      const pipeline_total = this.pipeline_total;
      const total_target = this.total_target;

      const result =
        pipeline_total > total_target ? total_target : pipeline_total;

      return result;
    },
    filled_computed() {
      const modResult = this.total_endorsed % 15;
      if (modResult > 1) {
        return Math.floor(this.total_endorsed / 15) + 1;
      } else {
        return Math.floor(this.total_endorsed / 15);
      }
    },
    open_computed() {
      const result = this.classes_number - this.filled;
      return result < 0 ? 0 : result;
    },
  },
  mounted() {
    console.log("Component mounted.");
    this.getSites();
    this.getPrograms();
    this.getDateRange();
    this.getClasses();
    this.getTransaction();
    this.getClassesStaffing();
  },
  watch: {
    agreed_start_date: {
      handler: "getDateRange",
      immediate: true,
    },
    training_start: {
      handler: "getDateRange",
      immediate: true,
    },
    day_1: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day_2: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day_3: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day_4: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day_5: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    day_6: {
      handler: "syncEndorsedTotal",
      immediate: true,
    },
    total_target: {
      handler: "syncDeficitTotal",
      immediate: true,
    },
    show_ups_total: {
      handler: "syncDeficitTotal",
      immediate: true,
    },
    internals_hires: {
      handler: "syncPipelineTotal",
      immediate: true,
    },
    externals_hires: {
      handler: "syncPipelineTotal",
      immediate: true,
    },
    with_jo: {
      handler: "syncPipelineTotal",
      immediate: true,
    },
    pending_jo: {
      handler: "syncPipelineTotal",
      immediate: true,
    },
    pending_berlitz: {
      handler: "syncPipelineTotal",
      immediate: true,
    },
    pending_pre_emps: {
      handler: "syncPipelineTotal",
      immediate: true,
    },
    additional_remarks: {
      handler: "syncPipelineTotal",
      immediate: true,
    },
    pipeline_total: {
      handler: "syncPipelineTarget",
      immediate: true,
    },
    total_endorsed: {
      handler: "syncPipelineTarget",
      immediate: true,
    },
    open: {
      handler: "syncOpen",
      immediate: true,
    },
  },
  methods: {
    syncShowUpsTotal() {
      this.show_ups_total = this.show_ups_total_computed;
      this.internals_hires_all = this.all_internals_hires_computed;
    },
    syncDeficitTotal() {
      this.deficit = this.deficit_computed;
      this.percentage = this.percentage_computed;
      this.classes_number = this.classes_number_computed;
      this.over_hires = this.over_hires_computed;
      this.cap_starts = this.capstart_computed;
      this.internals_hires_all = this.all_internals_hires_computed;
      this.deficit_total = this.deficit_total_computed;
      this.pipeline_target = this.pipeline_target_computed;
      this.open = this.open_computed;
    },
    syncEndorsedTotal() {
      this.total_endorsed = this.total_endorsed_computed;
      this.open = this.filled_computed;
      this.filled = this.filled_computed;
    },
    syncPipelineTotal() {
      this.pipeline_total = this.pipeline_total_computed;
      this.pipeline = this.pipeline_computed;
      this.deficit_total = this.deficit_total_computed;
      this.filled = this.filled_computed;
    },
    syncPipelineTarget() {
      this.pipeline_target = this.pipeline_target_computed;
      this.deficit_total = this.deficit_total_computed;
      this.filled = this.filled_computed;
    },
    syncOpen() {
      this.open = this.open_computed;
      this.filled = this.filled_computed;
    },
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
      console.log(this.programs_selected);
      try {
        const token = this.$store.state.token;
        const headers = {
          Authorization: `Bearer ${token}`,
        };

        const response = await axios.get(
          "http://127.0.0.1:8000/api/programs",
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
      console.log(this.agreed_start_date);
      try {
        const token = this.$store.state.token;
        const headers = {
          Authorization: `Bearer ${token}`,
        };

        const response = await axios.get(
          "http://127.0.0.1:8000/api/daterangeall",
          { headers }
        );

        if (response.status === 200) {
          this.daterange = response.data.data;
          console.log(response.data.data);

          for (let i = 0; i < this.daterange.length; i++) {
            const range = this.daterange[i];
            if (
              this.agreed_start_date >= range.week_start &&
              this.agreed_start_date <= range.week_end
            ) {
              this.date_selected = range.id;
              break;
            }
          }
        } else {
          console.log("Error fetching date range");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getClasses() {
      try {
        const token = this.$store.state.token;
        const headers = {
          Authorization: `Bearer ${token}`,
        };

        const response = await axios.get(
          `http://127.0.0.1:8000/api/classes/${this.$route.params.id}`,
          { headers }
        );

        if (response.status === 200) {
          const data = response.data;
          const classObj = data.class;
          const nestedRequestedByArray = JSON.parse(classObj.requested_by);

          // Ensure requestedByArray is always an array
          let requestedByArray = Array.isArray(nestedRequestedByArray)
            ? nestedRequestedByArray.flat()
            : [nestedRequestedByArray];

          console.log("Parsed requested_by array:", requestedByArray);

          // Assign the concatenated string to requested_by property
          this.sites_selected = classObj.site.id;
          this.programs_selected = classObj.program.id;
          this.type_of_hiring = classObj.type_of_hiring;
          this.external_target = classObj.external_target;
          this.internal_target = classObj.internal_target;
          this.total_target = classObj.total_target;
          this.original_start_date = classObj.original_start_date;
          this.notice_days = classObj.notice_days;
          this.notice_weeks = classObj.notice_weeks;
          this.date_selected = classObj.date_range.id;
          this.with_erf = classObj.with_erf;
          this.category = classObj.category;
          this.within_sla = classObj.within_sla;
          this.agreed_start_date = classObj.agreed_start_date;
          this.erf_number = classObj.erf_number;
          this.approved_by = classObj.approved_by;
          this.wfm_date_requested = classObj.wfm_date_requested;
          this.remarks = classObj.remarks;
          this.ta = classObj.ta;
          this.wf = classObj.wf;
          this.tr = classObj.tr;
          this.cl = classObj.cl;
          this.op = classObj.op;
          this.requested_by = requestedByArray;

          this.wave_no = classObj.wave_no;

          console.log(classObj);
        } else {
          console.log("Error fetching classes");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getClassesStaffing() {
      try {
        const token = this.$store.state.token;
        const id = this.$route.params.id;

        const response = await axios.get(
          `http://127.0.0.1:8000/api/classesstaffing/${id}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        const classStaffingObj = response.data.class;

        this.day_1 = classStaffingObj.day_1;
        this.day_2 = classStaffingObj.day_2;
        this.day_3 = classStaffingObj.day_3;
        this.day_4 = classStaffingObj.day_4;
        this.day_5 = classStaffingObj.day_5;
        this.day_6 = classStaffingObj.day_6;
        this.total_endorsed = classStaffingObj.total_endorsed;
        this.show_ups_internal = classStaffingObj.show_ups_internal;
        this.show_ups_external = classStaffingObj.show_ups_external;
        this.show_ups_total = classStaffingObj.show_ups_total;
        this.deficit = classStaffingObj.deficit;
        this.percentage = classStaffingObj.percentage;
        this.status = classStaffingObj.status;
        this.internals_hires = classStaffingObj.internals_hires;
        this.externals_hires = classStaffingObj.externals_hires;
        this.additional_extended_jo = classStaffingObj.additional_extended_jo;
        this.with_jo = classStaffingObj.with_jo;
        this.pending_jo = classStaffingObj.pending_jo;
        this.pending_berlitz = classStaffingObj.pending_berlitz;
        this.pending_ov = classStaffingObj.pending_ov;
        this.pending_pre_emps = classStaffingObj.pending_pre_emps;
        this.classes_number = classStaffingObj.classes_number;
        this.pipeline_total = classStaffingObj.pipeline_total;
        this.cap_starts = classStaffingObj.cap_starts;
        this.internals_hires_all = classStaffingObj.internals_hires_all;
        this.externals_hires_all = classStaffingObj.externals_hires_all;
        this.pipeline_target = classStaffingObj.pipeline_target;
        this.class_selected = classStaffingObj.classes_id;
        this.pipeline = classStaffingObj.pipeline;
        this.over_hires = classStaffingObj.over_hires;
        this.additional_remarks = classStaffingObj.additional_remarks;

        console.log("id moto" + this.class_selected);
      } catch (error) {
        console.log(error);
      }
    },
    async getTransaction() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "http://127.0.0.1:8000/api/transaction/" + this.$route.params.id,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        this.classes = response.data.classes;
        console.log(response.data.classes);
      } catch (error) {
        console.log(error);
      }
    },

    pushClass() {
      // Filter out any null values from requested_by array
      const filtered_requested_by = this.requested_by.filter(
        (value) => value !== null
      );

      // Convert requested_by array to JSON string with required format
      const requested_by_string = JSON.stringify([filtered_requested_by]);
      this.loading = true;

      // Determine the status based on changes
      let status;
      if (
        this.changes === "Change Dates" ||
        this.changes === "Change Targets" ||
        this.changes === "Change Targets and Dates"
      ) {
        status = "Moved";
      } else if (
        this.changes === "Change Type of Hiring" ||
        this.changes === "Change Category"
      ) {
        status = "Changed";
      } else {
        status = "Active"; // Default status if none of the conditions are met
      }

      // Other form data
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
        category: this.category,
        original_start_date: this.original_start_date,
        wfm_date_requested: this.wfm_date_requested,
        within_sla: this.within_sla,
        remarks: this.remarks,
        requested_by: requested_by_string,
        erf_number: this.erf_number,
        date_range_id: this.date_selected,
        approved_status: "pending",
        status: status,
        updated_by: this.$store.state.user_id,
        agreed_start_date: this.agreed_start_date,
        changes: this.changes,
        approved_by: this.approved_by,
        ta: this.ta,
        wf: this.wf,
        tr: this.tr,
        cl: this.cl,
        op: this.op,
        day_1: this.day_1,
        day_2: this.day_2,
        day_3: this.day_3,
        day_4: this.day_4,
        day_5: this.day_5,
        open: this.open,
        filled: this.filled,
        total_endorsed: this.total_endorsed,
        show_ups_internal: this.show_ups_internal,
        show_ups_external: this.show_ups_external,
        show_ups_total: this.show_ups_total,
        deficit: this.deficit,
        percentage: this.percentage,
        // status: this.status,
        internals_hires: this.internals_hires,
        externals_hires: this.externals_hires,
        additional_extended_jo: this.additional_extended_jo,
        with_jo: this.with_jo,
        pending_jo: this.pending_jo,
        pending_berlitz: this.pending_berlitz,
        pending_ov: this.pending_ov,
        pending_pre_emps: this.pending_pre_emps,
        classes_number: this.classes_number,
        pipeline_total: this.pipeline_total,
        cap_starts: this.cap_starts,
        internals_hires_all: this.internals_hires_all,
        externals_hires_all: this.externals_hires_all,
        pipeline_target: this.pipeline_target,
        pipeline: this.pipeline,
        over_hires: this.over_hires,
        additional_remarks: this.additional_remarks,
        classes_id: this.class_selected,
      };

      const token = this.$store.state.token;

      axios
        .put(
          "http://127.0.0.1:8000/api/classes/pushedback/" +
            this.$route.params.id,
          formData,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        )
        .then((response) => {
          console.log(response.data);
          this.resetForm();
          this.$router.push("/capfile", () => {
            location.reload();
          });
        })
        .catch((error) => {
          console.log(error.response.data);
        })
        .finally(() => {
          this.loading = false;
        });
    },

    resetForm() {
      this.sites_selected = "";
      this.programs_selected = "";
      this.type_of_hiring = "";
      this.external_target = "";
      this.internal_target = "";
      this.total_target = 0;
      this.notice_days = 0;
      this.notice_weeks = "";
      this.with_erf = "";
      this.erf_number = "";
      this.category = "";
      this.original_start_date = "";
      this.wfm_date_requested = "";
      this.within_sla = "";
      this.remarks = "";
      this.requested_by = [];
      this.date_selected = "";
      this.approved_status = "";
      this.updated_by = "";
      this.agreed_start_date = "";
      this.approved_by = "";
      this.changes = "Change Targets and Dates";
      this.ta = "";
      this.wf = "";
      this.tr = "";
      this.cl = "";
      this.op = "";
      this.day_1 = "";
      this.day_2 = "";
      this.day_3 = "";
      this.day_4 = "";
      this.day_5 = "";
      this.open = "";
      this.filled = "";
      this.total_endorsed = "";
      this.show_ups_internal = "";
      this.show_ups_external = "";
      this.show_ups_total = "";
      this.deficit = "";
      this.percentage = "";
      this.status = "";
      this.internals_hires = "";
      this.externals_hires = "";
      this.additional_extended_jo = "";
      this.with_jo = "";
      this.pending_jo = "";
      this.pending_berlitz = "";
      this.pending_ov = "";
      this.pending_pre_emps = "";
      this.classes_number = "";
      this.classes_id = "";
      this.pipeline_total = "";
      this.cap_starts = "";
      this.internals_hires_all = "";
      this.all_externals_hires = "";
      this.pipeline_target = "";
      this.pipeline = "";
      this.over_hires = "";
      this.additional_remarks = "";
    },
  },
};
</script>
<style scoped>
/* Your loader styles here */
.loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* Ensure the loader is on top of other elements */
}

.loader-content {
  /* Style your loader content (SVG, text, etc.) */
  display: flex;
  align-items: center;
}

.icon {
  /* Style your SVG icon */
  height: 3rem; /* Adjust the size as needed */
  width: 3rem; /* Adjust the size as needed */
  animation: spin 1s linear infinite;
  stroke: rgba(107, 114, 128, 1);
}

.loading-text {
  /* Style your loading text */
  font-size: 1.5rem; /* Adjust the size as needed */
  line-height: 2rem; /* Adjust the size as needed */
  font-weight: 500;
  color: rgba(107, 114, 128, 1);
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
