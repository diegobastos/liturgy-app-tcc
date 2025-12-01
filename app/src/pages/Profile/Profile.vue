<template>
  <div class="container-fluid py-4">
    <h2 class="mb-4">{{ isEdit ? 'Editar' : 'Cadastrar' }} Perfil</h2>

    <form @submit.prevent="save" class="p-1" v-if="!loading">
      <section>
        <h5 class="fw-bold mb-3 text-primary">Informações Pessoais</h5>

        <div class="row g-3 mb-3">
          <div class="col-md-6 col-lg-4">
            <label class="form-label fw-semibold small">Nome Completo</label>
            <input v-model="user.name" type="text" class="form-control form-control-sm" required />
          </div>

          <div class="col-md-6 col-lg-4">
            <label class="form-label fw-semibold small">Apelido / Username</label>
            <input v-model="user.username" type="text" class="form-control form-control-sm" required />
          </div>

          <div class="col-md-6 col-lg-4">
            <label class="form-label fw-semibold small">E-mail</label>
            <input v-model="user.email" type="email" class="form-control form-control-sm" required />
          </div>

          <div class="col-md-4">
            <TimezoneSelect v-model="user.timezone" />
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold small">Habilidades</label>
            <input v-model="user.roles" type="text" class="form-control form-control-sm"
              placeholder="Ex: Voz, Guitarra, Violão, Bateria, etc." />
          </div>

          <div class="col-md-2">
            <div class="form-check form-switch d-flex align-items-center">
              <input class="form-check-input me-2" id="ativoSwitch" type="checkbox" v-model="user.active"
                :true-value="1" :false-value="0" />
              <label class="form-check-label" for="ativoSwitch">Ativo</label>
            </div>
          </div>
        </div>
      </section>

      <div class="d-flex justify-content-end mt-4">
        <router-link to="/" class="btn btn-secondary me-2" :disabled="saving">Cancelar</router-link>
        <button type="submit" class="btn btn-primary" :disabled="saving">
          {{ saving ? 'Salvando...' : 'Atualizar' }}
        </button>
      </div>
    </form>

    <div v-else class="text-center py-4">
      <div class="spinner-border" role="status"><span class="visually-hidden">Carregando...</span></div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { UserService } from '../../services/UserService'
import type { UserPublic } from '../../models/User'
import TimezoneSelect from '../../components/inputs/TimezoneSelect.vue'
import { useAuthStore } from '../../stores/authStore'
import { useNotify } from '@/composables/useNotify'

const router = useRouter()
const { success, warning, error } = useNotify()
const userService = UserService.getInstance()
const authStore = useAuthStore()

const loading = ref(true)
const saving = ref(false)
const isEdit = ref(false)

const user = ref<UserPublic>({
  id: undefined,
  uuid: '',
  name: '',
  email: '',
  username: '',
  active: 0,
  timezone: '',
  roles: undefined
})

watch(
  () => authStore.user,
  (u) => {
    if (u && u.id) {
      user.value.id = u.id
      user.value.name = u.name ?? user.value.name
      user.value.email = u.email ?? user.value.email
    }
  },
  { immediate: true }
)

onMounted(async () => {
  loading.value = true
  try {
    const loggedUserId = authStore.user?.id
    if (!loggedUserId) {
      warning(`Usuário não encontrado!`)

      loading.value = false
      return
    }

    isEdit.value = true
    const resp = await userService.get(loggedUserId)
    const data = (resp as any)?.data ?? resp

    if (!data) {
      warning('usuário não encontrado, sem resposta na obtenção dos dados')
      console.log('ProfileEdit: resposta de userService.get() sem dados.', resp)
      loading.value = false
      return
    }

    user.value = {
      id: data.id ?? user.value.id,
      uuid: data.uuid ?? data.uuid ?? user.value.uuid,
      name: data.name ?? '',
      email: data.email ?? '',
      username: data.username ?? '',
      active: typeof data.active !== 'undefined' ? data.active : 1,
      timezone: data.timezone ?? '',
      roles: data.roles ?? ''
    }
  } catch (err) {
    error('Erro ao carregar dados do usuário')
    console.error('Erro ao carregar dados do usuário:', err)
  } finally {
    loading.value = false
  }
})

const save = async () => {
  if (!user.value?.id) {
    error('Usuário inválido. Faça login novamente.')
    return
  }

  saving.value = true
  try {
    const resp = await userService.update(user.value.id, user.value)
    const updated = (resp as any)?.data ?? resp

    authStore.user = { ...authStore.user, ... (updated ?? user.value) }

    success('Perfil atualizado com sucesso.')
    router.push('/')
  } catch (err) {
    console.error('Erro ao salvar usuário:', err)
    error('Erro ao salvar perfil. Verifique o console.')
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
</style>
