<template>
  <div>
    <table class="border-2 border-black align-center">
      <thead>
        <tr>
          <th class="border-2 border-black">Site Name</th>
          <th class="border-2 border-black">Program Name</th>
        </tr>
      </thead>
      <tbody v-for="(item, index) in classes" :key="index">
        <tr v-for="(item1, index1) in item" :key="index1">
          <td class="border-2 border-black">{{ index }}</td>
          <td class="border-2 border-black">{{ index1 }}</td>
          <td v-for="(item2, index2) in item1" :key="index2" class="border-2 border-black">
            <tbody v-for="(item3, index3) in item2" :key="index3">
              <td  class="border-2 border-black"><table  >
                <thead>
                  <th class="text-red-500">{{ index3 }}</th>
                </thead>
                <tbody><td class="border-2 border-black">{{ item3 }}</td></tbody>
              </table></td>
            </tbody>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>



<script>
import axios from "axios";
export default {
  data() {
    return {
      classes: [],
    };
  },
  computed: {
  },
  mounted() {
    this.getClassesAll();
  },
  methods: {
    async getClassesAll() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("http://127.0.0.1:8000/api/classesdashboard", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.status === 200) {
          this.classes = response.data.classes;
        } else {
          console.error("Error fetching classes. Status code:", response.status);
        }
      } catch (error) {
        console.error("An error occurred:", error);
      }
    },
  },
};
</script>
