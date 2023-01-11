<template>
    <div class="min-h-screen bg-white">
        <!-- Navbar content goes here-->
        <NavBar/>
        <div class="container px-4 py-16 mx-auto">
            Welcome to TA Portal
        </div>
        <!-- Footer content goes here-->
        <FootBar/>
    </div>
</template>
<script>
import NavBar from './components/indexnavbar.vue';
import FootBar from './components/indexfooter.vue';

export default {
    components: {
        NavBar,
        FootBar
    },
}
</script>
<style>
</style>