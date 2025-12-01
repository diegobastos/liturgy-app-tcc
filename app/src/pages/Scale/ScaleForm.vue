<template>
  <div class="container">
    <h2 class="mb-4">{{ isEdit ? 'Editar Escala' : 'Criar Nova Escala' }}</h2>

    <form @submit.prevent="save" class="p-1">
      <section>
        <h5 class="fw-bold mb-3 text-primary">Dados da Escala</h5>

        <div class="row row g-3 align-items-end mb-3">
          <div class="col-md-3">
            <label class="form-label fw-semibold small">Liturgia</label>
            <select v-model="scale.event_id" class="form-select form-select-sm" disabled>
              <option disabled value="">Selecione...</option>
              <option v-for="e in events" :key="e.id" :value="e.id">{{ e.name }}</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold small">Tipo de Escala</label>
            <select v-model="scale.scale_type_id" class="form-select form-select-sm" disabled>
              <option disabled value="">Selecione...</option>
              <option v-for="t in types" :key="t.id" :value="t.id">{{ t.name }}</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold small">Início</label>
            <input v-model="scale.start_at" type="datetime-local" class="form-control form-control-sm" required disabled/>
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold small">Fim</label>
            <input v-model="scale.end_at" type="datetime-local" class="form-control form-control-sm" required disabled/>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold small">Anotações</label>
          <textarea v-model="scale.notes" class="form-control form-control-sm" rows="3"
            placeholder="Observações, instruções especiais, etc."></textarea>
        </div>
      </section>

      <section class="mb-4">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h5 class="fw-bold mb-3 text-primary">Pessoas</h5>

          <div class="d-flex gap-2">
            <button v-if="isEdit && scaleMembers.length" type="button" class="btn btn-outline-danger btn-sm"
              @click="clearMembers" :disabled="membersLoading">
              Remover todos
            </button>
            <span v-if="membersLoading" class="align-self-center small text-muted">Processando...</span>
          </div>
        </div>

        <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
          <select v-model="newMember.user_id" class="form-select form-select-sm" style="width: 260px">
            <option disabled value="">Selecione um membro</option>
            <option v-for="u in allUsers" :key="u.id" :value="u.id">{{ u.name }}</option>
          </select>

          <input v-model="newMember.role" type="text" class="form-control form-control-sm"
            placeholder="Função (ex: vocal, guitarra)" style="width: 260px" />

          <button type="button" class="btn btn-sm btn-primary" @click="addMember" :disabled="addingMember">
            {{ addingMember ? 'Adicionando...' : 'Adicionar' }}
          </button>
        </div>

        <table class="table table-sm table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Nome</th>
              <th>Função</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(m, index) in scaleMembers" :key="m.id ?? `new-${index}`">
              <td>{{ m.user.name }} <small class="text-info">{{ 'email@email.com' }}</small> </td>
              <td>{{ m.role }}</td>
              <td>
                <button type="button" class="btn btn-danger btn-sm" @click="removeMember(index)">Remover</button>
              </td>
            </tr>
            <tr v-if="!scaleMembers.length">
              <td colspan="4" class="text-center text-muted small py-3">
                Nenhum membro adicionado
              </td>
            </tr>
          </tbody>
        </table>
      </section>

      <div class="d-flex justify-content-end mt-4">
        <router-link to="/scales" class="btn btn-secondary me-2" :disabled="saving">Cancelar</router-link>
        <button v-if="isEdit" class="btn btn-danger me-2" @click="deleteScale(scale)" :disabled="saving">Excluir</button>        
        <button type="submit" class="btn btn-primary" :disabled="saving">
          {{ saving ? (isEdit ? 'Salvando...' : 'Criando...') : (isEdit ? 'Atualizar Escala' : 'Criar Escala') }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import { ScaleService, ScaleTypeService, ScaleMemberService } from '../../services/ScaleService'
import { EventService } from '../../services/EventService'
import { UserService } from '../../services/UserService'

import type { Scale, ScaleResponse, ScaleType, ScaleMember } from '../../models/Scale'
import type { Event } from '../../models/Event'
import type { UserPublic } from '../../models/User'
import { useNotify } from '@/composables/useNotify'

const {success, warning, error} = useNotify()

const scaleService = ScaleService.getInstance()
const memberService = ScaleMemberService.getInstance()
const eventService = EventService.getInstance()
const typeService = ScaleTypeService.getInstance()
const userService = UserService.getInstance()

const route = useRoute()
const router = useRouter()

const scale = ref<Scale>({
  scale_type_id: undefined,
  event_id: undefined,
  notes: '',
  start_at: '',
  end_at: '',
  members: []
})

const events = ref<Event[]>([])
const types = ref<ScaleType[]>([])
const allUsers = ref<UserPublic[]>([])

const isEdit = ref(false)
const saving = ref(false)
const membersLoading = ref(false)
const addingMember = ref(false)

const scaleMembers = ref<ScaleResponse['members']>([])
const newMember = ref<ScaleMember>({
  user_id: 0,
  role: undefined,
  status: 'PENDING'
})

const formatDateTimeLocal = (dateString?: string | null) => {
  if (!dateString) return undefined
  const date = new Date(dateString)
  const local = new Date(date.getTime() - date.getTimezoneOffset() * 60000)
  return local.toISOString().slice(0, 16)
}

onMounted(async () => {
  try {
    const [typeResp, userResp, eventResp] = await Promise.all([
      typeService.list(),
      userService.list(),
      eventService.list()      
    ])

    events.value = (eventResp as any).data ?? eventResp
    types.value = (typeResp as any).data ?? typeResp
    allUsers.value = (userResp as any).data ?? userResp

    const id = route.params.id
    if (id) {
      isEdit.value = true
      const response = await scaleService.get<ScaleResponse>(Number(id))
      const s = (response as any).data ?? response

      scale.value = {
        id: s.id,
        notes: s.notes || '',
        start_at: formatDateTimeLocal(s.start_at || s.event?.start_at || ''),
        end_at: formatDateTimeLocal(s.end_at || s.event?.end_at || ''),
        event_id: s.event?.id ?? undefined,
        scale_type_id: s.type?.id ?? undefined,
        members: s.members?.map((m: { user: { id: any }; role: any; status: any }) => ({
          user_id: m.user.id,
          role: m.role,
          status: m.status
        })) ?? []
      }

      scaleMembers.value = s.members ?? []
    }
  } catch (err) {
    console.error('Erro ao carregar dados auxiliares', err)
    error('Erro ao carregar dados.')
  }
})

watch(
  () => newMember.value.user_id,
  (userId) => {
    if (!userId) {
      newMember.value.role = undefined
      return
    }
    const u = allUsers.value.find(x => x.id === userId)
    if (!u) {
      newMember.value.role = ''
      return
    }
    const roles = [(u as any).roles].filter(Boolean).join(', ')
    newMember.value.role = roles || undefined
  }
)

watch(
  () => scale.value.event_id,
  (eventId) => {
    if (!eventId) return
    const sel = events.value.find(e => e.id === eventId)
    if (!sel) return
    scale.value.start_at = formatDateTimeLocal(sel.start_at)
    scale.value.end_at = formatDateTimeLocal(sel.end_at)
  }
)

function addMember() {
  if (!newMember.value.user_id) {
    warning('Selecione um usuário.')
    return
  }

  const exists = scaleMembers.value.some(m => m.user.id === newMember.value.user_id)
  if (exists) return

  const user = allUsers.value.find(u => u.id === newMember.value.user_id)
  if (!user) {
    warning('Usuário não encontrado na lista local.')
    return
  }

  addingMember.value = true
  try {
    scaleMembers.value.push({
      id: Date.now(), // temporário
      role: newMember.value.role!,
      status: 'PENDING',
      user
    } as any)
    newMember.value = { user_id: 0, role: undefined, status: 'PENDING'}
  } finally {
    addingMember.value = false
  }
}

function removeMember(index: number) {
  if (!confirm('Remover este membro localmente?')) return
  scaleMembers.value.splice(index, 1)
}

async function clearMembers() {
  if (!scale.value.id) return
  if (!confirm('Deseja remover todos os membros desta escala?')) return
  membersLoading.value = true
  try {
    await memberService.deleteMembers(scale.value.id)
    scaleMembers.value = []
    success('Todos os membros foram removidos.')
  } catch (err) {
    console.error('Erro ao remover membros', err)
    error('Erro ao remover membros.')
  } finally {
    membersLoading.value = false
  }
}

const deleteScale = async (scale: Scale) => {
  if (!scale.id) return
  if (!confirm('Deseja realmente excluir esta escala?')) return
  saving.value = true
  try {
    await scaleService.delete(scale.id)
    success('Escala excluída com sucesso.')
    await router.push('/scales')
  } catch (err) {
    console.error('Erro ao excluir Escala:', err)
    error('Erro ao excluir escala.')
  } finally {
    saving.value = false
  }
}

const buildPayload = (): Scale => ({
  event_id: scale.value.event_id!,
  scale_type_id: scale.value.scale_type_id!,
  notes: scale.value.notes?.trim() || '',
  start_at: scale.value.start_at,
  end_at: scale.value.end_at,
  members: scaleMembers.value.map(m => ({
    user_id: m.user.id,
    role: m.role?.trim() || '',
    status: m.status ?? 'PENDING'
  }))
})

const save = async () => {
  saving.value = true
  try {
    const payload = buildPayload()

    isEdit.value && scale.value.id
      ? await scaleService.update(scale.value.id, payload)
      : await scaleService.create(payload)

    success(isEdit.value ? 'Escala atualizada com sucesso.' : 'Escala criada com sucesso.')

    router.push('/scales')
  } catch (err) {
    console.error('Erro ao salvar escala:', err)
    error('Erro ao salvar escala.')
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
.table {
  font-size: 0.875rem;
}
</style>
