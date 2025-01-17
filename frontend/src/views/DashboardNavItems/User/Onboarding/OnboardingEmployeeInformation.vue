<template>
  <div class="container mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4 text-center">Employee Information</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
      <div class="flex flex-col">
        <label class="block text-sm font-medium">First Name</label>
        <input
          type="text"
          v-model="employee_info_first_name"
          class="p-1 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Middle Name</label>
        <input
          type="text"
          v-model="employee_info_middle_name"
          class="p-1 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Last Name</label>
        <input
          type="text"
          v-model="employee_info_last_name"
          class="p-1 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Position</label>
        <input
          type="text"
          v-model="employee_info_position"
          class="p-1 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Account Type</label>
        <input
          type="text"
          v-model="employee_info_account_type"
          class="p-1 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Employee ID</label>
        <input
          type="text"
          v-model="employee_info_employee_id"
          class="p-1 mt-1 border rounded w-full"
        />
      </div>
       <div class="flex flex-col">
        <label class="block text-sm font-medium">Workday ID</label>
        <input
          type="text"
          v-model="employee_info_wd_id"
          class="p-1 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Contact Number</label>
        <input
          type="text"
          v-model="employee_info_contact_number"
          class="p-1 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Email Address</label>
        <input
          type="text"
          v-model="employee_info_email_address"
          class="p-1 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Date of Birth</label>
        <input
          type="date"
          v-model="employee_info_birth_date"
          class="p-1 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Hired Date</label>
        <input
          type="date"
          v-model="employee_info_hired_date"
          class="p-1 mt-1 border rounded w-full"
        />
      </div>
        <div class="flex flex-col">
        <label class="block text-sm font-medium">Hired Month</label>
        <select
                  disabled
                  v-model="employee_info_hired_month"
                   class="p-1 mt-1 border rounded w-full"
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
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Employee Status</label>
        <input
          type="text"
          v-model="employee_info_employee_status"
          class="p-1 mt-1 border rounded w-full"
        />
      </div>
      <div class="flex flex-col">
        <label class="block text-sm font-medium">Employment Status</label>
        <input
          type="text"
          v-model="employee_info_employement_status"
          class="p-1 mt-1 border rounded w-full"
        />
      </div>
    </div>

    <div class="mt-4">
      <button
        @click="submitForm"
        :disabled="isSubmitting"
        class="btn p-2 border rounded w-full sm:w-1/2 lg:w-1/4 mx-auto"
      >
        {{ isSubmitting ? "Submitting..." : "Submit" }}
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      isSubmitting: false,
      employee_info_first_name: "",
      employee_info_middle_name: "",
      employee_info_last_name: "",
      employee_info_position: "",
      employee_info_employee_id: "",
       employee_info_wd_id: "",
      employee_info_contact_number: "",
      employee_info_email_address: "",
      employee_info_birth_date: "",
      employee_info_hired_date: "",
      employee_info_employee_status: "",
      employee_info_employement_status: "",
      employee_info_hired_month:"",
            employee_info_account_type:"",
    };
  },
   watch: {
    employee_info_hired_date(newValue) {
      if (newValue) {
        const date = new Date(newValue);
        const months = [
          "JAN",
          "FEB",
          "MAR",
          "APR",
          "MAY",
          "JUN",
          "JUL",
          "AUG",
          "SEP",
          "OCT",
          "NOV",
          "DEC",
        ];
        this.employee_info_hired_month = months[date.getMonth()];
      } else {
        this.employee_info_hired_month = "Select one";
      }
    },
  },
  methods: {
    async fetchEmployeeData() {
      try {
        const apiUrl = `https://10.109.2.112/api/employee_info/${this.$route.params.id}`;
        const response = await axios.get(apiUrl);
        const data = response.data;
        this.employee_info_first_name = data.first_name;
        this.employee_info_middle_name = data.middle_name;
        this.employee_info_last_name = data.last_name;
        this.employee_info_position = data.position;
        this.employee_info_account_type = data.account_type;
        this.employee_info_wd_id = data.wd_id;
         this.employee_info_employee_id = data.employee_id;
        this.employee_info_contact_number = data.contact_number;
        this.employee_info_email_address = data.email;
        this.employee_info_birth_date = data.birthdate;
        this.employee_info_hired_date = data.hired_date;
        this.employee_info_employee_status = data.employee_status;
        this.employee_info_employement_status = data.employment_status;
        this.employee_info_hired_month = data.hired_month;
      } catch (error) {
        console.error("Error fetching employee data:", error);
        alert("Failed to load employee data.");
      }
    },
    async submitForm() {
      this.isSubmitting = true;
      const formData = new FormData();
     formData.append("employee_info_first_name" ,this.employee_info_first_name);
     formData.append("employee_info_middle_name" ,this.employee_info_middle_name);
     formData.append("employee_info_last_name" ,this.employee_info_last_name);
     formData.append("employee_info_position" ,this.employee_info_position);
     formData.append("employee_info_account_type" ,this.employee_info_account_type);
     formData.append("employee_info_employee_id" ,this.employee_info_employee_id);
     formData.append("employee_info_wd_id" ,this.employee_info_wd_id);
     formData.append("employee_info_contact_number" ,this.employee_info_contact_number);
     formData.append("employee_info_email_address" ,this.employee_info_email_address);
     formData.append("employee_info_birth_date" ,this.employee_info_birth_date);
     formData.append("employee_info_hired_date" ,this.employee_info_hired_date);
     formData.append("employee_info_employee_status" ,this.employee_info_employee_status);
     formData.append("employee_info_employement_status" ,this.employee_info_employement_status);
     formData.append("employee_info_hired_month" ,this.employee_info_hired_month);
     formData.append("employee_info_updated_by" ,this.$store.state.user_id);

      try {
        const apiUrl = `https://10.109.2.112/api/update/employee_info/requirement/${this.$route.params.id}`;
        const response = await axios.post(apiUrl, formData, {
          headers: { "Content-Type": "multipart/form-data" },
        });
        alert("Form submitted successfully!");
        console.log(response.data);
        this.$router.push({ name: "OnboardingUpdateSelection", params: { id: this.$route.params.id } }).then(() => {
          window.location.reload();
        });
      } catch (error) {
        console.error("Error submitting form:", error.response ? error.response.data : error.message);
        alert("An error occurred while submitting the form.");
      } finally {
        this.isSubmitting = false;
      }
    },
  },
  mounted() {
    this.fetchEmployeeData();
  },
};
</script>

<style scoped>
.btn {
  background-color: #3490dc;
  color: white;
  font-weight: bold;
  transition: background-color 0.3s ease;
}

.btn:hover {
  background-color: #2779bd;
}

.container {
  max-width: 800px;
  margin: auto;
}

.object-cover {
  object-fit: cover;
}
</style>
