<template>
  <nav class="fixed bg-white shadow-md w-full flex flex-wrap justify-between z-50" style="max-width:1400px !important;">
    <div class="p-7">
      <inertia-link href="/">BMIID</inertia-link>
    </div>
    <div class="block md:hidden p-5">
      <button @click="show=!show" class="flex items-center px-3 py-2 border rounded text-black border-blue-400 hover:border-blue-900">
        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
      </button>
    </div>
    <ul class="flex flex-row ulLink1">
      <!-- <li class="py-7">
        <inertia-link href="/" class="text-blue-400 py-2 px-7 border-2 border-blue-400 registerHover">Register</inertia-link>
      </li> -->
      <inertia-link href="/">
        <li class="p-7 cursor-pointer homeLink hover:border-black">Home</li>
      </inertia-link>
      <inertia-link href="/map">
        <li class="p-7 linkHover cursor-pointer" :class="{'active': $inertia.page.url.startsWith('/map')}">Map</li>
      </inertia-link>
      <inertia-link href="/about">
        <li class="p-7 linkHover cursor-pointer" :class="{'active': $inertia.page.url.startsWith('/about')}">About</li>
      </inertia-link>
      <inertia-link href="/contact">
        <li class="p-7 linkHover cursor-pointer" :class="{'active': $inertia.page.url.startsWith('/contact')}">Contact</li>
      </inertia-link>
      <!-- <inertia-link href="/contact">
        <li class="p-7 linkHover cursor-pointer" :class="{'active': $inertia.page.url.startsWith('/Login')}">Login</li>
      </inertia-link> -->
    </ul>
    <transition name="fade">
      <div v-if="show" :class="{'block md:hidden':show}" class="w-full block flex-grow md:flex md:justify-end md:w-auto">
        <ul class="px-5">
          <!-- <li class="py-7">
            <inertia-link href="/" class="text-blue-400 py-2 px-7 border-2 border-blue-400 registerHover">Register</inertia-link>
          </li> -->
          <inertia-link href="/">
            <li class="p-7 cursor-pointer homeLink hover:border-black">Home</li>
          </inertia-link>
          <inertia-link href="/map">
            <li class="p-7 homeLink hover:border-black cursor-pointer" :class="{'active': $inertia.page.url.startsWith('/map')}">Map</li>
          </inertia-link>
          <inertia-link href="/about">
            <li class="p-7 homeLink hover:border-black cursor-pointer" :class="{'active': $inertia.page.url.startsWith('/about')}">About</li>
          </inertia-link>
          <inertia-link href="/contact">
            <li class="p-7 homeLink hover:border-black cursor-pointer" :class="{'active': $inertia.page.url.startsWith('/contact')}">Contact</li>
          </inertia-link>
          <!-- <inertia-link href="/contact">
            <li class="p-7 homeLink hover:border-black cursor-pointer" :class="{'active': $inertia.page.url.startsWith('/Login')}">Login</li>
          </inertia-link> -->
        </ul>
      </div>
    </transition>
  </nav>
</template>

<style scoped>
  @import '../../css/navbar.css';
</style>

<script>
  export default {
    data(){
      return {
        show: false,
      }
    }
  }
</script>