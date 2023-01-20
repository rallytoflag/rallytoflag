<script setup>
import {onMounted, onUnmounted, ref} from 'vue';
import {Inertia} from '@inertiajs/inertia';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

//get images from props
const images = props.event[0].media;

const photoIndex = ref(0);

const editEvent = () => {
  Inertia.visit(route('event.edit', props.event[0].id));
};

const deleteEvent = () => {
  confirm('Are you sure you want to delete this event?') && Inertia.delete(route('event.destroy', props.event[0].id));
};

const incrementCounter = () => {
  if (photoIndex.value < images.length - 1) {
    photoIndex.value++;
  } else {
    photoIndex.value = 0;
  }
  console.log(photoIndex.value);
};

const decrementCounter = () => {
  if (photoIndex.value > 0) {
    photoIndex.value--;
  } else {
    photoIndex.value = images.length - 1;
  }
  console.log(photoIndex.value);
};

</script>
<template>
  <Head :title="event[0].title" />
<div class="grid place-items-center mx-4">
  <div class="text-lg text-gray-900 dark:text-gray-400 w-full sm:max-w-4xl max-w-md">
    <div v-if="images.length > 0" class="">
      <div v-if="images.length > 1" class="relative w-full h-64 sm:h-96">
        <img v-for="(image, index) in images" :key="index" :class="index === photoIndex ? 'block' : 'hidden'" :src="image.original_url" class="w-full h-64 sm:h-96 object-cover shadow-md dark:shadow-none rounded-lg"   />
        <button
            @click="decrementCounter()"
            class="absolute z-20 inset-y-0 left-0 w-8 group cursor-pointer h-64 sm:h-96">
            <font-awesome-icon icon="fa-solid fa-chevron-left" 
              class="fas fa-chevron-left text-4xl text-gray-50 py-2 px-1 bg-gray-800 bg-opacity-80 hover:bg-opacity-100">
            </font-awesome-icon>
        </button>
        <button
            @click="incrementCounter()"
            class="absolute z-20 inset-y-0 right-0 w-8 group cursor-pointer overflow-x-hidden overflow-y-hidden h-64 sm:h-96">
            <font-awesome-icon icon="fa-solid fa-chevron-right" 
              class="fas fa-chevron-right text-4xl text-gray-50 py-2 px-1 bg-gray-800 bg-opacity-80 hover:bg-opacity-100">
            </font-awesome-icon>
        </button>
      </div>
      <div v-else>
        <img :src="images[0].original_url" class="object-cover w-full h-64 sm:h-96 shadow-md dark:shadow-none rounded-lg" />
      </div>
    </div>
    <div v-else>
      <img src="/castle.png" class="object-cover w-full h-64 sm:h-96 shadow-md dark:shadow-none rounded-lg" />
    </div>

    <div>
      <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100 py-2">{{ event[0].title }}</h1>
    </div>

    <div class="text-xl py-2 ">
      <p v-if="event[0].end_date !== null">{{ event[0].start_date }} until {{ event[0].end_date }}</p>
      <p v-else>{{ event[0].start_date }}</p>
      <p v-if="event[0].start_time !== null">Begins at {{ event[0].start_time }}</p>
    </div>

    <p class="py-2">
      {{ event[0].location }}
    </p>

    <a v-if="event[0].url !== null" :href="event[0].url" class="underline text-violet-700 dark:text-violet-500 py-2">
      {{ event[0].url }}
    </a>

    <p class="pt-4 leading-loose break-words">
      {{ event[0].description }}
    </p>
  </div> 
  <div v-if="$page.props.auth.user">
    <SecondaryButton @click="editEvent" class="mr-4">Edit</SecondaryButton>
    <DangerButton @click="deleteEvent">Delete</DangerButton>
  </div>
</div>
</template>
