<template>
  <div>
    <!-- Your component content here -->
    <paginate :page-count="pageCount" :click-handler="fetchData" :hide-prev-next="false" :hide-pages-limit="false"></paginate>
  </div>
</template>

<script>
import Paginate from 'vuejs-paginate';

export default {
  components: {
    Paginate
  },
  data() {
    return {
      // Your component data here
      pageCount: 10 // Total number of pages
    };
  },
  methods: {
    fetchData(pageNumber) {
      // Your method to fetch data for the specified page number
    }
  }
}
</script>
