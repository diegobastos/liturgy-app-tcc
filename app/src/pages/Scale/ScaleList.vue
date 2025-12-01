<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import type { Scale } from '../../models/Scale'
import { ScaleService } from '../../services/ScaleService'
import ScaleCalendar from './ScaleCalendar.vue'

const router = useRouter()
const scaleService = ScaleService.getInstance()
const scales = ref<Scale[]>([])
const page = ref(1)
const totalPages = ref(1)
const pageSize = 5
const loading = ref(false)

const filters = reactive({
  title: '',
  start_at: '',
  end_at: ''
})

const fetchScales = async () => {
  loading.value = true
  try {
    const response = await scaleService.list(filters, page.value, pageSize)
    scales.value = response?.data || []
    totalPages.value = response.pagination?.last || 1

  } catch (error) {
    console.error('Erro ao buscar escalas:', error)
    scales.value = []
    totalPages.value = 1
  } finally {
    loading.value = false
  }
}

const deleteScale = async (id?: number) => {
  if (!id) return
  if (!confirm('Deseja realmente excluir esta escala?')) return

  try {
    await scaleService.delete(id)
    await fetchScales()
  } catch (error) {
    console.error('Erro ao excluir Escala:', error)
  }
}

const prevPage = () => {
  if (page.value > 1) {
    page.value--
    fetchScales()
  }
}

const nextPage = () => {
  if (page.value < totalPages.value) {
    page.value++
    fetchScales()
  }
}

onMounted(fetchScales)
</script>

<template>
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Escalas</h2>
      <button v-if="false" class="btn btn-success" @click="router.push('/scales/new')">Nova Escala</button>
    </div>

    <div class="flex flex-col">
      <ScaleCalendar />
    </div>
  </div>
</template>
