<template>
    <div class="relative flex flex-col justify-center min-h-screen overflow-hidden">
        <div class="w-full p-6 m-auto rounded-md border-2 border-solid border-orange-500 lg:max-w-md">
            <img :src="'/storage/vxi.jpg'"  class="logo mx-auto h-20 w-30"/>
            <form class="mt-6" autocomplete="off">
                <div>
                    <label for="email" class="block text-sm font-bold text-black">Email:</label>
                    <input type="email" placeholder="Enter your Email"
                        class="placeholder-gray-500 placeholder-opacity-75 block w-full px-4 py-2 mt-2 text-orange-700 bg-white border-2 border-solid border-black rounded-md focus:border-orange-400 focus:ring-orange-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
                <div class="mt-4">
                    <div>
                        <label for="password" class="block text-sm font-bold text-black">Password:</label>
                        <input type="password" placeholder="Enter your Password"
                            class="placeholder-gray-500 placeholder-opacity-75 block w-full px-4 py-2 mt-2 text-orange-700 bg-white border-2 border-solid border-black rounded-md focus:border-orange-400 focus:ring-orange-300 focus:outline-none focus:ring focus:ring-opacity-40">
                    </div>
                    <div class="mt-6">
                        <button
                            class="w-full px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-orange-500 rounded-md hover:bg-orange-600 focus:outline-none focus:bg-orange-600">
                            Login
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
}
</script>