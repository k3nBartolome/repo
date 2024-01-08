<template>
  <div v-if="isLoading" class="loader">
    <div aria-label="Loading..." role="status" class="loader">
      <svg class="icon" viewBox="0 0 256 256">
        <line x1="128" y1="32" x2="128" y2="64" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"></line>
        <line x1="195.9" y1="60.1" x2="173.3" y2="82.7" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"></line>
        <line x1="224" y1="128" x2="192" y2="128" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"></line>
        <line x1="195.9" y1="195.9" x2="173.3" y2="173.3" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"></line>
        <line x1="128" y1="224" x2="128" y2="192" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"></line>
        <line x1="60.1" y1="195.9" x2="82.7" y2="173.3" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"></line>
        <line x1="32" y1="128" x2="64" y2="128" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"></line>
        <line x1="60.1" y1="60.1" x2="82.7" y2="82.7" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"></line>
      </svg>
      <span class="loading-text">Loading...</span>
    </div>
  </div>
  <div class="py-0">
    <div class="px-4 sm:px-6 lg:px-8 flex items-center justify-between">
      <div class="flex items-center">
        <button
          @click="exportToExcel"
          class="bg-green-600 text-white px-3 py-2 rounded-md hover:bg-orange-700 transition duration-300 ease-in-out mr-2"
        >
          Export
        </button>
        <button
          @click="toggleAll"
          class="px-3 py-2 rounded-md transition duration-300 ease-in-out"
          :class="{
            'bg-red-600 text-white': showJanColumn,
            'bg-blue-500 text-white': !showJanColumn,
          }"
        >
          {{ showJanColumn ? "Swith to Monthly View" : "Switch to Weekly View" }}
        </button>
      </div>
      <div class="grid grid-cols-1 gap-2 md:grid-cols-2 py-2 ml-4 items-center">
        <div>
          <label class="block mb-2">Sites</label>
          <MultiSelect
            v-model="sites_selected"
            :options="sites"
            filter
            optionLabel="name"
            placeholder="Select Sites"
            class="w-full p-2 border border-gray-300 rounded-md md:w-60 focus:ring focus:ring-orange-500 focus:ring-opacity-50 hover:border-orange-500 hover:ring hover:ring-orange-500 hover:ring-opacity-50 transition-all duration-300 ease-in-out"
            :selected-items-class="'bg-orange-500 text-white'"
            :panel-style="{
              backgroundColor: 'white',
              borderRadius: '5px',
              boxShadow: '0 2px 5px rgba(0, 0, 0, 0.15)',
            }"
            :panel-class="'border border-gray-300 rounded-md shadow-md text-black'"
            :filter-placeholder="'Search...'"
            :filter-input-class="'p-2 border border-gray-300 rounded-md focus:ring focus:ring-orange-500 focus:ring-opacity-50 hover:border-orange-500 hover:ring hover:ring-orange-500 hover:ring-opacity-50 transition-all duration-300 ease-in-out'"
            :item-template="'<span class=\'custom-item\'>{{item.name}}</span>'"
            @change="getPrograms"
          />
        </div>
        <div>
          <label class="block mb-2">Programs</label>
          <MultiSelect
            v-model="programs_selected"
            :options="programs"
            filter
            optionLabel="name"
            placeholder="Select Programs"
            class="w-full p-2 border border-gray-300 rounded-md md:w-60 focus:ring focus:ring-orange-500 focus:ring-opacity-50 hover:border-orange-500 hover:ring hover:ring-orange-500 hover:ring-opacity-50 transition-all duration-300 ease-in-out"
            :selected-items-class="'bg-orange-500 text-white'"
            :panel-style="{
              backgroundColor: 'white',
              borderRadius: '5px',
              boxShadow: '0 2px 5px rgba(0, 0, 0, 0.15)',
            }"
            :panel-class="'border border-gray-300 rounded-md shadow-md text-black'"
            :filter-placeholder="'Search...'"
            :filter-input-class="'p-2 border border-gray-300 rounded-md focus:ring focus:ring-orange-500 focus:ring-opacity-50 hover:border-orange-500 hover:ring hover:ring-orange-500 hover:ring-opacity-50 transition-all duration-300 ease-in-out'"
            :item-template="'<span class=\'custom-item\'>{{item.name}}</span>'"
          />
        </div>
      </div>
    </div>
  </div>

  <div class="px-2 overflow-x-auto overflow-y-auto">
    <div class="p-4">
      <div
        class="bg-white shadow-md rounded-lg overflow-x-auto overflow-y-auto"
      >
        <table class="min-w-full border-collapse border-2 border-gray-300">
          <thead class="">
            <tr class="border-4 border-gray-300 px-1 text-center">
              <th
                class="border-2 border-gray-300 px-1"
                rowspan="3"
                style="vertical-align: middle"
              >
                Site Name
              </th>
              <th
                class="border-2 border-gray-300 px-1"
                style="vertical-align: middle"
                rowspan="3"
              >
                Program Name
              </th>
              <th
                :colspan="showJanColumn ? 5 : 1"
                :rowspan="showJanColumn ? 1 : 3"
                :class="{
                  'bg-red-500': !showJanColumn,
                  'bg-blue-500': showJanColumn,
                  border: true,
                  'border-gray-200': true,
                  'sm:border-none': true,
                }"
                @click="toggleColumnVisibility('Jan')"
              >
                Jan
              </th>
              <th
                :colspan="showFebColumn ? 5 : 1"
                :rowspan="showFebColumn ? 1 : 3"
                :class="{
                  'bg-red-500': !showFebColumn,
                  'bg-blue-500': showFebColumn,
                  border: true,
                  'border-gray-200': true,
                  'sm:border-none': true,
                }"
                @click="toggleColumnVisibility('Feb')"
              >
                Feb
              </th>
              <th
                :colspan="showMarColumn ? 5 : 1"
                :rowspan="showMarColumn ? 1 : 3"
                :class="{
                  'bg-red-500': !showMarColumn,
                  'bg-blue-500': showMarColumn,
                  border: true,
                  'border-gray-200': true,
                  'sm:border-none': true,
                }"
                @click="toggleColumnVisibility('Mar')"
              >
                Mar
              </th>
              <th
                :colspan="showAprColumn ? 6 : 1"
                :rowspan="showAprColumn ? 1 : 3"
                :class="{
                  'bg-red-500': !showAprColumn,
                  'bg-blue-500': showAprColumn,
                  border: true,
                  'border-gray-200': true,
                  'sm:border-none': true,
                }"
                @click="toggleColumnVisibility('Apr')"
              >
                Apr
              </th>
              <th
                :colspan="showMayColumn ? 5 : 1"
                :rowspan="showMayColumn ? 1 : 3"
                :class="{
                  'bg-red-500': !showMayColumn,
                  'bg-blue-500': showMayColumn,
                  border: true,
                  'border-gray-200': true,
                  'sm:border-none': true,
                }"
                @click="toggleColumnVisibility('May')"
              >
                May
              </th>
              <th
                :colspan="showJunColumn ? 5 : 1"
                :rowspan="showJunColumn ? 1 : 3"
                :class="{
                  'bg-red-500': !showJunColumn,
                  'bg-blue-500': showJunColumn,
                  border: true,
                  'border-gray-200': true,
                  'sm:border-none': true,
                }"
                @click="toggleColumnVisibility('Jun')"
              >
                Jun
              </th>
              <th
                :colspan="showJulColumn ? 6 : 1"
                :rowspan="showJulColumn ? 1 : 3"
                :class="{
                  'bg-red-500': !showJulColumn,
                  'bg-blue-500': showJulColumn,
                  border: true,
                  'border-gray-200': true,
                  'sm:border-none': true,
                }"
                @click="toggleColumnVisibility('Jul')"
              >
                Jul
              </th>
              <th
                :colspan="showAugColumn ? 5 : 1"
                :rowspan="showAugColumn ? 1 : 3"
                :class="{
                  'bg-red-500': !showAugColumn,
                  'bg-blue-500': showAugColumn,
                  border: true,
                  'border-gray-200': true,
                  'sm:border-none': true,
                }"
                @click="toggleColumnVisibility('Aug')"
              >
                Aug
              </th>
              <th
                :colspan="showSepColumn ? 6 : 1"
                :rowspan="showSepColumn ? 1 : 3"
                :class="{
                  'bg-red-500': !showSepColumn,
                  'bg-blue-500': showSepColumn,
                  border: true,
                  'border-gray-200': true,
                  'sm:border-none': true,
                }"
                @click="toggleColumnVisibility('Sep')"
              >
                Sep
              </th>
              <th
                :colspan="showOctColumn ? 5 : 1"
                :rowspan="showOctColumn ? 1 : 3"
                :class="{
                  'bg-red-500': !showOctColumn,
                  'bg-blue-500': showOctColumn,
                  border: true,
                  'border-gray-200': true,
                  'sm:border-none': true,
                }"
                @click="toggleColumnVisibility('Oct')"
              >
                Oct
              </th>
              <th
                :colspan="showNovColumn ? 5 : 1"
                :rowspan="showNovColumn ? 1 : 3"
                :class="{
                  'bg-red-500': !showNovColumn,
                  'bg-blue-500': showNovColumn,
                  border: true,
                  'border-gray-200': true,
                  'sm:border-none': true,
                }"
                @click="toggleColumnVisibility('Nov')"
              >
                Nov
              </th>
              <th
                :colspan="showDecColumn ? 6 : 1"
                :rowspan="showDecColumn ? 1 : 3"
                :class="{
                  'bg-red-500': !showDecColumn,
                  'bg-blue-500': showDecColumn,
                  border: true,
                  'border-gray-200': true,
                  'sm:border-none': true,
                }"
                @click="toggleColumnVisibility('Dec')"
              >
                Dec
              </th>
              <th
                class="border-4 border-gray-300 px-1"
                style="vertical-align: middle"
                rowspan="3"
              >
                Total
              </th>
            </tr>
            <tr class="border-4 border-gray-300 px-1 text-center">
              <th class="border-2 border-gray-300 px-1" v-if="showJanColumn">
                Jan 1
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJanColumn">
                Jan 8
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJanColumn">
                Jan 15
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJanColumn">
                Jan 22
              </th>
              <th
                class="border-2 border-gray-300 px-1"
                style="vertical-align: middle"
                rowspan="2"
                v-if="showJanColumn"
              >
                Jan
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showFebColumn">
                Jan 29
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showFebColumn">
                Feb 5
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showFebColumn">
                Feb 12
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showFebColumn">
                Feb 19
              </th>
              <th
                class="border-2 border-gray-300 px-1"
                style="vertical-align: middle"
                rowspan="2"
                v-if="showFebColumn"
              >
                Feb
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMarColumn">
                Feb 26
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMarColumn">
                Mar 5
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMarColumn">
                Mar 12
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMarColumn">
                Mar 19
              </th>
              <th
                class="border-2 border-gray-300 px-1"
                style="vertical-align: middle"
                rowspan="2"
                v-if="showMarColumn"
              >
                Mar
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAprColumn">
                Mar 26
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAprColumn">
                Apr 2
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAprColumn">
                Apr 9
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAprColumn">
                Apr 16
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAprColumn">
                Apr 23
              </th>
              <th
                class="border-2 border-gray-300 px-1"
                style="vertical-align: middle"
                rowspan="2"
                v-if="showAprColumn"
              >
                Apr
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMayColumn">
                Apr 30
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMayColumn">
                May 7
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMayColumn">
                May 14
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMayColumn">
                May 21
              </th>
              <th
                class="border-2 border-gray-300 px-1"
                style="vertical-align: middle"
                rowspan="2"
                v-if="showMayColumn"
              >
                May
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJunColumn">
                May 28
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJunColumn">
                Jun 4
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJunColumn">
                Jun 11
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJunColumn">
                Jun 18
              </th>
              <th
                class="border-2 border-gray-300 px-1"
                style="vertical-align: middle"
                rowspan="2"
                v-if="showJunColumn"
              >
                Jun
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJulColumn">
                Jun 25
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJulColumn">
                Jul 2
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJulColumn">
                Jul 9
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJulColumn">
                Jul 16
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJulColumn">
                Jul 23
              </th>
              <th
                class="border-2 border-gray-300 px-1"
                style="vertical-align: middle"
                rowspan="2"
                v-if="showJulColumn"
              >
                Jul
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAugColumn">
                Jul 30
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAugColumn">
                Aug 6
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAugColumn">
                Aug 13
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAugColumn">
                Aug 20
              </th>
              <th
                class="border-2 border-gray-300 px-1"
                style="vertical-align: middle"
                rowspan="2"
                v-if="showAugColumn"
              >
                Aug
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showSepColumn">
                Aug 27
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showSepColumn">
                Sep 3
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showSepColumn">
                Sep 10
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showSepColumn">
                Sep 17
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showSepColumn">
                Sep 24
              </th>
              <th
                class="border-2 border-gray-300 px-1"
                style="vertical-align: middle"
                rowspan="2"
                v-if="showSepColumn"
              >
                Sep
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showOctColumn">
                Oct 1
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showOctColumn">
                Oct 8
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showOctColumn">
                Oct 15
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showOctColumn">
                Oct 22
              </th>
              <th
                class="border-2 border-gray-300 px-1"
                style="vertical-align: middle"
                rowspan="2"
                v-if="showOctColumn"
              >
                Oct
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showNovColumn">
                Oct 29
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showNovColumn">
                Nov 5
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showNovColumn">
                Nov 12
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showNovColumn">
                Nov 19
              </th>
              <th
                class="border-2 border-gray-300 px-1"
                style="vertical-align: middle"
                rowspan="2"
                v-if="showNovColumn"
              >
                Nov
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showDecColumn">
                Nov 26
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showDecColumn">
                Dec 3
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showDecColumn">
                Dec 10
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showDecColumn">
                Dec 17
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showDecColumn">
                Dec 24
              </th>
              <th
                class="border-2 border-gray-300 px-1"
                style="vertical-align: middle"
                rowspan="2"
                v-if="showDecColumn"
              >
                Dec
              </th>
            </tr>
            <tr class="border-4 border-gray-300 px-1 text-center">
              <th class="border-2 border-gray-300 px-1" v-if="showJanColumn">
                Jan 7
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJanColumn">
                Jan 14
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJanColumn">
                Jan 21
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJanColumn">
                Jan 28
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showFebColumn">
                Feb 4
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showFebColumn">
                Feb 11
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showFebColumn">
                Feb 18
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showFebColumn">
                Feb 25
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMarColumn">
                Mar 4
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMarColumn">
                Mar 11
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMarColumn">
                Mar 18
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMarColumn">
                Mar 25
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAprColumn">
                Apr 1
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAprColumn">
                Apr 8
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAprColumn">
                Apr 15
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAprColumn">
                Apr 22
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAprColumn">
                Apr 29
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMayColumn">
                May 6
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMayColumn">
                May 13
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMayColumn">
                May 20
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showMayColumn">
                May 27
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJunColumn">
                Jun 3
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJunColumn">
                Jun 10
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJunColumn">
                Jun 17
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJunColumn">
                Jun 24
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJulColumn">
                Jul 1
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJulColumn">
                Jul 8
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJulColumn">
                Jul 15
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJulColumn">
                Jul 22
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showJulColumn">
                Jul 29
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAugColumn">
                Aug 5
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAugColumn">
                Aug 12
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAugColumn">
                Aug 19
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showAugColumn">
                Aug 26
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showSepColumn">
                Sep 2
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showSepColumn">
                Sep 9
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showSepColumn">
                Sep 16
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showSepColumn">
                Sep 23
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showSepColumn">
                Sep 30
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showOctColumn">
                Oct 7
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showOctColumn">
                Oct 14
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showOctColumn">
                Oct 21
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showOctColumn">
                Oct 28
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showNovColumn">
                Nov 4
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showNovColumn">
                Nov 11
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showNovColumn">
                Nov 18
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showNovColumn">
                Nov 25
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showDecColumn">
                Dec 2
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showDecColumn">
                Dec 9
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showDecColumn">
                Dec 16
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showDecColumn">
                Dec 23
              </th>
              <th class="border-2 border-gray-300 px-1" v-if="showDecColumn">
                Dec 30
              </th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(item, index) in classes" :key="index">
              <tr>
                <td
                  class="border-4 border-gray-300 truncate px-2 font-semibold"
                >
                  {{ item.Site }}
                </td>
                <td
                  class="border-4 border-gray-300 truncate px-2 font-semibold"
                >
                  {{ item.Program }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showJanColumn"
                >
                  {{ item.Week1 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showJanColumn"
                >
                  {{ item.Week2 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showJanColumn"
                >
                  {{ item.Week3 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showJanColumn"
                >
                  {{ item.Week4 }}
                </td>
                <td class="border-2 border-gray-300 text-center font-semibold">
                  {{ item.Jan }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showFebColumn"
                >
                  {{ item.Week5 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showFebColumn"
                >
                  {{ item.Week6 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showFebColumn"
                >
                  {{ item.Week7 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showFebColumn"
                >
                  {{ item.Week8 }}
                </td>
                <td class="border-2 border-gray-300 text-center font-semibold">
                  {{ item.Feb }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showMarColumn"
                >
                  {{ item.Week9 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showMarColumn"
                >
                  {{ item.Week10 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showMarColumn"
                >
                  {{ item.Week11 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showMarColumn"
                >
                  {{ item.Week12 }}
                </td>
                <td class="border-2 border-gray-300 text-center font-semibold">
                  {{ item.Mar }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showAprColumn"
                >
                  {{ item.Week13 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showAprColumn"
                >
                  {{ item.Week14 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showAprColumn"
                >
                  {{ item.Week15 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showAprColumn"
                >
                  {{ item.Week16 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showAprColumn"
                >
                  {{ item.Week17 }}
                </td>
                <td class="border-2 border-gray-300 text-center font-semibold">
                  {{ item.Apr }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showMayColumn"
                >
                  {{ item.Week18 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showMayColumn"
                >
                  {{ item.Week19 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showMayColumn"
                >
                  {{ item.Week20 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showMayColumn"
                >
                  {{ item.Week21 }}
                </td>
                <td class="border-2 border-gray-300 text-center font-semibold">
                  {{ item.May }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showJunColumn"
                >
                  {{ item.Week22 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showJunColumn"
                >
                  {{ item.Week23 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showJunColumn"
                >
                  {{ item.Week24 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showJunColumn"
                >
                  {{ item.Week25 }}
                </td>
                <td class="border-2 border-gray-300 text-center font-semibold">
                  {{ item.Jun }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showJulColumn"
                >
                  {{ item.Week26 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showJulColumn"
                >
                  {{ item.Week27 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showJulColumn"
                >
                  {{ item.Week28 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showJulColumn"
                >
                  {{ item.Week29 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showJulColumn"
                >
                  {{ item.Week30 }}
                </td>
                <td class="border-2 border-gray-300 text-center font-semibold">
                  {{ item.Jul }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showAugColumn"
                >
                  {{ item.Week31 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showAugColumn"
                >
                  {{ item.Week32 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showAugColumn"
                >
                  {{ item.Week33 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showAugColumn"
                >
                  {{ item.Week34 }}
                </td>
                <td class="border-2 border-gray-300 text-center font-semibold">
                  {{ item.Aug }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showSepColumn"
                >
                  {{ item.Week35 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showSepColumn"
                >
                  {{ item.Week36 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showSepColumn"
                >
                  {{ item.Week37 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showSepColumn"
                >
                  {{ item.Week38 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showSepColumn"
                >
                  {{ item.Week39 }}
                </td>
                <td class="border-2 border-gray-300 text-center font-semibold">
                  {{ item.Sep }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showOctColumn"
                >
                  {{ item.Week40 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showOctColumn"
                >
                  {{ item.Week41 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showOctColumn"
                >
                  {{ item.Week42 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showOctColumn"
                >
                  {{ item.Week43 }}
                </td>
                <td class="border-2 border-gray-300 text-center font-semibold">
                  {{ item.Oct }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showNovColumn"
                >
                  {{ item.Week44 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showNovColumn"
                >
                  {{ item.Week45 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showNovColumn"
                >
                  {{ item.Week46 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showNovColumn"
                >
                  {{ item.Week47 }}
                </td>
                <td class="border-2 border-gray-300 text-center font-semibold">
                  {{ item.Nov }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showDecColumn"
                >
                  {{ item.Week48 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showDecColumn"
                >
                  {{ item.Week49 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showDecColumn"
                >
                  {{ item.Week50 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showDecColumn"
                >
                  {{ item.Week51 }}
                </td>
                <td
                  class="border-2 border-gray-300 text-center font-semibold"
                  v-if="showDecColumn"
                >
                  {{ item.Week52 }}
                </td>
                <td class="border-2 border-gray-300 text-center font-semibold">
                  {{ item.Dec }}
                </td>
                <td class="border-4 border-gray-300 text-center font-semibold">
                  {{ item.GrandTotalByProgram }}
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import MultiSelect from "primevue/multiselect";

export default {
  components: {
    MultiSelect,
  },
  data() {
    return {
      classes: [],
      grandTotal: [],
      grandTotal2: [],
      programs: [],
      sites: [],
      daterange: [],
      week_selected: [],
      programs_selected: [],
      sites_selected: [],
      month_selected: [],
      months: [],
      showJanColumn: false,
      showFebColumn: false,
      showMarColumn: false,
      showAprColumn: false,
      showMayColumn: false,
      showJunColumn: false,
      showJulColumn: false,
      showAugColumn: false,
      showSepColumn: false,
      showOctColumn: false,
      showNovColumn: false,
      showDecColumn: false,
      showAll: true,
      isLoading: false,
    };
  },
  computed: {},
  mounted() {
    this.getClassesAll();
    this.getSites();
    this.getPrograms();
    this.getDateRange();
    this.getMonth();
  },
  watch: {
    month_selected: {
      handler: "getClassesAll",
      immediate: true,
    },
    week_selected: {
      handler: "getClassesAll",
      immediate: true,
    },
    sites_selected: {
      handler: "getClassesAll",
      immediate: true,
    },
    programs_selected: {
      handler: "getClassesAll",
      immediate: true,
    },
  },
  methods: {
    toggleColumnVisibility(month) {
      if (month === "Jan") this.showJanColumn = !this.showJanColumn;
      else if (month === "Feb") this.showFebColumn = !this.showFebColumn;
      else if (month === "Mar") this.showMarColumn = !this.showMarColumn;
      else if (month === "Apr") this.showAprColumn = !this.showAprColumn;
      else if (month === "May") this.showMayColumn = !this.showMayColumn;
      else if (month === "Jun") this.showJunColumn = !this.showJunColumn;
      else if (month === "Jul") this.showJulColumn = !this.showJulColumn;
      else if (month === "Aug") this.showAugColumn = !this.showAugColumn;
      else if (month === "Sep") this.showSepColumn = !this.showSepColumn;
      else if (month === "Oct") this.showOctColumn = !this.showOctColumn;
      else if (month === "Nov") this.showNovColumn = !this.showNovColumn;
      else if (month === "Dec") this.showDecColumn = !this.showDecColumn;
    },
    toggleAll() {
      const newState = !this.showJanColumn;
      this.showJanColumn = newState;
      this.showFebColumn = newState;
      this.showMarColumn = newState;
      this.showAprColumn = newState;
      this.showMayColumn = newState;
      this.showJunColumn = newState;
      this.showJulColumn = newState;
      this.showAugColumn = newState;
      this.showSepColumn = newState;
      this.showOctColumn = newState;
      this.showNovColumn = newState;
      this.showDecColumn = newState;
    },
    async getClassesAll() {
  try {
    this.isLoading = true; // Set loading state to true

    const token = this.$store.state.token;
    const response = await axios.get(
      "http://127.0.0.1:8000/api/classesdashboard3",
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
        params: {
          month_num: this.month_selected.map(
            (month_num) => month_num.month_num
          ),
          site_id: this.sites_selected.map((site) => site.site_id),
          program_id: this.programs_selected.map(
            (program) => program.program_id
          ),
          date_id: this.week_selected.map(
            (week_selected) => week_selected.date_id
          ),
        },
      }
    );

    if (response.status === 200) {
      this.classes = response.data.classes;
      this.grandTotal = response.data.grandTotal;
      this.grandTotal2 = response.data.grandTotal2;
    } else {
      console.error(
        "Error fetching classes. Status code:",
        response.status
      );
    }
  } catch (error) {
    console.error("An error occurred:", error);
  } finally {
    this.isLoading = false; // Set loading state back to false, regardless of success or failure
  }
},

    async getMonth() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/months", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.months = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching sites");
        }
      } catch (error) {
        console.log(error);
      }
    },
    async exportToExcel() {
      // Set export loading to true before making the request
      try {
        this.isLoading = true;
        const token = this.$store.state.token;

        // Make an API request to trigger the Excel export
        const response = await axios.get("http://127.0.0.1:8000/api/export3", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
          responseType: "blob", // Tell Axios to expect a binary response
          params: {
            // Include any parameters needed for the export

            site_id: this.sites_selected.map((site) => site.site_id),
            program_id: this.programs_selected.map(
              (program) => program.program_id
            ),
          },
        });

        // Create a Blob and create a download link for the Excel file
        const blob = new Blob([response.data], {
          type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "cancelled_classes.xlsx";
        a.click();
      } catch (error) {
        console.error("Error exporting data to Excel", error);
      }
      finally {
    this.isLoading = false; // Set loading state back to false, regardless of success or failure
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
    async getDateRange() {
      if (!this.month_selected) {
        return;
      }
      try {
        const token = this.$store.state.token;
        const monthId = this.month_selected.map((month) => month.month_num);

        const url = `http://127.0.0.1:8000/api/daterange_select/${monthId.join(
          ","
        )}`;

        const response = await axios.get(url, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.daterange = response.data.data;
          console.log(response.data.data);
        } else {
          console.log("Error fetching week");
        }
      } catch (error) {
        console.error(error);
      }
    },
    async getPrograms() {
      if (!this.sites_selected) {
        return;
      }

      try {
        const token = this.$store.state.token;
        const siteId = this.sites_selected.map((site) => site.site_id);

        const url = `http://127.0.0.1:8000/api/programs_select/${siteId.join(
          ","
        )}`;

        const response = await axios.get(url, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

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
  background: rgba(255, 255, 255, 0.8);
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
  width: 3rem;  /* Adjust the size as needed */
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

