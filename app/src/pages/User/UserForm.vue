<template>
  <div class="container">
    <h2 class="mb-4">{{ isEdit ? 'Editar' : 'Cadastrar' }} Usuário</h2>

    <form @submit.prevent="save" class="p-1">
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
              <input class="form-check-input me-2" type="checkbox" v-model="user.active" :true-value="1"
                :false-value="0" />
              <label class="form-check-label" for="ativoSwitch">
                Ativo
              </label>
            </div>
          </div>

        </div>
      </section>

      <div class="d-flex justify-content-end mt-4">
        <router-link to="/users" class="btn btn-secondary me-2">
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
import { UserService } from '../../services/UserService'
import type { UserPublic } from '../../models/User'
import TimezoneSelect from '../../components/inputs/TimezoneSelect.vue'
import { useNotify } from '@/composables/useNotify'

const route = useRoute()
const router = useRouter()
const {success, warning, error} = useNotify()
const userService = UserService.getInstance()
const user = ref<UserPublic>({
  uuid: '',
  name: '',
  email: '',
  username: '',
  active: 0,
  timezone: '',
  roles: undefined
})
const isEdit = ref(false)

onMounted(async () => {
  const id = route.params.id
  if (id) {
    isEdit.value = true
    user.value = await userService.get(Number(id))
  }
})

const save = async () => {
  try {
    if (isEdit.value && user.value.id) {
      await userService.update(user.value.id, user.value)
    } else {
      await userService.create(user.value)
    }
    success(isEdit.value ? 'Usuário atualizado com sucesso.' : 'Usuário criado com sucesso.')
    router.push('/users')
  } catch (err) {
    console.error('Erro ao salvar usuário:', err)
    error('Erro ao salvar usuário')
  }
}
</script>
