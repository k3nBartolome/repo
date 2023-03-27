<template>
    <table class="bg-red-200">
      <thead>
        <tr>
          <th v-for="column in columns" :key="column">{{ column }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, rowIndex) in rows" :key="rowIndex">
          <td v-for="(cell, columnIndex) in row" :key="columnIndex">
            <input v-model="cell.value" @blur="updateCell(rowIndex, columnIndex)">
          </td>
        </tr>
      </tbody>
    </table>
  </template>
  
  <script>
  export default {
    props: {
      columns: {
        type: Array,
        required: true
      },
      rows: {
        type: Array,
        required: true
      }
    },
    methods: {
      updateCell(rowIndex, columnIndex) {
        const cell = this.rows[rowIndex][columnIndex];
        cell.evaluatedValue = eval(cell.value);
      }
    }
  };
  </script>
  