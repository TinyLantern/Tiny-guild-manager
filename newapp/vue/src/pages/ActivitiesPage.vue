<template>
  <div class="calendar-container">
    <CalendarNavigation @prev="calendarPrev" @next="calendarNext" @new-activity="showForm = true" />
    <q-separator />
    <div class="calendar-container-inner row">
      <q-calendar
        ref="calendarRef"
        v-model="selectedDate"
        view="week"
        locale="en-us"
        :hour24-format="true"
        :interval-minutes="60"
        :weekdays="[1, 2, 3, 4, 5, 6, 0]"
        :interval-count="24"
        :interval-start="0"
        animated
        bordered
      >
        <template #interval="{ interval }">
          <div class="text-center">
            {{ interval.time }}:00
          </div>
        </template>
        <template #day-body="{ scope: { timestamp, timeStartPos, timeDurationHeight } }">
          <template v-if="timestamp?.date">
            <template v-for="event in getEvents(timestamp.date)" :key="event.id">
              <CalendarEventBadge
                :event="event"
                :timeStartPos="timeStartPos"
                :timeDurationHeight="timeDurationHeight"
                @click="goToEvent(event.id)"
              />
            </template>
          </template>
        </template>
      </q-calendar>
    </div>

    <CalendarEventForm v-model:showForm="showForm" @save="addEvent" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { QCalendar } from '@quasar/quasar-ui-qcalendar';
import { useAuthStore } from 'src/stores/auth';
import { useRouter } from 'vue-router';
import axios from 'axios';
import CalendarNavigation from '/src/components/Calendar/CalendarNavigation.vue';
import CalendarEventBadge from '/src/components/Calendar/CalendarEventBadge.vue';
import CalendarEventForm from '/src/components/Calendar/CalendarEventForm.vue';

const authStore = useAuthStore();
const router = useRouter();
const selectedDate = ref(new Date().toISOString().split("T")[0]);
const calendarRef = ref(null);
const events = ref([]);
const showForm = ref(false);

const fetchEvents = async () => {
  try {
    const response = await axios.get("http://newapp.test/api/activities", {
      headers: { Authorization: `Bearer ${authStore.token}` },
      withCredentials: true,
    });

    events.value = response.data.map((event) => {
      const startTime = new Date(event.start_time);
      const endTime = new Date(event.end_time);

      return {
        id: event.id,
        title: event.name,
        date: startTime.toISOString().split("T")[0],
        time: startTime.toTimeString().split(" ")[0].substring(0, 5),
        duration: (endTime - startTime) / (1000 * 60),
        color: getColorForEvent(event.dkp),
        start_time: event.start_time,
        end_time: event.end_time,
        dkp: event.dkp,
      };
    });

  } catch (error) {
    console.error("Error fetching activities:", error);
  }
};

const calendarNext = () => calendarRef.value?.next();
const calendarPrev = () => calendarRef.value?.prev();

const goToEvent = (activityId) => router.push(`/activity/${activityId}`);

const getColorForEvent = (dkp) => {
  console.log("DKP Value:", dkp);
  if (dkp >= 1000) return "bg-purple";
  if (dkp >= 900) return "bg-deep-red";
  if (dkp >= 800) return "bg-dark-red";
  if (dkp >= 700) return "bg-orange";
  if (dkp >= 600) return "bg-deep-orange";
  if (dkp >= 500) return "bg-red";
  if (dkp >= 400) return "bg-light-green";
  if (dkp >= 300) return "bg-green";
  if (dkp >= 200) return "bg-teal";
  if (dkp >= 100) return "bg-blue";
  return "bg-gray";
};

const getEvents = (date) => {
  if (!date || !events.value) return [];

  const dayEvents = events.value.filter((event) => event.date === date);
  dayEvents.sort((a, b) => new Date(a.start_time) - new Date(b.start_time));

  let columns = [];
  dayEvents.forEach((event, index) => {
    event.hasOverlap = false;
    for (let prevEvent of dayEvents.slice(0, index)) {
      if (isOverlapping(prevEvent, event)) {
        event.hasOverlap = true;
        prevEvent.hasOverlap = true;
      }
    }
  });

  dayEvents.forEach((event) => {
    let placed = false;
    if (!event.hasOverlap) {
      event.column = 0;
      event.totalColumns = 1;
      columns.push([event]);
      return;
    }

    for (let i = 0; i < columns.length; i++) {
      if (!columns[i].some((e) => isOverlapping(e, event))) {
        columns[i].push(event);
        event.column = i;
        event.totalColumns = 2;
        placed = true;
        break;
      }
    }

    if (!placed) {
      columns.push([event]);
      event.column = columns.length - 1;
      event.totalColumns = 2;
    }
  });

  return dayEvents;
};

const isOverlapping = (event1, event2) => !(
  new Date(event1.end_time) <= new Date(event2.start_time) ||
  new Date(event2.end_time) <= new Date(event1.start_time)
);

const addEvent = async (newEvent) => {
  try {
    const response = await axios.post(
      "http://newapp.test/api/createactivity",
      newEvent,
      {
        headers: { Authorization: `Bearer ${authStore.token}` },
        withCredentials: true,
      }
    );

    console.log("Activity Created:", response.data);
    fetchEvents();
    showForm.value = false;
  } catch (error) {
    console.error("Error creating activity:", error);
  }
};

onMounted(fetchEvents);
</script>

<style scoped lang="scss">
.calendar-container-inner {
  display: flex;
  justify-content: center;
  width: 100%;
}

.calendar-container {
  justify-content: center;
  padding: 16px;
  width: 100%;
}

.bg-gray {
  background-color: #9e9e9e;
}
.bg-blue {
  background-color: #1976d2;
}
.bg-teal {
  background-color: #00897b;
}
.bg-green {
  background-color: #388e3c;
}
.bg-light-green {
  background-color: #8bc34a;
}
.bg-red {
  background-color: #d32f2f;
}
.bg-deep-orange {
  background-color: #ff5722;
}
.bg-orange {
  background-color: #ff9800;
}
.bg-dark-red {
  background-color: #b71c1c;
}
.bg-deep-red {
  background-color: #880e4f;
}
.bg-purple {
  background-color: #6a1b9a;
}

</style>