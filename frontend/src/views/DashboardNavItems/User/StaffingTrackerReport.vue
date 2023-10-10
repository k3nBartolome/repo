<template>
  <div>
    <header class="w-full bg-orange-300">
      <!-- Your header content here -->
    </header>
    <main class="flex flex-col flex-1 px-2 md:px-1">
      <div class="w-full py-0">
        <router-view />
      </div>
    </main>
    <footer class="w-full bg-orange-600 text-white text-center py-2">
      <router-link to="/staffing_report/month" class="footer-link">Month View</router-link>
      <router-link to="/staffing_report/site" class="footer-link">Site View</router-link>
      <router-link to="/staffing_report/week" class="footer-link">Week View</router-link>
    </footer>
  </div>
</template>

<style scoped>
.footer-link {
  color: white;
  margin: 0 10px; /* Adjust spacing between links as needed */
  text-decoration: none;
}

html, body, #app {
  height: 100%;
  display: flex;
  flex-direction: column;
}

main {
  flex-grow: 1;
  min-height: 0; /* Ensure it can shrink if content is smaller than viewport */
}

footer {
  position: sticky;
  bottom: 0;
  background-color: #333;
  color: white;
  text-align: center;
}

/* Responsive styles for footer */
@media (max-width: 768px) {
  .footer-link {
    margin: 10px 0; /* Adjust spacing for smaller screens */
    display: block;
  }
}
</style>
