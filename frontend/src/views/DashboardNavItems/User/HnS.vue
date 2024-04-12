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
            class="px-4 py-2 grid grid-cols  gap-2"
          >
          <div class="col-span-1">
            <label class="block">
              First Name
              <input
                type="text"
                v-model="first_name"
                class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
              />
            </label>
          </div>
          <div class="col-span-1">
            <label class="block">
              Middle Name
              <input
                type="text"
                v-model="middle_name"
                class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
              />
            </label>
          </div>
          <div class="col-span-1">
            <label class="block">
              Last Name
              <input
                type="text"
                v-model="last_name"
                class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
              />
            </label>
          </div>
          <div class="col-span-1">
            <label class="block">
              Position
              <input
                type="text"
                v-model="last_name"
                class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
              />
            </label>
          </div>
          <div class="col-span-1">
            <label class="block">
              Email
              <input
                type="text"
                v-model="email"
                class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
              />
            </label>
          </div>
          <div class="col-span-1">
            <label class="block">
              Contact
              <input
                type="text"
                v-model="contact_number
                "
                class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
              />
            </label>
          </div>
          <div class="col-span-1">
            <label class="block">
              General Source
              <input
                type="text"
                v-model="gen_source"
                class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
              />
            </label>
          </div>
          <div class="col-span-1">
            <label class="block">
              Specific Source
              <input
                type="text"
                v-model="spec_source"
                class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal leading-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
              />
            </label>
          </div>
          <div class="col-span-1">
            <label class="block">
              Site
              <input
                type="text"
                v-model="site_selected"
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
            @submit.prevent="importLeads"
            class="grid grid-cols-1 gap-4 font-semibold sm:grid-cols-2 md:grid-cols-1"
          >
          <div>
            <select v-model="selected">
              <option disabled value="">Please select one</option>
              <option v-for="option in options" :key="option">{{ option }}</option>
            </select>
            <input type="file" @change="handleFileUpload" accept=".xlsx,.xls,.csv" />
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
    <div class="flex items-baseline space-x-2 " >
      <div class="">
        <router-link to="/h&s/screening" class="link-button">
          <button class="text-black font-sans font-semibold text-2xs svg-button">
            <svg viewBox="-8.16 -8.16 50.32 50.32" xmlns="http://www.w3.org/2000/svg" fill="#f2740d" stroke="#f2740d">
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.544"></g>
              <g id="SVGRepo_iconCarrier">
                <g fill="none" fill-rule="evenodd">
                  <g>
                    <path d="M1 1.993c0-.55.45-.993.995-.993h17.01c.55 0 1.34.275 1.776.625l3.44 2.75c.43.345.78 1.065.78 1.622v26.006c0 .55-.447.997-1 .997H2c-.552 0-1-.452-1-.993V1.993z" stroke="##f2740d" stroke-width="1.224"></path>
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
        <button class="text-black font-sans font-semibold text-2xs svg-button">
            <svg viewBox="-8.16 -8.16 50.32 50.32" xmlns="http://www.w3.org/2000/svg" fill="#f2740d" stroke="#f2740d">
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.544"></g>
              <g id="SVGRepo_iconCarrier">
                <g fill="none" fill-rule="evenodd">
                  <g>
                    <path d="M1 1.993c0-.55.45-.993.995-.993h17.01c.55 0 1.34.275 1.776.625l3.44 2.75c.43.345.78 1.065.78 1.622v26.006c0 .55-.447.997-1 .997H2c-.552 0-1-.452-1-.993V1.993z" stroke="##f2740d" stroke-width="1.224"></path>
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
            </svg>Interview</button>
        </router-link>
      </div>
      <div class="">
        <router-link to="/h&s/ov" class="link-button">
        <button class="text-black font-sans font-semibold text-2xs svg-button">
            <svg viewBox="-8.16 -8.16 50.32 50.32" xmlns="http://www.w3.org/2000/svg" fill="#f2740d" stroke="#f2740d">
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.544"></g>
              <g id="SVGRepo_iconCarrier">
                <g fill="none" fill-rule="evenodd">
                  <g>
                    <path d="M1 1.993c0-.55.45-.993.995-.993h17.01c.55 0 1.34.275 1.776.625l3.44 2.75c.43.345.78 1.065.78 1.622v26.006c0 .55-.447.997-1 .997H2c-.552 0-1-.452-1-.993V1.993z" stroke="##f2740d" stroke-width="1.224"></path>
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
            </svg>OV</button>
        </router-link>
      </div>
      <div class="">
        <router-link to="/h&s/prog_specs" class="link-button">
          <button class="text-black font-sans font-semibold text-2xs svg-button">
            <svg viewBox="-8.16 -8.16 50.32 50.32" xmlns="http://www.w3.org/2000/svg" fill="#f2740d" stroke="#f2740d">
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.544"></g>
              <g id="SVGRepo_iconCarrier">
                <g fill="none" fill-rule="evenodd">
                  <g>
                    <path d="M1 1.993c0-.55.45-.993.995-.993h17.01c.55 0 1.34.275 1.776.625l3.44 2.75c.43.345.78 1.065.78 1.622v26.006c0 .55-.447.997-1 .997H2c-.552 0-1-.452-1-.993V1.993z" stroke="##f2740d" stroke-width="1.224"></path>
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
            </svg> Prog Specs </button>
        </router-link>
      </div>
      <div class="">
        <router-link to="/h&s/job_offer" class="link-button">
          <button class="text-black font-sans font-semibold text-2xs svg-button">
            <svg viewBox="-8.16 -8.16 50.32 50.32" xmlns="http://www.w3.org/2000/svg" fill="#f2740d" stroke="#f2740d">
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.544"></g>
              <g id="SVGRepo_iconCarrier">
                <g fill="none" fill-rule="evenodd">
                  <g>
                    <path d="M1 1.993c0-.55.45-.993.995-.993h17.01c.55 0 1.34.275 1.776.625l3.44 2.75c.43.345.78 1.065.78 1.622v26.006c0 .55-.447.997-1 .997H2c-.552 0-1-.452-1-.993V1.993z" stroke="##f2740d" stroke-width="1.224"></path>
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
            </svg> Job Offer</button>
        </router-link>
      </div>
    </div>
  </div>
  <main class="flex flex-col h-screen border border-1">
    <div class="px-4 py-2 grid grid-cols-10 grid-rows-1 gap-2">
      <button class="bg-orange-500 text-white rounded-full text-2xs svg-button2" @click="showModalAdd = true">
        <svg width="143px" height="143px" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#ffffff" stroke="#ffffff">
          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
          <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
          <g id="SVGRepo_iconCarrier">
            <title>plus_circle [#ffffff]</title>
            <desc>Created with Sketch.</desc>
            <defs></defs>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
              <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -520.000000)" fill="#ffffff">
                <g id="icons" transform="translate(56.000000, 160.000000)">
                  <path d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z" id="plus_circle-[#ffffff]"> </path>
                </g>
              </g>
            </g>
          </g>
        </svg>
        Add Leads
      </button>
      <button class="bg-orange-500 text-white rounded-full text-2xs svg-button2" @click="showModalImport = true">
        <svg width="143px" height="143px" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#ffffff" stroke="#ffffff">
          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
          <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
          <g id="SVGRepo_iconCarrier">
            <title>plus_circle [#ffffff]</title>
            <desc>Created with Sketch.</desc>
            <defs></defs>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
              <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -520.000000)" fill="#ffffff">
                <g id="icons" transform="translate(56.000000, 160.000000)">
                  <path d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z" id="plus_circle-[#ffffff]"> </path>
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
export default {
  data() {
    return {
      errors: {},
      showModalAdd: false,
      showModalImport: false,
      last_name:"",
      first_name:"",
      middle_name:"",
      position:"",
      email:"",
      contact_number:"",
      gen_source:"",
      spec_source:"",
      site_selected:"",

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
  width: 1em; /* This makes the SVG the same size as the surrounding text */
  height: 1em; /* This makes the SVG the same size as the surrounding text */
  border: 1px solid #000; /* This adds a border to the SVG */
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
  width: 1em; /* This makes the SVG the same size as the surrounding text */
  height: 1em; /* This makes the SVG the same size as the surrounding text */
  /* This adds a border to the SVG */
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
</style>
