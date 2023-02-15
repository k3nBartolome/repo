<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full px-4 py-6 max-w-7xl sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        Capacity File Manager
      </h1>
    </div>
  </header>
  <div class="px-4 py-6 mx-auto bg-gray-200 max-w-7xl sm:px-6 lg:px-8 ">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
      <label class="block">
        Site
        <input
          type="text"
          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
      <label class="block">
        Line of Business
        <input
          type="text"
          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
      <label class="block">
        Type of Hiring
        <input
          type="text"
          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
      <label class="block">
        External Target
        <input
          type="text"
          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
      <label class="block">
        Internal Target
        <input
          type="text"
          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
      <label class="block">
        Total Target
        <input
          type="text"
          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
      <label class="block">
        Delivery Date
        <input
          type="date"
          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
      <label class="block">
        Notice Days
        <input
          type="text"
          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
      <label class="block">
        Notice Weeks
        <input
          type="text"
          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
      <label class="block">
        Pipeline Utilized
        <input
          type="text"
          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
     <label class="block">With ERF?
      <input
          type="text"
        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
     <label class="block">Original Start Date
      <input
          type="date"
        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
     <label class="block">Supposed Start Date
      <input
          type="date"
        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
     <label class="block">WF Requested Start Date
      <input
          type="date"
        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
     <label class="block">TA Committed Start Date
      <input
          type="date"
        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
     <label class="block">WFM Requested Date
      <input
          type="date"
          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
      <label class="block">Reason for Counter Proposal
      <input
          type="text"
          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
      <label class="block">Remarks<input
          type="text"
        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
     <label class="block">Within SLA?<input
          type="text"
        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
    </label>
      <label class="block">Out of SLA Reason
      <input
          type="text"
          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-100"
        />
      </label>
    </div>
  </div>
</template>
