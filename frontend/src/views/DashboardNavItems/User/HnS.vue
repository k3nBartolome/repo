<template>
  <div class="py-0">
    <div class="px-1 py-0 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
      <div
        class="fixed inset-0 z-50 flex items-center justify-center modal"
        v-if="showModalAdd"
      >
        <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
        <div class="max-w-sm p-4 bg-white rounded shadow-lg modal-content">
          <header class="px-4 py-2 border-b-2 border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Add Leads</h2>
          </header>
          <button
            @click="showModalAdd = false"
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
          <form
            @submit.prevent="addLeads"
            class="grid gap-2 px-4 py-2 grid-cols"
          >
            <div class="col-span-1">
              <label class="block">
                First Name
                <input
                  type="text"
                  v-model="lead_first_name"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Middle Name
                <input
                  type="text"
                  v-model="lead_middle_name"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Last Name
                <input
                  type="text"
                  v-model="lead_last_name"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Position
                <input
                  type="text"
                  v-model="lead_position"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Email
                <input
                  type="text"
                  v-model="lead_email_address"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Contact
                <input
                  type="text"
                  v-model="lead_contact_number"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                General Source
                <input
                  type="text"
                  v-model="lead_gen_source"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Specific Source
                <input
                  type="text"
                  v-model="lead_spec_source"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Site
                <input
                  type="text"
                  v-model="lead_site"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>

            <div class="flex justify-end mt-4">
              <button
                type="submit"
                class="px-4 py-2 text-white bg-orange-500 rounded hover:bg-blue-600"
              >
                Submit
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="py-0">
    <div class="px-1 py-0 mx-auto bg-white max-w-7xl sm:px-6 lg:px-8">
      <div
        class="fixed inset-0 z-50 flex items-center justify-center modal"
        v-if="showModalImport"
      >
        <div class="absolute inset-0 bg-black opacity-50 modal-overlay"></div>
        <div class="max-w-sm p-4 bg-white rounded shadow-lg modal-content">
          <header class="px-4 py-2 border-b-2 border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Import Leads</h2>
          </header>
          <button
            @click="showModalImport = false"
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
          <form
            @submit.prevent="submitBulkLeads"
            class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-1"
          >
            <div>
              <label for="images" class="drop-container" id="dropcontainer">
                <span class="drop-title">Drop files here</span>
                or
                <input type="file" @change="handleFileChange" />
              </label>
            </div>
            <div class="flex justify-end mt-4">
              <button
                type="submit"
                class="px-4 py-2 text-white bg-orange-500 rounded hover:bg-blue-600"
              >
                Submit
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="px-4 border border-solid border-1">
    <div class="flex items-baseline space-x-2">
      <div class="">
        <router-link to="/h&s/screening" class="link-button">
          <button
            class="font-sans font-semibold text-black text-2xs svg-button"
          >
            <svg
              viewBox="-8.16 -8.16 50.32 50.32"
              xmlns="http://www.w3.org/2000/svg"
              fill="#f2740d"
              stroke="#f2740d"
            >
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke="#CCCCCC"
                stroke-width="0.544"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <g fill="none" fill-rule="evenodd">
                  <g>
                    <path
                      d="M1 1.993c0-.55.45-.993.995-.993h17.01c.55 0 1.34.275 1.776.625l3.44 2.75c.43.345.78 1.065.78 1.622v26.006c0 .55-.447.997-1 .997H2c-.552 0-1-.452-1-.993V1.993z"
                      stroke="##f2740d"
                      stroke-width="1.224"
                    ></path>
                    <g fill="#f2740d">
                      <path d="M8 12h12v1H8z"></path>
                      <path d="M6 12h1v1H6z"></path>
                      <path d="M6 15h1v1H6z"></path>
                      <path d="M8 15h12v1H8z"></path>
                      <path d="M6 18h1v1H6z"></path>
                      <path d="M8 18h12v1H8z"></path>
                      <path d="M6 21h1v1H6z"></path>
                      <path d="M8 21h12v1H8z"></path>
                    </g>
                    <path fill="##f2740d" d="M18 2h1v6h-1z"></path>
                    <path fill="##f2740d" d="M18 7h6v1h-6z"></path>
                  </g>
                </g>
              </g>
            </svg>
            Screening
          </button>
        </router-link>
      </div>

      <div class="">
        <router-link to="/h&s/interview" class="link-button">
          <button
            class="font-sans font-semibold text-black text-2xs svg-button"
          >
            <svg
              viewBox="-8.16 -8.16 50.32 50.32"
              xmlns="http://www.w3.org/2000/svg"
              fill="#f2740d"
              stroke="#f2740d"
            >
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke="#CCCCCC"
                stroke-width="0.544"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <g fill="none" fill-rule="evenodd">
                  <g>
                    <path
                      d="M1 1.993c0-.55.45-.993.995-.993h17.01c.55 0 1.34.275 1.776.625l3.44 2.75c.43.345.78 1.065.78 1.622v26.006c0 .55-.447.997-1 .997H2c-.552 0-1-.452-1-.993V1.993z"
                      stroke="##f2740d"
                      stroke-width="1.224"
                    ></path>
                    <g fill="#f2740d">
                      <path d="M8 12h12v1H8z"></path>
                      <path d="M6 12h1v1H6z"></path>
                      <path d="M6 15h1v1H6z"></path>
                      <path d="M8 15h12v1H8z"></path>
                      <path d="M6 18h1v1H6z"></path>
                      <path d="M8 18h12v1H8z"></path>
                      <path d="M6 21h1v1H6z"></path>
                      <path d="M8 21h12v1H8z"></path>
                    </g>
                    <path fill="##f2740d" d="M18 2h1v6h-1z"></path>
                    <path fill="##f2740d" d="M18 7h6v1h-6z"></path>
                  </g>
                </g>
              </g></svg
            >Interview
          </button>
        </router-link>
      </div>
      <div class="">
        <router-link to="/h&s/ov" class="link-button">
          <button
            class="font-sans font-semibold text-black text-2xs svg-button"
          >
            <svg
              viewBox="-8.16 -8.16 50.32 50.32"
              xmlns="http://www.w3.org/2000/svg"
              fill="#f2740d"
              stroke="#f2740d"
            >
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke="#CCCCCC"
                stroke-width="0.544"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <g fill="none" fill-rule="evenodd">
                  <g>
                    <path
                      d="M1 1.993c0-.55.45-.993.995-.993h17.01c.55 0 1.34.275 1.776.625l3.44 2.75c.43.345.78 1.065.78 1.622v26.006c0 .55-.447.997-1 .997H2c-.552 0-1-.452-1-.993V1.993z"
                      stroke="##f2740d"
                      stroke-width="1.224"
                    ></path>
                    <g fill="#f2740d">
                      <path d="M8 12h12v1H8z"></path>
                      <path d="M6 12h1v1H6z"></path>
                      <path d="M6 15h1v1H6z"></path>
                      <path d="M8 15h12v1H8z"></path>
                      <path d="M6 18h1v1H6z"></path>
                      <path d="M8 18h12v1H8z"></path>
                      <path d="M6 21h1v1H6z"></path>
                      <path d="M8 21h12v1H8z"></path>
                    </g>
                    <path fill="##f2740d" d="M18 2h1v6h-1z"></path>
                    <path fill="##f2740d" d="M18 7h6v1h-6z"></path>
                  </g>
                </g>
              </g></svg
            >OV
          </button>
        </router-link>
      </div>
      <div class="">
        <router-link to="/h&s/prog_specs" class="link-button">
          <button
            class="font-sans font-semibold text-black text-2xs svg-button"
          >
            <svg
              viewBox="-8.16 -8.16 50.32 50.32"
              xmlns="http://www.w3.org/2000/svg"
              fill="#f2740d"
              stroke="#f2740d"
            >
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke="#CCCCCC"
                stroke-width="0.544"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <g fill="none" fill-rule="evenodd">
                  <g>
                    <path
                      d="M1 1.993c0-.55.45-.993.995-.993h17.01c.55 0 1.34.275 1.776.625l3.44 2.75c.43.345.78 1.065.78 1.622v26.006c0 .55-.447.997-1 .997H2c-.552 0-1-.452-1-.993V1.993z"
                      stroke="##f2740d"
                      stroke-width="1.224"
                    ></path>
                    <g fill="#f2740d">
                      <path d="M8 12h12v1H8z"></path>
                      <path d="M6 12h1v1H6z"></path>
                      <path d="M6 15h1v1H6z"></path>
                      <path d="M8 15h12v1H8z"></path>
                      <path d="M6 18h1v1H6z"></path>
                      <path d="M8 18h12v1H8z"></path>
                      <path d="M6 21h1v1H6z"></path>
                      <path d="M8 21h12v1H8z"></path>
                    </g>
                    <path fill="##f2740d" d="M18 2h1v6h-1z"></path>
                    <path fill="##f2740d" d="M18 7h6v1h-6z"></path>
                  </g>
                </g>
              </g>
            </svg>
            Prog Specs
          </button>
        </router-link>
      </div>
      <div class="">
        <router-link to="/h&s/job_offer" class="link-button">
          <button
            class="font-sans font-semibold text-black text-2xs svg-button"
          >
            <svg
              viewBox="-8.16 -8.16 50.32 50.32"
              xmlns="http://www.w3.org/2000/svg"
              fill="#f2740d"
              stroke="#f2740d"
            >
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke="#CCCCCC"
                stroke-width="0.544"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <g fill="none" fill-rule="evenodd">
                  <g>
                    <path
                      d="M1 1.993c0-.55.45-.993.995-.993h17.01c.55 0 1.34.275 1.776.625l3.44 2.75c.43.345.78 1.065.78 1.622v26.006c0 .55-.447.997-1 .997H2c-.552 0-1-.452-1-.993V1.993z"
                      stroke="##f2740d"
                      stroke-width="1.224"
                    ></path>
                    <g fill="#f2740d">
                      <path d="M8 12h12v1H8z"></path>
                      <path d="M6 12h1v1H6z"></path>
                      <path d="M6 15h1v1H6z"></path>
                      <path d="M8 15h12v1H8z"></path>
                      <path d="M6 18h1v1H6z"></path>
                      <path d="M8 18h12v1H8z"></path>
                      <path d="M6 21h1v1H6z"></path>
                      <path d="M8 21h12v1H8z"></path>
                    </g>
                    <path fill="##f2740d" d="M18 2h1v6h-1z"></path>
                    <path fill="##f2740d" d="M18 7h6v1h-6z"></path>
                  </g>
                </g>
              </g>
            </svg>
            Job Offer
          </button>
        </router-link>
      </div>
    </div>
  </div>
  <main class="flex flex-col h-screen border border-1">
    <div class="grid grid-cols-10 grid-rows-1 gap-2 px-4 py-2">
      <button
        class="text-white bg-orange-500 rounded-full text-2xs svg-button2"
        @click="showModalAdd = true"
      >
        <svg
          width="143px"
          height="143px"
          viewBox="0 -0.5 21 21"
          version="1.1"
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          fill="#ffffff"
          stroke="#ffffff"
        >
          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
          <g
            id="SVGRepo_tracerCarrier"
            stroke-linecap="round"
            stroke-linejoin="round"
          ></g>
          <g id="SVGRepo_iconCarrier">
            <title>plus_circle [#ffffff]</title>
            <desc>Created with Sketch.</desc>
            <defs></defs>
            <g
              id="Page-1"
              stroke="none"
              stroke-width="1"
              fill="none"
              fill-rule="evenodd"
            >
              <g
                id="Dribbble-Light-Preview"
                transform="translate(-419.000000, -520.000000)"
                fill="#ffffff"
              >
                <g id="icons" transform="translate(56.000000, 160.000000)">
                  <path
                    d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z"
                    id="plus_circle-[#ffffff]"
                  ></path>
                </g>
              </g>
            </g>
          </g>
        </svg>
        Add Leads
      </button>
      <button
        class="text-white bg-orange-500 rounded-full text-2xs svg-button2"
        @click="showModalImport = true"
      >
        <svg
          width="143px"
          height="143px"
          viewBox="0 -0.5 21 21"
          version="1.1"
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          fill="#ffffff"
          stroke="#ffffff"
        >
          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
          <g
            id="SVGRepo_tracerCarrier"
            stroke-linecap="round"
            stroke-linejoin="round"
          ></g>
          <g id="SVGRepo_iconCarrier">
            <title>plus_circle [#ffffff]</title>
            <desc>Created with Sketch.</desc>
            <defs></defs>
            <g
              id="Page-1"
              stroke="none"
              stroke-width="1"
              fill="none"
              fill-rule="evenodd"
            >
              <g
                id="Dribbble-Light-Preview"
                transform="translate(-419.000000, -520.000000)"
                fill="#ffffff"
              >
                <g id="icons" transform="translate(56.000000, 160.000000)">
                  <path
                    d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z"
                    id="plus_circle-[#ffffff]"
                  ></path>
                </g>
              </g>
            </g>
          </g>
        </svg>
        Import Leads
      </button>
    </div>
    <div class="flex flex-1 px-4 py-2 md:px-1">
      <div class="w-full py-6">
        <router-view />
      </div>
    </div>
  </main>
</template>
<script>
import axios from "axios";
export default {
  data() {
    return {
      errors: {},
      showModalAdd: false,
      showModalImport: false,
      file: null,
      lead_date: "",
      lead_source: "",
      lead_type: "",
      lead_application_date: "",
      lead_released_date: "",
      lead_srid: "",
      lead_prism_status: "",
      lead_site: "",
      lead_last_name: "",
      lead_first_name: "",
      lead_middle_name: "",
      lead_contact_number: "",
      lead_email_address: "",
      lead_home_address: "",
      lead_gen_source: "",
      lead_spec_source: "",
      lead_position: "",
    };
  },
  mounted() {
    this.$router.afterEach(() => {
      window.location.reload();
    });
  },
  computed: {
    isUser() {
      const userRole = this.$store.state.role;
      return userRole === "user";
    },
    isRemx() {
      const userRole = this.$store.state.role;
      return userRole === "remx";
    },
    isBudget() {
      const userRole = this.$store.state.role;
      return userRole === "budget";
    },
    isSourcing() {
      const userRole = this.$store.state.role;
      return userRole === "sourcing";
    },
  },

  methods: {
    handleFileChange(event) {
      const file = event.target.files[0];
      console.log(file);
      this.file = file;
    },

    async submitBulkLeads() {
      if (!this.file) {
        this.errorMessage = "Please select a file to upload.";
        return;
      }

      this.loading = true;
      this.errorMessage = "";
      this.successMessage = "";

      const formData = new FormData();
      formData.append("file", this.file);
      formData.append("lead_added_by", this.$store.state.user_id);

      try {
        const token = this.$store.state.token;
        const response = await axios.post(
          "https://10.109.2.112/api/upload-leads-bulk",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
              Authorization: `Bearer ${token}`,
            },
          }
        );
        this.successMessage = response.data.success;
        this.file = null;
      } catch (error) {
        if (error.response && error.response.data) {
          this.errorMessage =
            error.response.data.error || "Error uploading file";
        } else {
          this.errorMessage = "Error uploading file";
        }
      } finally {
        this.loading = false;
      }
    },

    addLeads() {
      this.loading = true;
      const formData = {
        lead_date: this.lead_date,
        lead_source: this.lead_source,
        lead_type: this.lead_type,
        lead_application_date: this.lead_application_date,
        lead_released_date: this.lead_released_date,
        lead_srid: this.lead_srid,
        lead_prism_status: this.lead_prism_status,
        lead_site: this.lead_site,
        lead_last_name: this.lead_last_name,
        lead_first_name: this.lead_first_name,
        lead_middle_name: this.lead_middle_name,
        lead_contact_number: this.lead_contact_number,
        lead_email_address: this.lead_email_address,
        lead_home_address: this.lead_home_address,
        lead_gen_source: this.lead_gen_source,
        lead_spec_source: this.lead_spec_source,
        lead_position: this.lead_position,
        lead_added_by: this.$store.state.user_id,
      };
      const token = this.$store.state.token;
      const headers = {
        Authorization: `Bearer ${token}`,
      };

      axios
        .post("https://10.109.2.112/api/upload_leads", formData, {
          headers,
        })
        .then((response) => {
          console.log(response.data);
          this.lead_date = "";
          this.lead_source = "";
          this.lead_type = "";
          this.lead_application_date = "";
          this.lead_released_date = "";
          this.lead_srid = "";
          this.lead_prism_status = "";
          this.lead_site = "";
          this.lead_last_name = "";
          this.lead_first_name = "";
          this.lead_middle_name = "";
          this.lead_contact_number = "";
          this.lead_email_address = "";
          this.lead_home_address = "";
          this.lead_gen_source = "";
          this.lead_spec_source = "";
          this.lead_position = "";
        })
        .catch((error) => {
          console.log(error.response.data);
        })
        .finally(() => {
          this.loading = false;
        });
    },

    isActiveTab(route) {
      return this.$route.path === route;
    },
  },
};
</script>
<style>
* {
  margin: 0;
  padding: 0;
  border: 0;
  outline: 0;
  font-size: 100%;
  vertical-align: baseline;
  background: transparent;
}
main {
  display: flex;
  flex-direction: column;
  height: 100%;
}
@media (min-width: 576px) {
  .tab-button {
    padding: 1rem 0.75rem;
  }
}
@media (min-width: 768px) {
  .tab-button {
    padding: 1rem 0.5rem;
  }
}
.link-button {
  text-decoration: none;
}
.svg-button {
  display: flex;
  align-items: center;
  gap: 5px;
}

.svg-button svg {
  width: 1em;
  height: 1em;
  border: 1px solid #000;
}
.svg-button2 {
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 8px;
  height: 20px;
  width: auto;
}
.svg-button2 svg {
  width: 1em;
  height: 1em;
}
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  max-width: 400px;
}

.drop-container {
  position: relative;
  display: flex;
  gap: 10px;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 200px;
  padding: 20px;
  border-radius: 10px;
  border: 2px dashed #fb8303;
  color: #444;
  cursor: pointer;
  transition: background 0.2s ease-in-out, border 0.2s ease-in-out;
}

.drop-container:hover,
.drop-container.drag-active {
  background: #eee;
  border-color: #111;
}

.drop-container:hover .drop-title,
.drop-container.drag-active .drop-title {
  color: #222;
}

.drop-title {
  color: #444;
  font-size: 20px;
  font-weight: bold;
  text-align: center;
  transition: color 0.2s ease-in-out;
}
</style>
