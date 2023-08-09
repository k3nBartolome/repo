<template>
    <header class="w-full">
        <div class="flex items-center w-full max-w-screen-xl sm:px-2 lg:px-2">
          <h1 class="pl-8 text-sm font-bold tracking-tight text-gray-900">
            Report Dashboard
          </h1>
        </div>
    </header>
    <div class="py-2">
        <div class="pl-8 pr-8">
          
        </div>
      </div>
    </template>
    
    <script>
    export default {
      data() {
        return {
        }
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
      mounted() {
        window.vm = this;
        
      },
      methods: {
       
      },
    };
    </script>
    <style>

    
    </style>