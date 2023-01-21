<script setup>
import { Inertia } from '@inertiajs/inertia';
import Input from '@/Components/Input.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, onMounted } from 'vue';

const address = ref('');

const search = () => {
  Inertia.get('/events', {search: address.value, date: ''}, {
    preserveState: true,
    replace: true,
  });
}

</script>

<template>
<Head title="Welcome" />
<div class="h-screen bg-[url('https://upload.wikimedia.org/wikipedia/commons/d/d3/SiegeofAntioch.jpeg')] bg-cover bg-no-repeat ">
  <div class="min-h-screen z-2 flex flex-row justify-center items-center dark:backdrop-filter dark:backdrop-brightness-50">
    <div class="flex flex-wrap items-center justify-center max-w-xs xs:max-w-fit p-4
    bg-neutral-100 dark:bg-neutral-800 border border-gray-800 dark:border-gray-400 
    drop-shadow-md dark:drop-shadow-none rounded-lg">
      <h1 class="text-2xl font-bold text-center w-full mb-4">Find Renaissance fairs and similar events near you.</h1>
      <p class="text-lg sm:pr-4">I'm looking for events near</p>
      <form @submit.prevent="search">
        <Input v-model="address" placeholder="Corvallis, Oregon" />
        <PrimaryButton @submit.prevent="search" class="mt-2 sm:mt-0 sm:ml-4">Search</PrimaryButton>
      </form>
    </div>
  </div>
</div>
</template>
