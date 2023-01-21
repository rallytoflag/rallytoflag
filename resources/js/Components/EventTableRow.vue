<script setup>
import { Inertia } from '@inertiajs/inertia'
import SecondaryButton from './SecondaryButton.vue';
import DangerButton from './DangerButton.vue';
const props = defineProps({
    event: Object,
});

const editEvent = () => {
  Inertia.visit(route('event.edit', props.event.id));
};

const deleteEvent = () => {
  confirm('Are you sure you want to delete this event?') && Inertia.delete(route('event.destroy', props.event.id));
};

</script>
<template>
<tr>
  <td class="py-2">
    <Link :href="route('event.show', event.id)">
    <img v-if="event.media.length > 0" :src="event.media[0].original_url" class="w-10 h-10 rounded" />
      <img v-else src="/castle.png" class="w-10 h-10 rounded" />
    </Link>
  </td>
  <td class="break-words">{{ event.title }}</td>
  <td class="xs:hidden">{{ event.start_date }}</td>
  <td><SecondaryButton @click="editEvent">Edit</SecondaryButton></td>
  <td><DangerButton @click="deleteEvent">Delete</DangerButton></td>
</tr>
</template>
