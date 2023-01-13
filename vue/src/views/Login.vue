<template>
    <div class="flex items-center justify-center min-h-full px-4 py-12 sm:px-6 lg:px-8">
      <div class="w-full max-w-md space-y-8">
        <div>
          <img class="w-auto h-12 mx-auto" src="https://cdn.contactcenterworld.com/images/company/vxi-global-solutions-1200px-logo.png" alt="Your Company" />
          <h2 class="mt-6 text-3xl font-bold tracking-tight text-center text-gray-900">Sign in</h2>
        </div>
        <form class="mt-8 space-y-6" action="#" method="POST">
          <input type="hidden" name="remember" value="true" />
          <div class="-space-y-px rounded-md shadow-sm">
            <div>
              <label for="email-address" class="sr-only">Email address</label>
              <input id="email-address" name="email" type="email" autocomplete="email" required="" class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-none appearance-none rounded-t-md focus:z-10 focus:border-orange-600 focus:outline-none focus:ring-orange-600 sm:text-sm" placeholder="Email address" />
            </div>
            <div>
              <label for="password" class="sr-only">Password</label>
              <input id="password" name="password" type="password" autocomplete="current-password" required="" class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-none appearance-none rounded-b-md focus:z-10 focus:border-orange-600 focus:outline-none focus:ring-orange-600 sm:text-sm" placeholder="Password" />
            </div>
          </div>
  
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input id="remember-me" name="remember-me" type="checkbox" class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500" />
              <label for="remember-me" class="block ml-2 text-sm text-gray-900">Remember me</label>
            </div>
  
            <div class="text-sm">
              <a href="#" class="font-medium text-orange-600 hover:text-gray-700">Forgot your password?</a>
            </div>
          </div>
  
          <div>
            <button type="submit" class="relative flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-orange-600 border border-transparent rounded-md group hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <LockClosedIcon class="w-5 h-5 text-white group-hover:text-orange-600" aria-hidden="true" />
              </span>
              Sign in
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { LockClosedIcon } from '@heroicons/vue/20/solid'
  </script>