<template>
  <div :class="['sidebar d-flex flex-column vh-100 bg-light border-end p-3']"
    :style="collapsed !== undefined ? { width: collapsed ? '60px' : '200px' } : { width: '200px' }">
    <div class="d-flex justify-content-between align-items-center mb-4" v-if="collapsed !== undefined">
      <span v-if="!collapsed" class="fw-bold">Plataforma</span>
      <a class="toggle-circle d-none d-md-flex" @click="$emit('toggle')">
        <i :class="collapsed ? 'bi bi-chevron-right' : 'bi bi-chevron-left'"></i>
      </a>
    </div>

    <ul class="nav nav-pills flex-column">
      <li class="nav-item" v-for="item in menu" :key="item.name">
        <router-link :to="item.path" class="nav-link d-flex align-items-center" @click="handleLinkClick">
          <i :class="item.icon"></i>
          <span v-if="!collapsed" class="ms-2">{{ item.name }}</span>
        </router-link>
      </li>
      
      <hr v-if="!collapsed" />
      <li class="nav-link d-flex align-items-center text-danger" @click="handleLogout">
        <i class="bi bi-box-arrow-right me-2"></i> <span v-if="!collapsed" class="ms-2">Sair</span>
      </li>      
    </ul>

  </div>
</template>

<script setup lang="ts">
import router from '../../router'
import { useAuthStore } from '../../stores/authStore'

const emit = defineEmits(['toggle'])
const { collapsed } = defineProps<{ collapsed?: boolean }>()

const menu = [
  { name: 'Dashboard', path: '/', icon: 'bi bi-speedometer2' },
  { name: 'Liturgias', path: '/events', icon: 'bi bi-book' },
  { name: 'Músicas', path: '/musics', icon: 'bi bi-music-note-beamed' },
  { name: 'Escalas', path: '/scales', icon: 'bi bi-calendar3' },
  { name: 'Usuários', path: '/users', icon: 'bi bi-people' },
  { name: 'Perfil', path: '/profile', icon: 'bi bi-person' },
]

const handleLogout = () => {
  const authStore = useAuthStore()
  authStore.logout()
  router.push('/login')
}

const handleLinkClick = () => {
  if (window.innerWidth < 768) {
    emit('toggle')
  }
}
</script>

<style scoped>
.sidebar {
  transition: width 0.3s ease;
}

.nav-link i {
  font-size: 1.2rem;
}

.toggle-circle {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: #f1f1f1;
  color: #333;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  transition: 0.25s;
}

.toggle-circle:hover {
  background: #e2e2e2;
}
</style>
