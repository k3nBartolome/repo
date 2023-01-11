<template>
    <div class="relative flex flex-col justify-center min-h-screen overflow-hidden">

        <div class="w-full p-6 m-auto border-2 border-orange-500 border-solid rounded-md lg:max-w-md">

            <img :src="'/storage/logo.png'" class="h-20 mx-auto logo w-30" />

            <form class="mt-6" @submit.prevent="login" autocomplete="off">

                <div>

                    <label for="email" class="block text-sm font-bold text-black">Email:</label>

                    <input type="email" v-model="email" placeholder="Enter your Email"
                        class="block w-full px-4 py-2 mt-2 text-orange-700 placeholder-gray-500 placeholder-opacity-75 bg-white border-2 border-black border-solid rounded-md focus:border-orange-400 focus:ring-orange-300 focus:outline-none focus:ring focus:ring-opacity-40">

                </div>

                <div class="mt-4">

                    <div>

                        <label for="password" class="block text-sm font-bold text-black">Password:</label>

                        <input type="password" v-model="password" placeholder="Enter your Password"
                            class="block w-full px-4 py-2 mt-2 text-orange-700 placeholder-gray-500 placeholder-opacity-75 bg-white border-2 border-black border-solid rounded-md focus:border-orange-400 focus:ring-orange-300 focus:outline-none focus:ring focus:ring-opacity-40">

                    </div>

                    <div class="mt-6">

                        <button
                            class="w-full px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-orange-500 rounded-md hover:bg-orange-600 focus:outline-none focus:bg-orange-600"
                            @click="login">

                            Login

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>
</template>


<script>
import axios from 'axios';
export default {
    data() {
        return {
            email: '',
            password: '',
        }
    },
    methods: {
        async login() {
            try {
                const response = await axios.post('/api/login', {
                    email: this.email,
                    password: this.password
                });
                localStorage.setItem('token', response.data.token);
                localStorage.setItem('role', response.data.role);

                if (response.data.role === 'admin') {
                    this.$router.push({ name: "contactus" });
                } else if (response.data.role === 'manager') {
                    this.$router.push({ name: "contactus" });
                } else {
                    this.$router.push({ name: "contactus" });
                }
            } catch (error) {
                this.error = 'Invalid email or password.';
            }
        }
    }
}
</script>