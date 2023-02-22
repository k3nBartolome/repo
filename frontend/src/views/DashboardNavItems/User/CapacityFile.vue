<template>
  <div>
    <table class="date-table">
      <thead>
        <tr>
          <th v-for="(month, index) in months" :key="index">{{ month }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(week, index) in weeks" :key="index">
          <td class="week-cell">Week {{ week[0].week }}</td>
          <td v-for="(day, index) in week" :key="index" class="date-body-item">
            {{ day.label }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "DateRange",
  data() {
    return {
      startDate: "1-Jan-23",
      endDate: "31-Dec-23",
      months: [
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
      ],
      weeks: [],
    };
  },
  mounted() {
    this.weeks = this.getWeeks();
  },
  methods: {
    getWeeks() {
      const weeks = [];
      const currentDate = new Date(this.startDate);
      const endDate = new Date(this.endDate);

      while (currentDate <= endDate) {
        const currentMonth = currentDate.getMonth();
        const currentWeek = [];

        for (let i = 0; i < 7; i++) {
          if (currentDate.getMonth() === currentMonth) {
            const formattedDate = this.formatDate(currentDate);
            currentWeek.push({
              label: formattedDate,
              week: Math.ceil(
                (currentDate.getDate() + this.getDaysInPreviousMonths(currentDate)) / 7
              ),
            });
            currentDate.setDate(currentDate.getDate() + 1);
          } else {
            currentWeek.push({
              label: "",
              week: Math.ceil(
                (currentDate.getDate() + this.getDaysInPreviousMonths(currentDate)) / 7
              ),
            });
          }
        }

        weeks.push(currentWeek);
      }

      return weeks;
    },
    formatDate(date) {
      const week = Math.ceil((date.getDate() + this.getDaysInPreviousMonths(date)) / 7);
      const month = date.toLocaleString("default", { month: "short" }).toUpperCase();

      return `W${week} ${month}`;
    },
    getDaysInPreviousMonths(date) {
      let days = 0;
      for (let i = 0; i < date.getMonth(); i++) {
        days += new Date(date.getFullYear(), i + 1, 0).getDate();
      }
      return days;
    },
  },
};
</script>

<style scoped>
.date-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

.date-table th {
  padding: 10px;
  text-align: center;
  font-weight: bold;
  border-bottom: 2px solid #ccc;
}

.date-table td {
  height: 60px;
  border: 1px solid #ccc;
  box-sizing: border-box;
  text-align: center;
  padding: 10px;
}

.week-cell {
  font-weight: bold;
  border-right: 2px solid #ccc;
}
</style>
