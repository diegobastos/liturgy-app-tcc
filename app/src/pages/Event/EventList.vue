<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { EventService } from '../../services/EventService'
import type { Event } from '../../models/Event'
import { useRouter } from 'vue-router'
import { ScaleService } from '../../services/ScaleService'

const router = useRouter()
const scaleService = ScaleService.getInstance()
const eventService = EventService.getInstance()
const events = ref<Event[]>([])
const page = ref(1)
const totalPages = ref(1)
const pageSize = 5
const loading = ref(false)

const filters = reactive({
  title: '',
  start_at: '',
  end_at: ''
})

const fetchEvents = async () => {
  loading.value = true
  try {
    const response = await eventService.list(filters, page.value, pageSize)
    events.value = response?.data || []
    totalPages.value = response.pagination?.last || 1

  } catch (error) {
    console.error('Erro ao buscar eventos:', error)
    events.value = []
    totalPages.value = 1
  } finally {
    loading.value = false
  }
}

const deleteEvent = async (id?: number) => {
  if (!id) return
  if (!confirm('Deseja realmente excluir este evento?')) return

  try {
    await eventService.delete(id)
    await fetchEvents()
  } catch (error) {
    console.error('Erro ao excluir evento: ', error)
  }
}

const createScale = async(event: Event) => {
  if (!event) return
  try {
    const scale = await scaleService.createPartial({
      event_id: event.id,
      scale_type_id: 1, //louvor
      notes: `Escala automática - ${event.name}`,
      start_at: event.start_at,
      end_at: event.end_at, 
    })
    await fetchEvents()
    alert(`Escala ${scale.id} criada com sucesso!`)
  } catch (error) {
    console.error('Erro ao criar escala: ', error)
  }
}

const prevPage = () => {
  if (page.value > 1) {
    page.value--
    fetchEvents()
  }
}

const nextPage = () => {
  if (page.value < totalPages.value) {
    page.value++
    fetchEvents()
  }
}

onMounted(fetchEvents)
</script>

<template>
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Liturgias</h2>
      <button class="btn btn-success" @click="router.push('/events/new')">Novo Evento</button>
    </div>

    <form class="row g-3 mb-3" @submit.prevent="fetchEvents">
      <div class="col-md-4">
        <input v-model="filters.title" type="text" class="form-control" placeholder="Título">
      </div>
      <div class="col-md-4">
        <input v-model="filters.start_at" type="date" class="form-control">
      </div>
      <div class="col-md-4">
        <input v-model="filters.end_at" type="date" class="form-control">
      </div>      
      <div class="col-md-4">
        <button type="submit" class="btn btn-primary">Filtrar</button>
      </div>
    </form>

    <div v-if="loading" class="text-center py-4">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Carregando...</span>
      </div>
    </div>

    <div v-else class="table-responsive">
      <table  class="table table-striped align-middle">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Início</th>
            <th>Fim</th>
            <th class="text-end">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!(events?.length)">
            <td colspan="4" class="text-center text-muted py-3">Nenhum evento encontrado.</td>
          </tr>
          <tr v-for="ev in events" :key="ev.id">
            <td>{{ ev.name }}</td>
            <td>{{ new Date(ev.start_at||'').toLocaleDateString('pt-BR') }}</td>
            <td>{{ new Date(ev.end_at||'').toLocaleDateString('pt-BR') }}</td>
            <td class="text-end">
              <button @click="createScale(ev)" class="btn btn-sm btn-info me-2">
                Criar Escala
              </button>            
              <button @click="router.push(`/events/edit/${ev.id}`)" class="btn btn-sm btn-warning me-2">
                Editar
              </button>
              <button @click="deleteEvent(ev.id)" class="btn btn-sm btn-danger">
                Excluir
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <nav v-if="totalPages > 1" class="mt-3">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled: page === 1 }">
          <button class="page-link" @click="prevPage">Anterior</button>
        </li>
        <li class="page-item disabled">
          <span class="page-link">{{ page }} / {{ totalPages }}</span>
        </li>
        <li class="page-item" :class="{ disabled: page === totalPages }">
          <button class="page-link" @click="nextPage">Próximo</button>
        </li>
      </ul>
    </nav>
  </div>
</template>
