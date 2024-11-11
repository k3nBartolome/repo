<template>
  <div class="container">
    <div class="table-wrapper">
      <table class="employee-table">
        <thead>
          <tr>
            <th class="truncate">Action</th>
            <th class="truncate">Employee ID</th>
            <th class="truncate">Last Name</th>
            <th class="truncate">First Name</th>
            <th class="truncate">Middle Name</th>
            <th class="truncate">Date of Birth</th>
            <th class="truncate">Contact Number</th>
            <th class="truncate">Email</th>
            <th class="truncate">Hired Date</th>
            <th class="truncate">Hired Month</th>
            <th class="truncate">Employee Status</th>
            <th class="truncate">Employment Status</th>
            <th class="truncate">Account Associate</th>
            <th class="truncate">Added By</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="employee in employees" :key="employee.id">
            

            <td class="truncate">
              <button
                class="text-white truncate bg-blue-400"
                @click="goToUpdate(employee.id)"
              >
                Update
              </button>
            </td>
            <td class="truncate">{{ employee.employee_id }}</td>
            <td class="truncate">{{ employee.last_name }}</td>
            <td class="truncate">{{ employee.first_name }}</td>
            <td class="truncate">{{ employee.middle_name }}</td>
            <td class="truncate">{{ employee.birthdate }}</td>
            <td class="truncate">{{ employee.contact_number }}</td>
            <td class="truncate">{{ employee.email }}</td>
            <td class="truncate">{{ employee.hired_date }}</td>
            <td class="truncate">{{ employee.hired_month }}</td>
            <td class="truncate">{{ employee.employee_status }}</td>
            <td class="truncate">{{ employee.employment_status }}</td>
            <td class="truncate">{{ employee.account_associate }}</td>
            <td class="truncate">{{ employee.employee_added_by }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination Controls -->
    <div class="pagination">
      <button @click="goToPage(pagination.first_page)" :disabled="!pagination.prev_page">First</button>
      <button @click="goToPage(pagination.prev_page)" :disabled="!pagination.prev_page">Previous</button>
      <span>Page {{ pagination.current_page }} of {{ pagination.total_pages }}</span>
      <button @click="goToPage(pagination.next_page)" :disabled="!pagination.next_page">Next</button>
      <button @click="goToPage(pagination.last_page)" :disabled="!pagination.next_page">Last</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      employees: [],
      pagination: {
        current_page: 1,
        total: 0,
        first_page: null,
        last_page: null,
        next_page: null,
        prev_page: null,
        per_page: 10,
        total_pages: 1,
      },
    };
  },
  mounted() {
    this.getEmployees();
  },
  methods: {
    async getEmployees(url = 'http://127.0.0.1:8000/api/employees') {
      try {
        const token = this.$store.state.token;
        const response = await axios.get(url, {
          headers: { Authorization: `Bearer ${token}` },
        });

        if (response.status === 200) {
          this.employees = response.data.employees;
          this.pagination = response.data.pagination;
        }
      } catch (error) {
        console.error('Error fetching employees:', error);
      }
    },
    goToPage(url) {
      if (url) this.getEmployees(url);
    },
    goToUpdate(employeeId) {
      
      this.$router.push({ name: 'OnboardingUpdateForm', params: { id: employeeId } });
    },
  },
};
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-top: 10px;
  padding-left: 2px;
  padding-right: 2px;
}

.table-wrapper {
  overflow-x: auto;
  width: 100%;
}

.employee-table {
  width: 100%;
  max-width: 1200px;
  margin: 20px auto;
  border-collapse: collapse;
  text-align: left;
}

.employee-table th,
.employee-table td {
  padding: 12px;
  border: 1px solid #ddd;
}

.employee-table th {
  background-color: #f4f4f4;
  font-weight: bold;
}

.pagination {
  display: flex;
  gap: 10px;
  margin-top: 10px;
  justify-content: center;
  flex-wrap: wrap;
}

.pagination button {
  padding: 8px 12px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.pagination button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
