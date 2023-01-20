<script setup>
import {onMounted, onUnmounted} from 'vue';
import {Inertia} from '@inertiajs/inertia';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

const images = props.event[0].media;

import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';

const lightbox = new PhotoSwipeLightbox({
  gallery: '#gallery',
  children: 'a',
  pswpModule: () => import('photoswipe'),
  initialZoomLevel: 'fit',
});

onMounted(() => {
  lightbox.init();
});

onUnmounted(() => {
  lightbox.destroy();
});

const editEvent = () => {
  Inertia.visit(route('event.edit', props.event[0].id));
};

const deleteEvent = () => {
  confirm('Are you sure you want to delete this event?') && Inertia.delete(route('event.destroy', props.event[0].id));
};

</script>
<template>
  <Head :title="event[0].title" />
<div class="grid place-items-center mx-4">
  <div class="text-lg text-gray-900 dark:text-gray-400 w-full lg:md:max-w-4xl sm:max-w-md">
    <div v-if="images.length > 0" class="flex flex-wrap justify-center" id="gallery">
      <div v-if="images.length > 1" class="relative w-full">
        <a v-for="(image, index) in images" :href="image.original_url" :key="index" :class="index === 0 ? 'block' : 'hidden'">
          <img :src="image.original_url" class="w-full max-h-96 object-cover shadow-md dark:shadow-none rounded-lg"   />
          <SecondaryButton class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <span class="text-2xl">View Gallery</span>
          </SecondaryButton>
        </a>
      </div>
      <div v-else>
        <img :src="images[0].original_url" class="object-cover w-full max-h-96 shadow-md dark:shadow-none rounded-lg" />
      </div>
    </div>
    <div v-else>
      <img src="/castle.png" class="object-cover w-full h-full shadow-md dark:shadow-none rounded-lg" />
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
