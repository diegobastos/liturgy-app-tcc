<template>
  <div class="container">
    <h2 class="mb-4">{{ isEdit ? 'Editar' : 'Criar' }} Evento</h2>

    <form @submit.prevent="save" class="p-1">
      <section>
        <h5 class="fw-bold mb-3 text-primary">Dados do Evento</h5>

        <div class="row g-3 align-items-end">
          <div class="col-md-4">
            <label class="form-label">Título</label>
            <input
              v-model="event.name"
              type="text"
              class="form-control"
              placeholder="Ex: Culto de Celebração"
              required
            />
          </div>

          <div class="col-md-3 col-sm-6">
            <label class="form-label">Data de Início</label>
            <input
              v-model="event.start_at"
              type="datetime-local"
              class="form-control"
              required
            />
          </div>

          <div class="col-md-3 col-sm-6">
            <label class="form-label">Data de Término</label>
            <input
              v-model="event.end_at"
              type="datetime-local"
              class="form-control"
              required
            />
          </div>
        </div>
      </section>

      <section class="mt-4">
        <h5 class="fw-bold mb-3 text-primary">Atividades do Evento</h5>

        <div class="mb-3">
          <button
            type="button"
            class="btn btn-outline-primary"
            @click="addActivity"
          >
            + Adicionar Atividade
          </button>
        </div>

        <div
          v-for="(activity, index) in event.activities"
          :key="index"
          class="card p-3 mb-3 shadow-sm"
        >
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Atividade {{ index + 1 }}</h6>
            <div>
              <button
                type="button"
                class="btn btn-sm btn-outline-secondary me-1"
                :disabled="index === 0"
                @click="moveUp(index)"
              >
                ↑
              </button>
              <button
                type="button"
                class="btn btn-sm btn-outline-secondary me-1"
                :disabled="index === event.activities.length - 1"
                @click="moveDown(index)"
              >
                ↓
              </button>
              <button
                type="button"
                class="btn btn-sm btn-outline-danger"
                @click="removeActivity(index)"
              >
                ✕
              </button>
            </div>
          </div>

          <div class="row g-3 align-items-end">
            <div class="col-md-6">
              <label class="form-label">Notas / Descrição</label>
              <input
                v-model="activity.notes"
                type="text"
                class="form-control"
                placeholder="Ex: Abertura, Mensagem, Oração..."
              />
            </div>

            <div class="col-md-6">
              <label class="form-label">Música (opcional)</label>
              <select v-model="activity.music_id" class="form-select">
                <option :value="null">— Nenhuma —</option>
                <option
                  v-for="music in musics"
                  :key="music.id"
                  :value="music.id"
                >
                  {{ music.name }} — {{ music.author }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <div v-if="!event.activities.length" class="text-muted small mt-2">
          Nenhuma atividade adicionada ainda.
        </div>
      </section>

      <div class="d-flex justify-content-end mt-4">
        <router-link to="/events" class="btn btn-secondary me-2">
          Cancelar
        </router-link>
        <button type="submit" class="btn btn-primary">
          {{ isEdit ? 'Atualizar' : 'Criar' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useNotify } from '@/composables/useNotify'
import type { Event } from '../../models/Event'
import { EventService } from '../../services/EventService'
import { MusicService } from '../../services/MusicService'
import type { Music } from '../../models/Music'

const route = useRoute()
const router = useRouter()
const eventService = EventService.getInstance()
const musicService = MusicService.getInstance()
const { success, error } = useNotify()

const event = reactive<Event>({
  name: '',
  start_at: undefined,
  end_at: undefined,
  activities: []
})

const musics = ref<Music[]>([])
const isEdit = ref(false)

function addActivity() {
  const position = event.activities.length + 1
  event.activities.push({
    music_id: null,
    position,
    notes: ''
  })
}

function removeActivity(index: number) {
  event.activities.splice(index, 1)
  event.activities.forEach((a, i) => (a.position = i + 1))
}

function swap(arr: any[], i: number, j: number) {
  const temp = arr[i]
  arr[i] = arr[j]
  arr[j] = temp
}

function moveUp(index: number) {
  if (index === 0) return
  swap(event.activities, index, index - 1)
  event.activities.forEach((a, i) => (a.position = i + 1))
}

function moveDown(index: number) {
  if (index === event.activities.length - 1) return
  swap(event.activities, index, index + 1)
  event.activities.forEach((a, i) => (a.position = i + 1))
}

watch (
  () => event.start_at,
  (start_at) => {
    
    if (!start_at) {
      event.end_at = undefined
      return
    }

    if (event.end_at == undefined) {
      event.end_at = start_at
    }
  }
)

onMounted(async () => {
  const musicResponse = await musicService.list()
  musics.value = musicResponse.data

  const id = route.params.id
  if (id) {
    isEdit.value = true
    const data = await eventService.get(Number(id))
    Object.assign(event, {
      ...data,
      activities: Array.isArray(data.activities)
        ? data.activities.map((a, i) => ({
            id: a.id ?? null,
            music_id: a.music_id ?? null,
            notes: a.notes ?? '',
            position: a.position ?? i + 1
          }))
        : []
    })
  }
})

const save = async () => {
  try {
    if (isEdit.value && event.id) {
      await eventService.update(event.id, event)
    } else {
      await eventService.create(event)
    }
    success('Evento salvo com sucesso')
    router.push('/events')
  } catch (err) {
    error(`Erro ao salvar evento`)
    console.error('Erro ao salvar evento:', err)
  }
}
</script>
