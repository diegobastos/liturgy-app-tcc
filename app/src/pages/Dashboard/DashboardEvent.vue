<template>
  <div v-if="loading" class="text-center py-5">
    <div class="spinner-border text-primary"></div>
    <p class="mt-3 text-muted">Carregando agenda...</p>
  </div>

  <div v-else class="container-fluid py-3">

    <h4 class="mb-3 text-secondary">Eventos</h4>

    <!-- Linha 1: Cards Resumo -->
    <div class="row g-3 mb-3">

      <div class="col-md-4">
        <Card 
          class="small-card"
          title="Eventos Agendados"
          icon="bi bi-calendar-check"
          :value="summary.agendados"
        />
      </div>

      <div class="col-md-4">
        <Card 
          class="small-card"
          title="Eventos Neste Mês"
          icon="bi bi-calendar-event"
          :value="summary.mes_atual"
        />
      </div>

      <div class="col-md-4">
        <Card 
          class="small-card"
          title="Eventos Nesta Semana"
          icon="bi bi-calendar-week"
          :value="summary.semana_atual"
        />
      </div>

    </div>

    <!-- Linha 2: Próximos eventos -->
    <div class="card shadow-sm border-0 small-card mb-4">
      <div class="card-body py-3 px-3">

        <h6 class="fw-semibold mb-3">Próximos eventos</h6>

        <ul class="list-group list-group-flush">

          <li 
            v-for="event in nextEvents" 
            :key="event.id"
            class="list-group-item d-flex justify-content-between align-items-center"
          >

            <div>
              <div class="fw-semibold">{{ event.name }}</div>
              <div class="text-muted small">
                {{ formatDate(event.start_at) }} — {{ event.scale_type }} ({{ event.role }})
              </div>
            </div>

            <span 
              class="badge"
              :class="{
                'bg-secondary': event.status === 'PENDING',
                'bg-success': event.status === 'CONFIRMED',
                'bg-danger': event.status === 'ABSENT',
                'bg-warning': event.status === 'REPLACED'
              }"
            >
              {{ event.status }}
            </span>

          </li>

          <li v-if="nextEvents.length === 0" class="list-group-item text-muted text-center fst-italic">
            Nenhum evento futuro.
          </li>

        </ul>

      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { onMounted, computed } from "vue"
import { useDashboardStore } from "@/stores/eventDashboardStore"
import Card from "@/components/dashboard/Card.vue"

const store = useDashboardStore()

onMounted(() => {
  store.fetchDashboard()
})

const loading = computed(() => store.loading)
const summary = computed(() => store.summary)
const nextEvents = computed(() => store.nextEvents)

function formatDate(dt: string) {
  const d = new Date(dt)
  return d.toLocaleString("pt-BR", {
    weekday: "short",
    day: "2-digit",
    month: "short",
    hour: "2-digit",
    minute: "2-digit"
  })
}
</script>


<style scoped>
.small-card .card-body {
  padding: 0.8rem 1rem !important;
}

.list-group-item {
  padding: 0.65rem 0.75rem !important;
}
</style>
