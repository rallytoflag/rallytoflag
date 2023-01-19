<style> 
.mapboxgl-ctrl-geocoder--suggestion:hover {
  cursor: pointer;
  background-color: #78716c;
}
#map-container {
  height: 400px;
  width: 100%;
}
#map {
  height: 100%;
  width: 100%;
}
</style>

<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import Input from '@/Components/Input.vue';
import InputError from '@/Components/InputError.vue';
import EventCard from '@/Components/EventCard.vue';
import Paginator from '@/Components/Paginator.vue';
import {ref, computed, onMounted, watch} from 'vue';
import {Inertia} from '@inertiajs/inertia';
import { useDark } from '@vueuse/core';

const props = defineProps({
    events: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: false,
    },
});

const mapboxToken = import.meta.env.VITE_MAPBOX;
mapboxgl.accessToken = mapboxToken;

let map = ref(null);
let markers = ref([]);
let currentState = ref(null);
const search = ref(props.filters.search || '');
const start_date = ref(props.filters.start_date || '');
const isDark = useDark();

const resetMarkers = () => {
  markers.value.forEach(marker => {
    marker.remove();
  });
  markers.value = [];
}

const updateMarkers = (map) =>{
  resetMarkers();
  props.events.data.forEach(event => {
    if (event.location) {
      const marker = new mapboxgl.Marker()
        .setLngLat([event.longitude, event.latitude])
        .addTo(map);
      markers.value.push(marker);
    }
  });
}

const initMap = () => {
  map = new mapboxgl.Map({
    container: 'map', // container ID
    style: 'mapbox://styles/mapbox/streets-v12', // style URL
    center: [-87.6500523, 41.850033], // starting position [lng, lat]
    zoom: 4, // starting zoom
  });

  if (isDark.value) {
    map.setStyle('mapbox://styles/mapbox/dark-v11');
  }
}

const initGeocoder = () => {
  const geocoder = new MapboxGeocoder({
    accessToken: mapboxToken,
    mapboxgl: mapboxgl,
    countries: 'us',
    types: 'address',
  });

  document.getElementById('geocoder').appendChild(geocoder.onAdd(map));

  $('.mapboxgl-ctrl-geocoder--icon-search').remove();
  $('.mapboxgl-ctrl-geocoder--input')
    .addClass("w-full border-gray-300 focus:border-violet-500 focus:ring-violet-500 rounded-md bg-white dark:bg-neutral-700 drop-shadow-sm dark:drop-shadow-none")
    .removeClass("mapboxgl-ctrl-geocoder--input");
  $('.mapboxgl-ctrl-geocoder')
    .addClass("w-full ")
    .removeClass("mapboxgl-ctrl")
    .removeClass("mapboxgl-ctrl-geocoder");
    $('.mapboxgl-ctrl-geocoder--pin-right').remove();
    $('.suggestions').addClass("bg-white dark:bg-neutral-700 rounded absolute z-10");
    $('.suggestions').removeClass("suggestions");
}

onMounted(
  () => {
  initMap();
  initGeocoder();
});

watch(
  () => [search.value, start_date.value],
  ([search, start_date]) => {
    Inertia.get('/events', {search: search, date: start_date}, {
      preserveState: true,
      replace: true,
    });
  },
  {immediate: true}
);

watch(
  () => props.events,
  (events) => {
    if (map) {
      updateMarkers(map);
    }
  },
);


</script>
<template>
<Head title="Events" />
<div id="map-container">
  <div id="map"></div>
</div>
<div class="grid lg:md:sm:grid-cols-12 xs:grid-cols-1 gap-4 p-4">
  <div class="col-span-2 flex flex-col">
    <InputLabel for="geocoder" value="Filter by location" class=""/>
    <div id="geocoder" @input="event => search = event.target.value"></div>
    <InputLabel for="start_date" value="Filter by date" class="mt-4"/>
    <Input type="date" id="start_date" name="start_date" v-model="start_date" class=""/>
  </div>

  <div class="col-span-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <EventCard v-for="event in events.data" :key="event.id" :event="event" />
    </div>
    <Paginator :links="events.links" class="mt-4"/>
  </div>
</div>
</template>
