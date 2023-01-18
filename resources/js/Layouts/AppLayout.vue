<script setup>
import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';
</script>

<template>
<div class="bg-stone-100 font-main dark:bg-stone-900 text-gray-700 dark:text-gray-300">
  <NavBar />
  <main class="min-h-screen">
    <slot />
  </main>
  <Footer />
</div>
</template>
<style global>
html.dark {
  color-scheme: dark;
}
</style>
