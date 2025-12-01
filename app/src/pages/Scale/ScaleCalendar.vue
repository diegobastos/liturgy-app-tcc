<script setup lang="ts">
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import ptBrLocale from '@fullcalendar/core/locales/pt-br'
import { onMounted, onBeforeUnmount, ref } from 'vue'
import { useRouter } from 'vue-router'
import { ScaleService } from '../../services/ScaleService'
import type { CalendarOptions, EventClickArg } from '@fullcalendar/core'
import type { ScaleResponse } from '../../models/Scale'

const router = useRouter()
const scaleService = ScaleService.getInstance()
const fullCalendarRef = ref<any>(null)

const handleEventClick = (info: EventClickArg) => {
  const id = info.event.id
  if (id) router.push({ name: 'ScaleEdit', params: { id } })
}

const handleEventDrop = async (info: any) => {
  const { event } = info
  const id = event.id
  const newStart = event.start
  const newEnd = event.end || event.start // pode ser nulo se for evento de 1 dia só

  try {
    await scaleService.update(id, {
      start_at: newStart,
      end_at: newEnd,
    })
    console.log(`✅ Evento ${id} movido para ${newStart}`)
  } catch (error) {
    console.error('Erro ao atualizar evento:', error)
    info.revert() // 🔙 reverte visualmente o evento no calendário
  }
}

const handleEventResize = async (info: any) => {
  const { event } = info
  const id = event.id
  const newStart = event.start
  const newEnd = event.end

  try {
    await scaleService.updatePartial(id, {
      start_at: newStart,
      end_at: newEnd,
    })
    console.log(`✅ Evento ${id} redimensionado até ${newEnd}`)
  } catch (error) {
    console.error('Erro ao redimensionar evento:', error)
    info.revert()
  }
}

const calendarOptions = ref<CalendarOptions>({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  locale: ptBrLocale,
  editable: true,
  selectable: true,
  droppable: true,
  eventDrop: handleEventDrop,
  eventResize: handleEventResize,

  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,dayGridWeek,dayGridDay'
  },
  events: [],
  eventClick: handleEventClick
})

function adjustCalendarLayout() {
  const calendarApi = fullCalendarRef.value?.getApi?.()
  if (!calendarApi) return

  if (window.innerWidth < 768) {
    calendarApi.setOption('headerToolbar', {
      left: 'prev,next',
      center: 'title',
      right: 'today'
    })
    calendarApi.setOption('titleFormat', { month: '2-digit', year: 'numeric' })
    calendarApi.setOption('contentHeight', 'auto')
    document.documentElement.style.setProperty('--fc-font-size', '0.75rem')
  } else {
    calendarApi.setOption('headerToolbar', {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,dayGridWeek,dayGridDay'
    })
    calendarApi.setOption('titleFormat', { month: 'long', year: 'numeric' })
    calendarApi.setOption('contentHeight', 'auto')
    document.documentElement.style.setProperty('--fc-font-size', '1rem')
  }
}

const fetchScales = async () => {
  try {
    const response = await scaleService.list<ScaleResponse>()

    const mappedEvents = response.data
      .filter(scale => scale.start_at || scale.event?.start_at)
      .map(scale => ({
        id: String(scale.id),
        title: scale.event?.name || scale.type?.name || 'Escala',
        start: scale.start_at || scale.event?.start_at,
        end: scale.end_at || scale.event?.end_at,
        extendedProps: {
          notes: scale.notes,
          members: scale.members.map(m => `${m.user.name} (${m.role})`).join(', ')
        }
      }))

    calendarOptions.value.events = mappedEvents
  } catch (error) {
    console.error('Erro ao carregar escalas:', error)
  }
}

onMounted(() => {
  fetchScales()
  adjustCalendarLayout()
  window.addEventListener('resize', adjustCalendarLayout)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', adjustCalendarLayout)
})
</script>

<template>
  <div class="container-fluid p-2">
    <FullCalendar ref="fullCalendarRef" :options="calendarOptions" />
  </div>
</template>

<style>
:root {
  --fc-font-size: 1rem;
}

.fc {
  font-size: var(--fc-font-size);
}

/* --- MOBILE MODE --- */
@media (max-width: 768px) {
  .fc-toolbar-title {
    font-size: 0.9rem !important;
    font-weight: 600;
  }

  .fc-daygrid-day-frame {
    padding: 1px !important;
  }

  .fc-col-header-cell-cushion {
    font-size: 0.65rem !important;
    padding: 2px 0 !important;
  }

  .fc-daygrid-day-top {
    font-size: 0.7rem !important;
  }

  .fc-toolbar-chunk {
    margin: 0 !important;
  }

  .fc-button {
    font-size: 0.7rem !important;
    padding: 0.2rem 0.4rem !important;
  }

  .fc-scroller {
    overflow: hidden !important;
  }
}
</style>
