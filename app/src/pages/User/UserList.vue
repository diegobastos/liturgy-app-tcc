<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { UserService } from '../../services/UserService'
import type { User } from '../../models/User'

const router = useRouter()
const userService = UserService.getInstance()
const users = ref<User[]>([])
const page = ref(1)
const totalPages = ref(1)
const pageSize = 5
const loading = ref(false)

const filters = reactive({
  title: '',
  start_at: '',
  end_at: ''
})

const fetchUsers = async () => {
  loading.value = true
  try {
    const response = await userService.list(filters, page.value, pageSize)    
    users.value = response?.data || []
    totalPages.value = response.pagination?.last || 1

  } catch (error) {
    console.error('Erro ao buscar usuários:', error)
    users.value = []
    totalPages.value = 1
  } finally {
    loading.value = false
  }
}

const deleteUser = async (id?: number) => {
  if (!id) return
  if (!confirm('Deseja realmente excluir este usuário?')) return

  try {
    await userService.delete(id)
    await fetchUsers()
  } catch (error) {
    console.error('Erro ao excluir usuários:', error)
  }
}

const prevPage = () => {
  if (page.value > 1) {
    page.value--
    fetchUsers()
  }
}

const nextPage = () => {
  if (page.value < totalPages.value) {
    page.value++
    fetchUsers()
  }
}

onMounted(fetchUsers)
</script>

<template>
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Usuários</h2>
      <button class="btn btn-success" @click="router.push('/users/new')">Novo Usuário</button>
    </div>

    <form class="row g-3 mb-3" @submit.prevent="fetchUsers">
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
            <th>Apelido</th>
            <th>E-mail</th>
            <th>Ativo</th>
            <th class="text-end">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!(users?.length)">
            <td colspan="5" class="text-center text-muted py-3">Nenhum usuário encontrado.</td>
          </tr>
          <tr v-for="us in users" :key="us.id">
            <td>{{ us.name }}</td>
            <td>{{ us.username }}</td>
            <td>{{ us.email }}</td>
            <td>{{ us.active ? 'S' : 'N' }}</td>
            <td class="text-end">
              <button @click="router.push(`/users/edit/${us.id}`)" class="btn btn-sm btn-warning me-2">
                Editar
              </button>
              <button @click="deleteUser(us.id)" class="btn btn-sm btn-danger">
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
