<template>
  <header class="bg-white shadow">
    <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        Site Dashboard
      </h1>
    </div>
  </header>
  <main>
    <div class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
      <!-- Replace with your content -->
      <div class="px-4 py-6 sm:px-0">
        <div class="border-4 border-gray-200 rounded-lg h-96">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Region</th>
                <th>Is Active?</th>
                <th>Created By</th>
                <th>Created Date</th>
                <th>Updated By</th>
                <th>Updated Date</th>
                <th colspan="3">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Show</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- /End replace -->
    </div>
  </main>
</template>

<script>
//import axios from 'axios';
export default {
  data() {
    return {
      site: false };
  },
};
</script>
