<script>
export default {
  methods: {
     handleLinkClick() {
      this.$nextTick(() => {
        window.location.reload();
      });
    },
  },
};
</script>
<template>
    <div class="min-h-screen bg-orange-300">
        <div class="container px-4 py-8 mx-auto">
            <h1 class="mb-4 text-3xl font-medium">Contact Us</h1>
            
        </div>
    </div>
</template>