<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import type { Music } from '../../models/Music'
import { MusicService } from '../../services/MusicService'

const router = useRouter()
const musicService = MusicService.getInstance()
const musics = ref<Music[]>([])
const page = ref(1)
const totalPages = ref(1)
const pageSize = 5
const loading = ref(false)

const filters = reactive({
  title: '',
  start_at: '',
  end_at: ''
})

const fetchMusics = async () => {
  loading.value = true
  try {
    const response = await musicService.list(filters, page.value, pageSize)
    musics.value = response?.data || []
    totalPages.value = response.pagination?.last || 1

  } catch (error) {
    console.error('Erro ao buscar musicas:', error)
    musics.value = []
    totalPages.value = 1
  } finally {
    loading.value = false
  }
}

const deleteMusic = async (id?: number) => {
  if (!id) return
  if (!confirm('Deseja realmente excluir esta liturgia?')) return

  try {
    await musicService.delete(id)
    await fetchMusics()
  } catch (error) {
    console.error('Erro ao excluir liturgia:', error)
  }
}

const prevPage = () => {
  if (page.value > 1) {
    page.value--
    fetchMusics()
  }
}

const nextPage = () => {
  if (page.value < totalPages.value) {
    page.value++
    fetchMusics()
  }
}

onMounted(fetchMusics)
</script>

<template>
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Músicas</h2>
      <button class="btn btn-success" @click="router.push('/musics/new')">Nova Música</button>
    </div>

    <form class="row g-3 mb-3" @submit.prevent="fetchMusics">
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
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Autor</th>
            <th>Tom</th>
            <th>Andamento</th>
            <th class="text-end">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!(musics?.length)">
            <td colspan="5" class="text-center text-muted py-3">Nenhuma música encontrada.</td>
          </tr>
          <tr v-for="mus in musics" :key="mus.id">
            <td>{{ mus.name }}</td>
            <td>{{ mus.author }}</td>
            <td>{{ mus.tone }}</td>
            <td>{{ mus.time_signature }}</td>
            <td class="text-end">
              <button @click="router.push(`/musics/edit/${mus.id}`)" class="btn btn-sm btn-warning me-2">
                Editar
              </button>
              <button @click="deleteMusic(mus.id)" class="btn btn-sm btn-danger">
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
