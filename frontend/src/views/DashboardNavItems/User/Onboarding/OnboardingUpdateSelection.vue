<template>
  <div class="container">
    <h1 class="title">Select Update Option</h1>

    <div class="button-container">
      <button class="update-button" @click="selectOption('OnboardingNBI')">
        NBI
      </button>
      <button class="update-button" @click="selectOption('TIN')">TIN</button>
      <button class="update-button" @click="selectOption('DT')">DT</button>
      <button class="update-button" @click="selectOption('PEME')">PEME</button>
    </div>

    <div v-if="selectedOption" class="selection-display">
      <p>
        You selected: <strong>{{ selectedOption }}</strong>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  props: ["id"], // Receive the employee ID as a prop

  data() {
    return {
      selectedOption: null,
    };
  },
  methods: {
    // The method that handles selection and navigates to the corresponding form
    selectOption(option) {
      this.selectedOption = option;
      console.log(`Selected option: ${option}`);

      // Navigate to the corresponding form (e.g., OnboardingNBi, TIN, etc.) and pass the employee ID
      this.$router.push({
        name: option, // The name of the form corresponds to the selected option
        params: { id: this.id }, // Pass the employee ID as a route parameter
      });
    },
  },
};
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 20px;
  font-family: Arial, sans-serif;
}

.title {
  font-size: 2rem;
  margin-bottom: 20px;
}

.button-container {
  display: flex;
  flex-direction: column;
  gap: 15px;
  width: 100%;
  max-width: 400px;
}

.update-button {
  padding: 15px;
  font-size: 1.5rem;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s;
  width: 100%;
}

.update-button:hover {
  background-color: #218838;
}

.selection-display {
  margin-top: 20px;
  font-size: 1.2rem;
  color: #333;
}

@media (max-width: 600px) {
  .update-button {
    font-size: 1.3rem;
    padding: 20px;
  }
  .title {
    font-size: 1.8rem;
  }
}
</style>
