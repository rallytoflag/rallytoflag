<script setup>
const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});
</script>
<template>
<Link :href="route('event.show', event.id)">
<div class="h-96 w-full rounded-lg bg-neutral-100 dark:bg-neutral-800
border border-gray-500 hover:border-violet-800 dark:hover:border-violet-300 rounded-lg">
  <img v-if="event.media.length > 0" :src="event.media[0].original_url" alt="event image" 
  class="h-1/2 object-cover w-full rounded-t-lg" />
  <img v-else src="castle.png" alt="event image" 
  class="h-1/2 object-cover w-full rounded-t-lg" />

  <div class="p-4 ">
    <h3 class="text-lg font-bold pb-1">{{ event.title }}</h3>
    <p class="pb-1">{{ event.location }}</p>
    <p class="pb-1"><strong>Starts on </strong>{{ event.start_date }}</p>
    <p v-if="event.end_date !== null" class="pb-1"><strong>Ends on </strong>{{ event.end_date }}</p>
  </div>
</div>
</Link>
</template>
