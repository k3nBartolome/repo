<template>
  <header class="w-full bg-white shadow">
    <div class="flex items-center w-full max-w-screen-xl py-2 sm:px-2 lg:px-2">
      <h2 class="pl-8 text-3xl font-bold tracking-tight text-gray-900">
        Program Manager
      </h2>
    </div>
  </header>
  <div class="px-12 py-8 font-serifs">
    <span v-if="successMessage" class="text-green-500">{{
      successMessage
    }}</span>
    <span v-if="errorMessage" class="text-red-500">{{ errorMessage }}</span>
    <form @submit.prevent="editProgram">
      <div class="py-0 mb-2 md:flex md:space-x-2 md:items-center">
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <label class="block font-semibold"
            >Name<input
              type="text"
              v-model="name"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
          /></label>
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <label class="block font-semibold"
            >Description<input
              type="text"
              v-model="description"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
          /></label>
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <label class="block font-semibold"
            >Program Group<input
              type="text"
              v-model="program_group"
              class="w-full px-4 py-2 bg-white border rounded-lg"
          /></label>
        </div>

        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <label class="block font-semibold"
            >ID Creation<select
              v-model="id_creation"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
            >
              <option disabled value="" selected>Please select one</option>
              <option value="Yes" selected>Yes</option>
              <option value="No" selected>No</option>
            </select></label
          >
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <label class="block font-semibold"
            >Pre Emps<select
              v-model="pre_emps"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
            >
              <option disabled value="" selected>Please select one</option>
              <option value="Yes" selected>Yes</option>
              <option value="No" selected>No</option>
            </select></label
          >
        </div>
        <div class="w-full md:w-1/3 mt-4 md:mt-0">
          <label class="block font-semibold"
            >Site<select
              v-model="sites_selected"
              class="w-full px-4 py-2 bg-white border rounded-lg"
              required
              @change="getSites"
            >
              <option disabled value="" selected>Please select one</option>
              <option v-for="site in sites" :key="site.id" :value="site.id">
                {{ site.name }}
              </option>
            </select></label
          >
        </div>
        <div class="w-full mt-4 md:w-1/3 md:mt-0">
          <label class="block font-semibold">
            <button
              type="submit"
              class="w-full px-4 py-2 mt-4 font-bold text-white bg-orange-500 rounded hover:bg-gray-600"
            >
              <i class="fa fa-building"></i> Save
            </button>
          </label>
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
      programs: "",
      name: "",
      description: "",
      program_group: "",
      sites_selected: "",
      program_type: "",
      pre_emps: "",
      id_creation: "",
      sites: [],
    };
  },

  mounted() {
    console.log("Component mounted.");
    this.getPrograms();
    this.getSites();
  },
  methods: {
    async getPrograms() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(
          "https://10.236.103.190/api/programs/" + this.$route.params.id,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.programs = response.data.data;
          const programObj = this.programs;
          this.name = programObj.name;
          this.description = programObj.description;
          this.program_group = programObj.program_group;
          this.sites_selected = programObj.site_id;

          (this.pre_emps = programObj.pre_emps),
            (this.id_creation = programObj.id_creation),
            console.log(programObj);
        } else {
          console.log("Error fetching programs");
        }
      } catch (error) {
        console.log(error);
      }
    },

    async getSites() {
      try {
        const token = this.$store.state.token;
        const response = await axios.get("https://10.236.103.190/api/sites7", {
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
    editProgram() {
      const token = this.$store.state.token;
      const formData = {
        name: this.name,
        description: this.description,
        program_group: this.program_group,

        pre_emps: this.pre_emps,
        id_creation: this.id_creation,
        site_id: this.sites_selected,
        updated_by: this.$store.state.user_id,
      };
      axios
        .put(
          "https://10.236.103.190/api/programsother/" + this.$route.params.id,
          formData,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        )
        .then((response) => {
          console.log(response.data);
          this.name = "";
          this.description = "";
          this.program_group = "";

          this.id_creation = "";
          this.pre_emps = "";
          this.sites_selected = "";
          this.$router.push("/program_managementguatemala", () => {
            location.reload();
          });
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
  },
};
</script>
