<script setup>
import {onMounted} from 'vue';
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
  pswpModule: () => import('photoswipe')
});

onMounted(() => {
  lightbox.init();
});

const editEvent = () => {
  Inertia.visit(route('event.edit', props.event.id));
};

const deleteEvent = () => {
  confirm('Are you sure you want to delete this event?') && Inertia.delete(route('event.destroy', props.event.id));
};

</script>
<template>
<div class="flex flex-col sm:justify-center items-center pt-6 pb-10">
  <div class="text-lg text-gray-900 dark:text-gray-400 w-full lg:md:max-w-4xl sm:max-w-md">
    <div v-if="images.length > 0" class="flex flex-wrap justify-center" id="gallery">
      <div v-if="images.length > 1">
        <div class="grid grid-cols-2 gap-2 xs:grid-cols-1">
          <div v-for="image in images" :key="image.id" >
            <a :href="image.original_url" >
              <img :src="image.original_url" class="object-cover w-full h-full shadow-md dark:shadow-none rounded-lg" />
            </a>
          </div>
        </div>
      </div>
      <div v-else>
        <img :src="images[0].original_url" class="object-cover w-full h-full shadow-md dark:shadow-none rounded-lg" />
      </div>
    </div>
    <div v-else>
      <img src="/castle.png" class="object-cover w-full h-full shadow-md dark:shadow-none rounded-lg" />
    </div>

    <div>
      <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100 py-2">{{ event.title }}</h1>
    </div>

    <div class="text-xl py-2 ">
      <p v-if="event.end_date !== null">{{ event.start_date }} until {{ event.end_date }}</p>
      <p v-else>{{ event.start_date }}</p>
      <p v-if="event.start_time !== null">Begins at {{ event.start_time }}</p>
    </div>

    <p class="py-2">
      {{ event.location }}
    </p>

    <a v-if="event.url !== null" :href="event.url" class="underline text-violet-700 dark:text-violet-500 py-2">
      {{ event.url }}
    </a>

    <p class="pt-4 leading-loose break-words">
      {{ event.description }}
    </p>
  </div> 
  <div v-if="$page.props.auth.user">
    <SecondaryButton @click="editEvent" class="mr-4">Edit</SecondaryButton>
    <DangerButton @click="deleteEvent">Delete</DangerButton>
  </div>
</div>
</template>
