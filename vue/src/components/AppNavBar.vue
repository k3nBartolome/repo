<script>
export default {

}
</script>
<template>
    <nav class="flex items-center justify-between px-4 py-3 bg-white">
        <div class="flex items-center float-left ml-8">
            <img :src="'../assets/vue.svg'" alt="VXI Logo" class="w-8 h-8">
            <span class="ml-2 text-xl font-semibold text-black">VXI</span>
        </div>
        <div class="ml-px mr-8">
            <div class="space-x-0">
                <a
                    class="px-2 py-2 ml-4 font-semibold text-black hover:text-orange-600 focus:outline-none focus:shadow-outline-orange-600"><router-link v-bind:to="{ path: '/contact' }">Contact
                    Us</router-link></a>
                <router-link v-bind:to="{ path: '/login' }"><button
                    class="inline-flex items-center px-8 py-2 ml-4 font-semibold text-white bg-orange-600 rounded-full hover:bg-gray-600 focus:outline-none focus:shadow-outline-gray">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Login</span>
                </button></router-link>
            </div>
        </div>
    </nav>

</template>
