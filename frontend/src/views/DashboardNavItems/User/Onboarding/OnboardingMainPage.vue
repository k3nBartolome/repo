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
            <h2 class="text-lg font-semibold text-gray-800">Add Employee</h2>
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
            @submit.prevent="addEmployees"
            class="grid gap-2 px-4 py-2 grid-cols"
          >
            <div class="col-span-1">
              <label class="block">
                Employee ID
                <input
                  type="text"
                  v-model="employee_id"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                First Name
                <input
                  type="text"
                  v-model="first_name"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Middle Name
                <input
                  type="text"
                  v-model="middle_name"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Last Name
                <input
                  type="text"
                  v-model="last_name"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>

            <div class="col-span-1">
              <label class="block">
                Email
                <input
                  type="text"
                  v-model="email"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Contact
                <input
                  type="text"
                  v-model="contact_number"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Date of Birth
                <input
                  type="date"
                  v-model="birthdate"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Employee Status
                <input
                  type="text"
                  v-model="employee_status"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Hired Date
                <input
                  type="date"
                  v-model="hired_date"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Hired Month
                <select
                  v-model="hired_month"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                >
                  <option>Select one</option>
                  <option value="JAN">JAN</option>
                  <option value="FEB">FEB</option>
                  <option value="MAR">MAR</option>
                  <option value="APR">APR</option>
                  <option value="MAY">MAY</option>
                  <option value="JUN">JUN</option>
                  <option value="JUL">JUL</option>
                  <option value="AUG">AUG</option>
                  <option value="SEP">SEP</option>
                  <option value="OCT">OCT</option>
                  <option value="NOV">NOV</option>
                  <option value="DEC">DEC</option>
                </select>
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Account Associate
                <input
                  type="text"
                  v-model="account_associate"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                />
              </label>
            </div>
            <div class="col-span-1">
              <label class="block">
                Employment Status
                <select
                  v-model="employment_status"
                  class="block w-full whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-2 py-[0.17rem] text-center text-sm font-normal employeeing-[1.5] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                >
                  <option>Select one</option>
                  <option value="Hired">Hired</option>
                  <option value="To be Hired">To be Hired</option>
                </select>
              </label>
            </div>
            <div class="flex justify-end mt-4">
              <button
                type="submit"
                class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
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
            <h2 class="text-lg font-semibold text-gray-800">
              Import Employees
            </h2>
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
            @submit.prevent="submitBulkEmployees"
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
                class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
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
      <!-- <div class="">
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
            Dashboard
          </button>
        </router-link>
      </div> -->

      <div class="">
        <router-link to="/onboarding_dashboard" class="link-button">
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
            >Hired
          </button>
        </router-link>
      </div>
    </div>
  </div>
  <div class="grid grid-cols-10 grid-rows-1 gap-2 px-4 py-2">
    <div class="flex space-x-4">
      <button
        class="truncate flex items-center justify-center text-white bg-blue-500 rounded-lg md:rounded-full text-sm md:text-base px-8 py-1 md:px-10 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300"
        @click="showModalAdd = true"
      >
        Add
      </button>

      <button
        class="truncate flex items-center justify-center text-white bg-blue-500 rounded-lg md:rounded-full text-sm md:text-base px-8 py-1 md:px-10 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300"
        @click="showModalImport = true"
      >
        Bulk
      </button>
    </div>
  </div>
  <main class="flex flex-col min-h-screen border border-gray-300">
    <!-- Main Content -->
    <div class="flex flex-1 px-4 py-2 md:px-6 md:py-4 overflow-y-auto">
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
      employee_id: "",
      last_name: "",
      first_name: "",
      middle_name: "",
      employee_status: "",
      hired_date: "",
      hired_month: "",
      birthdate: "",
      contact_number: "",
      email: "",
      account_associate: "",
      employee_added_by: "",
      employment_status: "",
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

    async submitBulkEmployees() {
      if (!this.file) {
        this.errorMessage = "Please select a file to upload.";
        return;
      }

      this.loading = true;
      this.errorMessage = "";
      this.successMessage = "";

      const formData = new FormData();
      formData.append("file", this.file);
      formData.append("employee_added_by", this.$store.state.user_id);

      try {
        const token = this.$store.state.token;
        const response = await axios.post(
          "https://10.236.103.190/api/upload_employees_bulk",
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
        this.showModalAdd = false;
      }
    },

    addEmployees() {
      this.loading = true;
      const formData = {
        employee_id: this.employee_id,
        last_name: this.last_name,
        first_name: this.first_name,
        middle_name: this.middle_name,
        employee_status: this.employee_status,
        hired_date: this.hired_date,
        hired_month: this.hired_month,
        birthdate: this.birthdate,
        contact_number: this.contact_number,
        email: this.email,
        account_associate: this.account_associate,
        employment_status: this.employment_status,
        employee_added_by: this.$store.state.user_id,
      };
      const token = this.$store.state.token;
      const headers = {
        Authorization: `Bearer ${token}`,
      };

      axios
        .post("https://10.236.103.190/api/upload_employees", formData, {
          headers,
        })
        .then((response) => {
          console.log(response.data);
          this.employee_id = "";
          this.last_name = "";
          this.first_name = "";
          this.middle_name = "";
          this.employee_status = "";
          this.hired_date = "";
          this.hired_month = "";
          this.birthdate = "";
          this.contact_number = "";
          this.email = "";
          this.account_associate = "";
          this.employee_added_by = "";
          this.employment_status = "";
        })
        .catch((error) => {
          console.log(error.response.data);
        })
        .finally(() => {
          this.loading = false;
          this.showModalAdd = false;
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
  overflow-y: auto; /* Enable vertical scrolling when content overflows */
  border-left-width: 1px; /* Ensure left border is persistent */
  border-color: #ccc; /* Define a consistent border color */
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
