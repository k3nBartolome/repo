<template>
  <div class="container">
    <!-- Employee Details Section -->
    <div class="employee-details">
      <div class="employee-info">
        <div class="employee-info-row">
          <p>
            <strong>Full Name:</strong> {{ employee.last_name }},
            {{ employee.first_name }} {{ employee.middle_name }}
          </p>
        </div>
        <div class="employee-info-row">
          <p><strong>Email:</strong> {{ employee.email }}</p>
          <p><strong>Contact Number:</strong> {{ employee.contact_number }}</p>
        </div>
      </div>
    </div>

    <!-- Optional Buttons Section -->
    <div
      class="optional-buttons"
      v-if="optionalButtons && optionalButtons.length > 0"
    >
      <div class="optional-buttons-header">
        <h3>Optional Actions</h3>
      </div>
      <div
        v-for="(buttonLabel, index) in optionalButtons"
        :key="index"
        class="optional-button-container"
      >
        <button
          class="optional-button"
          @click="handleOptionalButton(buttonLabel)"
        >
          {{ buttonLabel }}
        </button>
      </div>
    </div>

    <!-- Main Button Container (Two or three buttons per row) -->
    <div class="button-container">
      <button class="update-button" @click="selectOption('OnboardingLob')">
        Lob
      </button>
      <button class="update-button" @click="selectOption('OnboardingNBI')">
        NBI
      </button>
      <button class="update-button" @click="selectOption('OnboardingDT')">
        DT
      </button>
      <button class="update-button" @click="selectOption('OnboardingPEME')">
        PEME
      </button>
      <button class="update-button" @click="selectOption('OnboardingSSS')">
        SSS
      </button>
      <button class="update-button" @click="selectOption('OnboardingPHIC')">
        PHIC
      </button>
      <button class="update-button" @click="selectOption('OnboardingPAGIBIG')">
        PAGIBIG
      </button>
      <button class="update-button" @click="selectOption('OnboardingTIN')">
        TIN
      </button>
      <button
        class="update-button"
        @click="selectOption('OnboardingHEALTHCERTIFICATE')"
      >
        HEALTH CERTIFICATE
      </button>
      <button
        class="update-button"
        @click="selectOption('OnboardingOCCUPATIONALPERMIT')"
      >
        OCCUPATIONAL PERMIT
      </button>
      <button class="update-button" @click="selectOption('OnboardingOFAC')">
        OFAC
      </button>
      <button class="update-button" @click="selectOption('OnboardingSAM')">
        SAM
      </button>
      <button class="update-button" @click="selectOption('OnboardingOIG')">
        OIG
      </button>
      <button class="update-button" @click="selectOption('OnboardingCIBI')">
        CIBI
      </button>
      <button class="update-button" @click="selectOption('OnboardingBGC')">
        BGC
      </button>
      <button
        class="update-button"
        @click="selectOption('OnboardingBIRTHCERTIFICATE')"
      >
        BIRTH CERTIFICATE
      </button>
      <button
        class="update-button"
        @click="selectOption('OnboardingMARRIAGECERTIFICATE')"
      >
        MARRIAGE CERTIFICATE
      </button>
      <button
        class="update-button"
        @click="selectOption('OnboardingDEPENDENTBIRTHCERTIFICATE')"
      >
        DEPENDENT'S BIRTH CERTIFICATE
      </button>
      <button
        class="update-button"
        @click="selectOption('OnboardingSCHOLASTIC')"
      >
        SCHOLASTIC RECORD
      </button>
      <button
        class="update-button"
        @click="selectOption('OnboardingPREVIOUSEMPLOYMENT')"
      >
        PREVIOUS EMPLOYMENT
      </button>
      <button
        class="update-button"
        @click="selectOption('OnboardingSUPPORTINGDOCUMENT')"
      >
        SUPPORTING DOCUMENT
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      employee: {
        first_name: "",
        middle_name: "",
        last_name: "",
        contact_number: "",
        email: "",
        employee_image_url: "", // You can add an employee image field if you have it
        employee_id: "",
        employee_status: "",
        hired_date: "",
        hired_month: "",
        birthdate: "",
      },
      selectedOption: null,
    };
  },
  mounted() {
    this.getEmployee(); // Fetch employee data when component is mounted
  },
  methods: {
    async getEmployee() {
      try {
        const token = this.$store.state.token;
        const headers = {
          Authorization: `Bearer ${token}`,
        };

        const response = await axios.get(
          `http://10.109.2.112:8000/api/employees/${this.$route.params.id}`,
          { headers }
        );

        if (response.status === 200) {
          const EmpObj = response.data.employee;

          // Update the employee data fields dynamically
          this.employee = {
            first_name: EmpObj.first_name,
            middle_name: EmpObj.middle_name,
            last_name: EmpObj.last_name,
            contact_number: EmpObj.contact_number,
            email: EmpObj.email,
            employee_image_url: EmpObj.employee_image_url,
            employee_id: EmpObj.employee_id,
            employee_status: EmpObj.employee_status,
            hired_date: EmpObj.hired_date,
            hired_month: EmpObj.hired_month,
            birthdate: EmpObj.birthdate,
          };

          console.log(EmpObj); // Log to check the structure of the response
        } else {
          console.log("Error fetching employee data");
        }
      } catch (error) {
        console.log(error);
      }
    },

    selectOption(option) {
      this.selectedOption = option;
      console.log(`Selected option: ${option}`);

      // Navigate to the corresponding form (e.g., OnboardingNBi, TIN, etc.) and pass the employee ID
      this.$router.push({
        name: option,
        params: { id: this.$route.params.id },
      });
    },

    handleOptionalButton(buttonLabel) {
      console.log(`Optional button clicked: ${buttonLabel}`);
    },
  },
};
</script>
<style scoped>
/* Container styling */
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 15px; /* Updated padding */
  max-width: 1200px; /* Limit max width */
  margin: 0 auto; /* Center content */
}

/* Title styling */
.title {
  font-size: 1.8rem; /* Reduced font size */
  margin-bottom: 20px;
  text-align: center;
  color: #333;
}

/* Employee details section */
.employee-details {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: flex-start;
  gap: 10px;
  margin-bottom: 20px;
  width: 100%;
  max-width: 800px;
  padding: 15px;
  background-color: #f4f4f4;
  border-radius: 8px;
}

.employee-image img {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #007bff;
}

.employee-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
}

.employee-info-row {
  display: flex;
  flex-direction: column;
  margin-bottom: 5px;
}

.employee-info p {
  margin: 5px 0;
  font-size: 0.9rem;
  color: #555;
}

.employee-info strong {
  color: #333;
}

/* Optional buttons section */
.optional-buttons {
  margin-bottom: 30px;
  width: 100%;
  max-width: 500px;
}

.optional-buttons-header {
  margin-bottom: 15px;
  font-size: 1.2rem; /* Reduced font size */
  font-weight: bold;
  color: #007bff;
}

.optional-button-container {
  margin-bottom: 10px;
}

.optional-button,
.update-button {
  padding: 10px; /* Reduced padding */
  font-size: 1rem; /* Reduced font size */
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s;
  width: 100%;
  white-space: nowrap; /* Prevent text from wrapping */
  overflow: hidden; /* Hide overflowed text */
  text-overflow: ellipsis; /* Add ellipsis (...) if text is too long */
  max-width: 100%; /* Ensure full width up to button size */
}

/* Hover effect */
.optional-button:hover,
.update-button:hover {
  background-color: #0056b3;
}

/* Main button container (Two or three buttons per row) */
.button-container {
  display: grid;
  grid-template-columns: repeat(
    auto-fill,
    minmax(180px, 1fr)
  ); /* Smaller button width */
  gap: 15px;
  justify-content: center;
  width: 100%;
  max-width: 700px;
}

.update-button {
  background-color: #28a745;
  transition: background-color 0.3s;
}

.update-button:hover {
  background-color: #218838;
}

/* Selection display section */
.selection-display {
  margin-top: 30px;
  font-size: 1rem; /* Reduced font size */
  color: #333;
}

/* Responsive Design */
@media (max-width: 600px) {
  .title {
    font-size: 1.6rem; /* Reduced font size on mobile */
  }

  .employee-details {
    flex-direction: column;
    align-items: center;
  }

  .employee-info {
    text-align: center;
    margin-top: 10px;
  }

  .employee-image {
    margin-bottom: 15px;
  }

  .button-container {
    grid-template-columns: repeat(
      auto-fill,
      minmax(140px, 1fr)
    ); /* Even smaller buttons */
  }

  .optional-button,
  .update-button {
    width: 100%;
    font-size: 1rem; /* Reduced font size */
    padding: 10px; /* Reduced padding */
  }
}
</style>
