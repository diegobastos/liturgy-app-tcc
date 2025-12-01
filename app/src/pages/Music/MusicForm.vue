<template>
  <div class="container">
    <h2 class="mb-4">{{ isEdit ? 'Editar' : 'Cadastrar' }} Música</h2>

    <form @submit.prevent="save" class="p-1">
      <section>
        <h5 class="fw-bold mb-3 text-primary">Informações da Música</h5>

        <div class="row g-3 mb-3">
          <div class="col-md-6 col-lg-4">
            <label class="form-label fw-semibold small">Título</label>
            <input v-model="music.name" type="text" class="form-control" required />
          </div>

          <div class="col-md-6 col-lg-4">
            <label class="form-label fw-semibold small">Autor</label>
            <input v-model="music.author" type="text" class="form-control" required />
          </div>

          <div class="col-md-6 col-lg-4">
            <label class="form-label fw-semibold small">Tom</label>
            <input v-model="music.tone" type="text" class="form-control" placeholder="Ex: D, F#, Bb" required />
          </div>

          <div class="col-md-6 col-lg-4">
            <label class="form-label fw-semibold small">Andamento</label>
            <input v-model="music.time_signature" type="text" class="form-control" placeholder="Ex: 4/4, 3/4"
              required />
          </div>
        </div>
      </section>

      <div class="d-flex justify-content-end mt-4">
        <router-link to="/musics" class="btn btn-secondary me-2">
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
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { MusicService } from '../../services/MusicService'
import type { Music } from '../../models/Music'
import { useNotify } from '@/composables/useNotify'

const route = useRoute()
const router = useRouter()
const { success, error } = useNotify()
const musicService = MusicService.getInstance()
const music = ref<Music>({
  uuid: '', 
  name: '', 
  author: '', 
  tone: '', 
  time_signature: '', 
  lyrics: null, 
  music_score: null
})
const isEdit = ref(false)

onMounted(async () => {
  const id = route.params.id
  if (id) {
    isEdit.value = true
    music.value = await musicService.get(Number(id))
  }
})

const save = async () => {
  try {
    if (isEdit.value && music.value.id) {
      await musicService.update(music.value.id, music.value)
    } else {
      await musicService.create(music.value)
    }
    success('Música salva com sucesso')
    router.push('/musics')
  } catch (err) {
    error(`Falha ao salvar música`)
    console.error('Erro ao salvar música:', err)
  }
}
</script>
